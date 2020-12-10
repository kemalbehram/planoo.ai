<?php

/**
 * Created by PhpStorm.
 * User: wac28
 * Date: 21/09/16
 * Time: 16:42
 */

namespace BPBundle\Controller\Frontend;

use BPBundle\Entity\Bfr;
use BPBundle\Pnl\Helper;
use JonnyW\PhantomJs\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BusinessPlanController extends Controller {

    /**
     * Delete a BP
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();
        $bp = $em->getRepository('BPBundle:BusinessPlan')->find($id);

        //check permissions
        $this->denyAccessUnlessGranted(null, $bp);

        if ($bp->getState() != 'validate') {
            $em->remove($bp);
            $em->flush();
        }

        return $this->redirectToRoute('user_my_projects');
    }

    /**
     * Show the pdf
     * @param $hash
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function businessPlan2PdfAction(Request $request, $hash) {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $bp = $em->getRepository('BPBundle:BusinessPlan')->findOneBy([
            'hash' => $hash,
        ]);

        //check permissions
        $this->denyAccessUnlessGranted(null, $bp);

        if (!$bp) {

            $this->addFlash('error', 'Une erreur inconnue est survenue');
            return $this->redirect($this->generateUrl('user_my_projects'));
        }

        if (!$bp->getInfoBfr()) {
            $bfr = new Bfr();
            $bfr->setUser($this->getUser());
            $bfr->setBusinessPlan($bp);
            $em->persist($bfr);
            $em->flush();
        }

        $nomDuFichier = 'Planoo_BP_' . $hash . '_' . time();
        $bpCreationPath = dirname(__DIR__) . '/../../../web/public/tmp/';

        //generation du html :
        $codeCountry = $bp->getInformation()->getAddress()->getCountry()->getId();
        $tauxChargesFiscales = $em->getRepository('BPBundle:Rate')->findBy(["country" => $codeCountry, 'type' => [1, 2]]);
        $smic = $em->getRepository('BPBundle:Smic')->find(1);
        $tauxIS = $em->getRepository('BPBundle:Rate')->findBy(["country" => $codeCountry, 'type' => [3, 4]]);

        $bp->calculPnl($tauxChargesFiscales, $tauxIS, $smic, $codeCountry);

        $infoBfr = $em->getRepository('BPBundle:Bfr')->findOneBy(["businessPlan" => $bp->getId()]);
        $bp->calculBilan($codeCountry, $infoBfr);
        $bp->calculPnlParAn();
        $bp->calculBilanParAn();
        $chargesDeDePersonnel = $this->calculChargesDePersonnel($bp);

        $nbExercice = $bp->getExercices()->count();
        // Creation des charts Compte de resultat et Chiffre d'affaire
        $chartCompteResultatJson = $this->getChartCompteResultatJson($bp->getInfoMensuel(), $nbExercice, $bp->getCreatedAt());
        $chartMargeJson = $this->getChartMargeJson($bp->getProduits());
        $chartBilanJson = $this->getChartBilanJson($bp->getInfoBilanAnnuel());

        $chargesVariables = array();
        $chargesExternes = array();
        foreach ($bp->getCharges() as $charge) {
            if ($charge->getTaux() != null) {
                $chargesVariables[] = $charge;
            } else {
                $chargesExternes[] = $charge;
            }
        }

        $result = $this->render('BPBundle:Frontend:Pdf/pdf-bp.html.twig', [
            'codeCountry' => $codeCountry,
            'bp' => $bp,
            'pdf' => $request->get('pdf', true),
            'chargesVariables' => $chargesVariables,
            'chargesExternes' => $chargesExternes,
            'chargesDeDePersonnel' => $chargesDeDePersonnel,
            'bfrBp' => $infoBfr,
            'tauxChargesFiscales' => $tauxChargesFiscales,
            'information' => $bp->getInformation(),
            'email' => $bp->getUser()->getEmail(),
            'chartCompteResultatJson' => $chartCompteResultatJson,
            'chartMargeJson' => $chartMargeJson,
            'chartBilanJson' => $chartBilanJson,
        ]);

        if ($request->get('pdf', true)) {

            $fileSystem = new Filesystem();
            $htmlFileName = $nomDuFichier . '.html';
            $htmlFilePath = $bpCreationPath . $htmlFileName;

            $client = Client::getInstance();
            // $client->getEngine()->debug(true);
            // var_dump($client->getLog());
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $pdfFileName = $nomDuFichier . '.pdf';
                $client->getEngine()->setPath(dirname(__DIR__) . '\..\..\..\vendor\bin\phantomjs.exe');
            } else {
                $pdfFileName = $bpCreationPath . $nomDuFichier . '.pdf';
                if (file_exists(dirname(__DIR__) . '/../../../vendor/bin/phantomjs')){
                    $client->getEngine()->setPath(dirname(__DIR__) . '/../../../vendor/bin/phantomjs');
                } else {
                    $client->getEngine()->setPath('/usr/local/bin/phantomjs');
                }
            }
            $fileSystem->dumpFile($htmlFilePath, $result->getContent());
            $client->getProcedureCompiler()->clearCache();
            $client->getProcedureCompiler()->disableCache();
            $client->getEngine()->addOption('--disk-cache=false');
            $client->getEngine()->addOption('--ssl-protocol=any');
            $client->getEngine()->addOption('--ignore-ssl-errors=true');
            $client->getEngine()->addOption('--web-security=false');

            if ($request->isSecure()) {
                $protocol = 'https://';
            } else {
                $protocol = 'http://';
            }
            $path = $protocol . $request->getHost() . str_replace(array('/app_dev.php', '/app_rc.php'), '', $this->container->get('router')->getContext()->getBaseUrl()) . '/public/tmp/' . $htmlFileName;
            $requestPhantom = $client->getMessageFactory()->createPdfRequest($path);

            $responsePhantom = $client->getMessageFactory()->createResponse();

            $requestPhantom->setFormat('A4');
            $requestPhantom->setOrientation('landscape');
            $requestPhantom->setMargin('0cm');
            $requestPhantom->setTimeout('10000');

            $requestPhantom->setOutputFile($pdfFileName);

            $client->send($requestPhantom, $responsePhantom);

            $fileSystem->remove($htmlFilePath);

            $response = new Response();
            if ($responsePhantom->getStatus() === 200) {
                $response->setContent(file_get_contents($pdfFileName));
                // modification du content-type pour forcer le téléchargement (sinon le navigateur internet essaie d'afficher le document)
                $response->headers->set('Content-Type', 'application/pdf');
                $response->headers->set('Content-disposition', 'filename=' . 'Planoo_BP.pdf');
            } else {
                echo ('Error : ' . $responsePhantom->getStatus());
            }
            return $response;
        } else {
            return $result;
        }
    }

    private function getChartCompteResultatJson($allInfoMensuel, $nbExercice, $dateDebut) {
        $chartCompteResultatJson = [];
        $sommeEbeInitial = 0;
        $sommeEbeFinal = 0;
        $sommeCaInitial = 0;
        $sommeCaFinal = 0;
        $margeBruteInitial = 0;
        $margeBruteFinal = 0;
        $currentExercice = 0;
        $commissionInital = 0;
        $commissionFinal = 0;
        $chargesExterneInital = 0;
        $chargesExterneFinal = 0;
        $personnelInitial = 0;
        $personnelFinal = 0;
        $impotEtTaxeInitial = 0;
        $impotEtTaxeFinal = 0;
        foreach ($allInfoMensuel as $infoMensuel) {
            $currentExercice = $infoMensuel->getNumExercice();
            if ($currentExercice == 1 || $currentExercice == $nbExercice) {
                if ($currentExercice == 1) {
                    $sommeEbeInitial += $infoMensuel->getEbe();
                    $sommeCaInitial += $infoMensuel->getCaMensuel();
                    $margeBruteInitial += $infoMensuel->getMargeBruteMensuelle();
                    $commissionInital += $infoMensuel->getAchatVariableMensuel();
                    $chargesExterneInital += $infoMensuel->getChargeFixeSurChargeMensuelle();
                    $personnelInitial += $infoMensuel->getChargePersonnelMensuelle() + $infoMensuel->getCiceMensuel();
                    $impotEtTaxeInitial += $infoMensuel->getImpotEtTaxeMensuel();
                }

                if ($currentExercice == $nbExercice) {
                    $sommeEbeFinal += $infoMensuel->getEbe();
                    $sommeCaFinal += $infoMensuel->getCaMensuel();
                    $margeBruteFinal += $infoMensuel->getMargeBruteMensuelle();
                    $commissionFinal += $infoMensuel->getAchatVariableMensuel();
                    $chargesExterneFinal += $infoMensuel->getChargeFixeSurChargeMensuelle();
                    $personnelFinal += $infoMensuel->getChargePersonnelMensuelle() + $infoMensuel->getCiceMensuel();
                    $impotEtTaxeFinal += $infoMensuel->getImpotEtTaxeMensuel();
                }
            }
        }

        $effetCa = $sommeCaInitial != 0 ? ($sommeCaFinal - $sommeCaInitial) * ($margeBruteInitial / $sommeCaInitial) : 0;
        $effetTauxMarge = $sommeCaFinal != 0 && $sommeCaInitial != 0 ? (($margeBruteFinal / $sommeCaFinal) - ($margeBruteInitial / $sommeCaInitial)) * $sommeCaFinal : 0;
        $commission = $commissionFinal - $commissionInital;
        $chargesExterne = $chargesExterneFinal - $chargesExterneInital;
        $personnel = $personnelFinal - $personnelInitial;
        $impotEtTaxe = $impotEtTaxeFinal - $impotEtTaxeInitial;
        $beginEndColor = "blue";
        array_push($chartCompteResultatJson, ["name" => "EBE " . $dateDebut->format('Y'), "y" => $sommeEbeInitial, "color" => $beginEndColor, "dataLabels" => ["inside" => false, "align" => "center", "enabled" => true, "format" => number_format($sommeEbeInitial, 0, '.', ' ')]]);
        array_push($chartCompteResultatJson, ["name" => "Effet CA", "y" => $effetCa, "dataLabels" => ["inside" => false, "align" => "center", "enabled" => true, "format" => number_format($effetCa, 0, '.', ' ')]]);
        array_push($chartCompteResultatJson, ["name" => "Effet TX marge", "y" => $effetTauxMarge, "dataLabels" => ["inside" => false, "align" => "center", "enabled" => true, "format" => number_format($effetTauxMarge, 0, '.', ' ')]]);
        array_push($chartCompteResultatJson, ["name" => "Charges Var.", "y" => $commission, "dataLabels" => ["inside" => false, "align" => "center", "enabled" => true, "format" => number_format($commission, 0, '.', ' ')]]);
        array_push($chartCompteResultatJson, ["name" => "Charges Ext.", "y" => $chargesExterne, "dataLabels" => ["inside" => false, "align" => "center", "enabled" => true, "format" => number_format($chargesExterne, 0, '.', ' ')]]);
        array_push($chartCompteResultatJson, ["name" => "Personnel", "y" => $personnel, "dataLabels" => ["inside" => false, "align" => "center", "enabled" => true, "format" => number_format($personnel, 0, '.', ' ')]]);
        array_push($chartCompteResultatJson, ["name" => "Impôts & Taxes", "y" => $impotEtTaxe, "dataLabels" => ["inside" => false, "align" => "center", "enabled" => true, "format" => number_format($impotEtTaxe, 0, '.', ' ')]]);
        array_push($chartCompteResultatJson, ["name" => "EBE " . $infoMensuel->getDate()->format('Y'), "y" => $sommeEbeFinal * -1, "color" => $beginEndColor, "dataLabels" => ["inside" => false, "align" => "center", "enabled" => true, "format" => number_format($sommeEbeFinal, 0, '.', ' ')]]);

        return $chartCompteResultatJson;
    }

    public function getChartMargeJson($produits) {
        $traitement = [];
        $return = [];
        foreach ($produits as $produit) {
            foreach ($produit->getInfoProduct() as $infoProduit) {
                if (!array_key_exists($produit->getId(), $traitement)) {
                    $traitement[$produit->getId()] = ['name' => $produit->getName(), 'data' => []];
                }
                $exerciceAnnee = $infoProduit->getExercice()->getDateDebut()->format('mY');
                $value = $infoProduit->getCAExercice() - $infoProduit->getCoutExercice();
                if (array_key_exists($exerciceAnnee, $traitement[$produit->getId()]['data'])) {
                    $value += $traitement[$produit->getId()]['data'][$exerciceAnnee];
                }
                $traitement[$produit->getId()]['data'][$exerciceAnnee] = $value;
            }
        }

        foreach ($traitement as $t) {
            array_push($return, ['name' => $t['name'], 'data' => array_values($t["data"]), "dataLabels" => ["enabled" => true]]);
        }

        return $return;
    }

    public function getChartBilanJson($infoBilanAnnuel) {
        $return = [];
        $stock = [];
        $client = [];
        $fournisseur = [];
        $dette = [];
        $bfr = [];
        foreach ($infoBilanAnnuel as $infoBilan) {
            array_push($stock, $infoBilan['stocks']);
            array_push($client, $infoBilan['client']);
            array_push($fournisseur, $infoBilan['fournisseur']);
            array_push($dette, ($infoBilan['detteFiscale'] + $infoBilan['detteSociale']));
            array_push($bfr, ($infoBilan['bfrExploitation']));
        }
        array_push($return, ['name' => 'stock', 'data' => $stock, "type" => 'column', "yAxis" => 1]);
        array_push($return, ['name' => 'client', 'data' => $client, "type" => 'column', "yAxis" => 1]);
        array_push($return, ['name' => 'fournisseur', 'data' => $fournisseur, "type" => 'column', "yAxis" => 1]);
        array_push($return, ['name' => 'dette fiscale & sociale', 'data' => $dette, "type" => 'column', "yAxis" => 1]);
        array_push($return, ['name' => 'BFR exploitation', 'data' => $bfr, "type" => 'spline', "yAxis" => 0]);

        return $return;
    }

    public function calculChargesDePersonnel($bp) {
        $chargesDePersonnel = [];
        foreach ($bp->getExercices() as $exercice) {
            $annee = $exercice->getDateDebut()->format('mY');
            $nbMoisExercice = Helper::nbMonthDiff($exercice->getDateDebut(), $exercice->getDateFin()) + 1;
            foreach ($bp->getStaffs() as $staff) {

                $nbMountOffset=0;
                //date de début pour l'exercice
                $dateDebutStaffExercice = null;
                if ($staff->getCreatedAt() <= $exercice->getDateDebut()){
                    $dateDebutStaffExercice = $exercice->getDateDebut();
                } else if ($staff->getCreatedAt() <= $exercice->getDateFin()){
                    $dateDebutStaffExercice = $staff->getCreatedAt();
                    $nbMountOffset += 1;
                }

                //date fin pour l'exercice
                $dateFinStaffExercice = null;
                if ($staff->getFinishedAt() >= $exercice->getDateDebut()){
                    if($staff->getFinishedAt() <= $exercice->getDateFin()){
                        $dateFinStaffExercice = $staff->getFinishedAt();
                    } else {
                        $dateFinStaffExercice = $exercice->getDateFin();
                        $nbMountOffset += 1;
                    }
                }

                $nbMoisPersonnel = 0;
                if ($dateFinStaffExercice && $dateDebutStaffExercice) {
                    $nbMoisPersonnel = Helper::nbMonthDiff($dateDebutStaffExercice, $dateFinStaffExercice) + $nbMountOffset;
                }
                $detailMasseSalariale = $staff->getDetailMasseSalariale();
                $detailMassePatronale = $staff->getDetailMassePatronale();
                $status = $staff->getStatus()->translate()->getName();
                $pole = $staff->getPole()->translate()->getName();
                $masseSalariale = (!empty($detailMasseSalariale[$annee]) ? $detailMasseSalariale[$annee] : 0);
                $massePatronale = (!empty($detailMassePatronale[$annee]) ? $detailMassePatronale[$annee] : 0);
                // Classement par status et effectif
                if (array_key_exists('status', $chargesDePersonnel) &&
                    array_key_exists($status, $chargesDePersonnel["status"]) &&
                    array_key_exists($annee, $chargesDePersonnel["status"][$status]) &&
                    array_key_exists("somme", $chargesDePersonnel["status"][$status][$annee]) &&
                    array_key_exists("effectif", $chargesDePersonnel["status"][$status][$annee])) {
                    $chargesDePersonnel["status"][$status][$annee]["somme"] += $masseSalariale + $massePatronale;
                    //$chargesDePersonnel["status"][$status][$annee]["sommePatronale"] += $massePatronale;
                    $chargesDePersonnel["status"][$status][$annee]["effectif"] += ($nbMoisPersonnel / $nbMoisExercice) * ($staff->getHours() / 100);
                } else {
                    $chargesDePersonnel["status"][$status][$annee]["somme"] = $masseSalariale + $massePatronale;
                    // $chargesDePersonnel["status"][$status][$annee]["sommePatronale"] = $massePatronale;
                    $chargesDePersonnel["status"][$status][$annee]["effectif"] = ($nbMoisPersonnel / $nbMoisExercice) * ($staff->getHours() / 100);
                }

                // Classement par pole et effectif
                if (array_key_exists('pole', $chargesDePersonnel) &&
                    array_key_exists($pole, $chargesDePersonnel["pole"]) &&
                    array_key_exists($annee, $chargesDePersonnel["pole"][$pole]) &&
                    array_key_exists("somme", $chargesDePersonnel["pole"][$pole][$annee]) &&
                    array_key_exists("effectif", $chargesDePersonnel["pole"][$pole][$annee])) {
                    $chargesDePersonnel["pole"][$pole][$annee]["somme"] += $masseSalariale + $massePatronale;
                    $chargesDePersonnel["pole"][$pole][$annee]["effectif"] += ($nbMoisPersonnel / $nbMoisExercice) * ($staff->getHours() / 100);
                } else {
                    $chargesDePersonnel["pole"][$pole][$annee]["somme"] = $masseSalariale + $massePatronale;
                    $chargesDePersonnel["pole"][$pole][$annee]["effectif"] = ($nbMoisPersonnel / $nbMoisExercice) * ($staff->getHours() / 100);
                }
            }
        }

        return $chargesDePersonnel;
    }

}
