<?php

/**
 * Created by PhpStorm.
 * User: wac28
 * Date: 10/10/16
 * Time: 16:56
 */

namespace BPBundle\Controller\Frontend;

use BPBundle\Form\CustomWritingType;
use BPBundle\Form\MarketStudyType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use BPBundle\Entity\CustomWriting;
use BPBundle\Entity\MarketStudy;
use BPBundle\Controller\Frontend\DefaultController;
use PromotionBundle\Entity\Catalog;

/**
 * Etape 11 - Custom Writing
 * @param Request $request
 * @param string $hash
 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
 */

class WordingController extends Controller {

    public function customWritingEditAction(Request $request, $hash) {
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();

        if (!$this->getUser()) {
            return $this->redirectToRoute('user_my_projects');
        }

        $historyFormSession = $session->get('bp_formulaire');

        if ($hash) {
            // BP
            $bp = $em->getRepository('BPBundle:BusinessPlan')->findOneBy(['hash' => $hash]);
            // $session->set('bp_formulaire', $historyFormSession);
            $historyFormSession['business_plan'] = $bp->getId();
            $currentStepRoute = 'wording_custom_writing_index';
            $historyFormSession['currentStepRoute'] = $currentStepRoute;
            $session->set('bp_formulaire', $historyFormSession);

        } else {
            // Etape du BP
            $idBusinessPlan = $historyFormSession['business_plan'];
            $bp = $em->getRepository('BPBundle:BusinessPlan')->find($idBusinessPlan);
        }

        //check permissions
        $this->denyAccessUnlessGranted(null, $bp);


        if (!$customWriting = $bp->getCustomWriting()) {
            $customWriting = new CustomWriting();
            $customWriting->setBusinessPlan($bp);
        }
        // GESTION DU FORMULAIRE
        $form = $this->createForm(CustomWritingType::class, $customWriting);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            /** @var UploadedFile $cvFile */
            $cvFile = $form->get('cvFile')->getData();

            // this condition is needed because the 'cv' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($cvFile) {
                $originalFilename = pathinfo($cvFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $cvFile->guessExtension();

                // Move the file to the directory where brochures are stored
                $cvFile->move(
                        $this->getParameter('cv_directory'), $newFilename
                );


                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $customWriting->setCvFileName($newFilename);
            }


            $em->persist($customWriting);
            $bp->addStep('11');
            $em->persist($bp);
            $em->flush();
            $historyFormSession['step'] = $bp->getSteps();
            $currentStepRoute = "wording_market_study_index";
            $historyFormSession['currentStepRoute'] = $currentStepRoute;
            $session->set('bp_formulaire', $historyFormSession);

            return $this->redirectToRoute($currentStepRoute);
        }

        //die(dump($session));
        $infos_page = $em->getRepository('BPBundle:Page')->findOneBy(['step' => 11]);

        return $this->render('BPBundle:Frontend:Wording/custom_writing.html.twig', [
                    'bp' => $bp,
                    'steps' => $bp->getSteps(),
                    'bpHash' => $bp->getHash(),
                    'infos_page' => $infos_page,
                    'form' => $form->createView()
        ]);
    }



    public function marketStudyEditAction(Request $request) {
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();

        if (!$this->getUser()) {
            return $this->redirectToRoute('user_my_projects');
        }

        $historyFormSession = $session->get('bp_formulaire');

        // Etape du BP
        $idBusinessPlan = $historyFormSession['business_plan'];
        $bp = $em->getRepository('BPBundle:BusinessPlan')->find($idBusinessPlan);

        //check permissions
        $this->denyAccessUnlessGranted(null, $bp);


        if (!$marketStudy = $bp->getMarketStudy()) {
            $marketStudy = new MarketStudy();
            $marketStudy->setBusinessPlan($bp);
        }
        // GESTION DU FORMULAIRE
        $form = $this->createForm(MarketStudyType::class, $marketStudy);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $bp->addStep('12');
            $em->persist($marketStudy);
            $em->flush();
            $currentStepRoute = "wording_market_study_index";
            $historyFormSession['step'] = $bp->getSteps();
            $historyFormSession['currentStepRoute'] = $currentStepRoute;
            $historyFormSession['business_plan'] = $bp->getId();

            if(in_array('11',$bp->getSteps())) {
                return $this->redirectToRoute("back_order_BP_internal_service", array('idBusinessPlan' => $bp->getId(), 'idCatalog' => Catalog::CATALOG_PREZ_PROJECT_FORMULA_ID));
            } else {
                return $this->redirectToRoute('wording_custom_writing_index');
            }

            
        }
        $infos_page = $em->getRepository('BPBundle:Page')->findOneBy(['step' => 12]);

        return $this->render('BPBundle:Frontend:Wording/market_study.html.twig', [
                    'bp' => $bp,
                    'steps' => $bp->getSteps(),
                    'bpHash' => $bp->getHash(),
                    'infos_page' => $infos_page,
                    'form' => $form->createView()
        ]);
    }


    // /**
    //  * Gestion de tableau de session 
    //  * @param $session, $element
    //  */
    // private function isSessionElementExist($session, $element) {
    //     if (!$session->has($element))
    //         $session->set($element, array());
    //     return $session->get($element);
    // }

}
