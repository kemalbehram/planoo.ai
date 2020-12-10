<?php

namespace BPBundle\Controller\Frontend;

use BPBundle\Entity\Bfr;
use BPBundle\Entity\BusinessPlan;
use BPBundle\Entity\Charge;
use BPBundle\Entity\Information;
use BPBundle\Entity\Investissement;
use BPBundle\Entity\Produit;
use BPBundle\Entity\Service;
use BPBundle\Entity\Staff;
use BPBundle\Form\BfrType;
use BPBundle\Form\FinanceType;
use BPBundle\Form\InformationType;
use BPBundle\Form\ProduitType;
use BPBundle\Form\SaisonnaliteBPType;
use DateInterval;
use DateTime;
use PromotionBundle\Checker\AmountTotalChecker;
use PromotionBundle\Entity\Catalog;
use PromotionBundle\Checker\DateEligibilityChecker;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller {

    public function initAction(Request $request, $idCatalog) {

        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();
        $session->remove('bp_formulaire');

        // Get Catalog
        $catalog = $em->getRepository('PromotionBundle:Catalog')->find($idCatalog);

        // Create Service
        $service = new Service();
        $expirationTrialDate = new \DateTime();
        $expirationTrialDate->add(new DateInterval('P15D'));
        $service->setExpireTrialDate($expirationTrialDate);
        $service->setUser($this->getUser());
        if ($catalog->getNbAdviceHour()) {
            $service->setAdviceHour($catalog->getNbAdviceHour());
        }
        if ($catalog->getHasWording()) {
            $service->setNbWording(1);
        }
        $em->persist($service);
        
        // Create BP
        $bp = new BusinessPlan();
        $bp->setState('trial');
        $bp->setUser($this->getUser());
        $bp->setService($service);
        $bp->setCatalog($catalog);
        $em->persist($bp);
        
        // Associate BP to Service
        $service->setBusinessPlan($bp);
        $em->persist($service);

        $em->flush();

        $bfr = new Bfr();
        $bfr->setUser($this->getUser());
        $bfr->setBusinessPlan($bp);
        $em->persist($bfr);
        $em->flush();

        $session->set('bp_formulaire', array());
        $historyFormSession = $session->get('bp_formulaire');
        $currentStepRoute = 'financial_information_index';
        $historyFormSession['step'] = $bp->getSteps();
        $historyFormSession['business_plan'] = $bp->getId();
        $historyFormSession['currentStepRoute'] = $currentStepRoute;

        $session->set('bp_formulaire', $historyFormSession);

        return $this->redirectToRoute('financial_information_index');
    }

    public function PrivacyPolicyUpdateAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('BackBundle:Page')->myFindOneBy('politique-de-confidentialite');
        if (!$page) {
            throw $this->createNotFoundException('Unable to find this page.');
        }

        return $this->render('BPBundle:Frontend:privacy_policy_update.html.twig', [
            'page' => $page,
            'orginal_path' => '',
            'newsletterAccepted' => $this->getUser()->getNewsletterAcceptedAt() != null,
            'commercialAccepted' => $this->getUser()->getCommercialAcceptedAt() != null,
        ]);
    }

    public function PrivacyPolicyUpdateDoAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $nlAccepted = $request->get('newsletterAccepted');
        $cAccepted = $request->get('commercialAccepted');

        if ($nlAccepted && $nlAccepted == 'on') {
            if ($user->getNewsletterAcceptedAt() == null) {
                $user->setNewsletterAcceptedAt(new DateTime());
            }
        } else {
            $user->setNewsletterAcceptedAt(null);
        }

        if ($cAccepted && $cAccepted == 'on') {
            if ($user->getCommercialAcceptedAt() == null) {
                $user->setCommercialAcceptedAt(new DateTime());
            }
        } else {
            $user->setCommercialAcceptedAt(null);
        }

        $user->setPrivacyPolicyAcceptedAt(new DateTime());

        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('user_my_projects');
    }

    /**
     * @Security("has_role('ROLE_USER')")
     *
     * Etape 1 - Information
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function informationAction(Request $request) {

        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();

        $idBusinessPlan = null;
        $bp = null;

        $historyFormSession = $session->get('bp_formulaire');
        if ($historyFormSession){
        $idBusinessPlan = $historyFormSession['business_plan'];
        }  
        if ($idBusinessPlan) {
            $bp = $em->getRepository('BPBundle:BusinessPlan')->find($idBusinessPlan);
        }

        //check permissions
        $this->denyAccessUnlessGranted(null, $bp);

        if ($bp->getInformation()) {
            $information = $bp->getInformation();
        } else {
            $information = new Information();
            $information->setBusinessPlan($bp);
            $em->persist($information);
        }

        $options = [
            'entityManager' => $em,
        ];

        // GESTION DU FORMULAIRE
        $form = $this->createForm(InformationType::class, $information, $options);
        $form->handleRequest($request);

        //dump($panier);die();
        if ($form->isSubmitted() && $form->isValid()) {
            $information->upload();
            $information->getAddress()->setUser($this->getUser());
            $em->persist($information);
            $bp->addStep('1');
            $em->persist($bp);
            $em->flush();
            $currentStepRoute = "financial_financiere_index";
            $historyFormSession['step'] = $bp->getSteps();
            $historyFormSession['currentStepRoute'] = $currentStepRoute;
            $historyFormSession['business_plan'] = $bp->getId();
            $session->set('bp_formulaire', $historyFormSession);

            return $this->redirectToRoute($currentStepRoute);
        }

        $infos_page = $em->getRepository('BPBundle:Page')->findOneBy(['step' => 1]);

        return $this->render('BPBundle:Frontend:Information/information.html.twig', [
            'form' => $form->createView(),
            'bp' => $bp,
            'steps' => $bp->getSteps(),
            'bpPaid' => $bp->getState() == 'validate',
            'bpHash' => $bp->getHash(),
            'infos_page' => $infos_page,
        ]);
    }

    /**
     * Etape 2 - Financière
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function financiereAction(Request $request) {
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();

        $historyFormSession = $session->get('bp_formulaire');
        $response = $this->stepValid($historyFormSession, 2);
        if ($response instanceof Response) {
            return $response;
        }

        // Etape du BP
        $idBusinessPlan = $historyFormSession['business_plan'];
        $bp = $em->getRepository('BPBundle:BusinessPlan')->find($idBusinessPlan);

        //check permissions
        $this->denyAccessUnlessGranted(null, $bp);

        $information = $bp->getInformation();

        $form=null;
        if($bp->getCatalog()->getId() == Catalog::CATALOG_FORMULE_ESSENTIELLE){
            $options = ['attr' => [
                'fixedAccountingPeriod' => 3,
                ]
            ];
            $information->setAccountingPeriod(3);
            $form = $this->createForm(FinanceType::class, $information,$options);
        } else {
            $form = $this->createForm(FinanceType::class, $information);
        }
       
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //double check if user manually enabled period accountancy input form
            if($bp->getCatalog()->getId() == Catalog::CATALOG_FORMULE_ESSENTIELLE){
                $information->setAccountingPeriod(3);
            }
            $bp->createExercice(clone $information->getCreatedAt(), clone $information->getClosingDate(), $information->getAccountingPeriod());
            $bp->checkDatesValidity(clone $information->getCreatedAt());
            $bp->addStep('2');
            $em->persist($bp);
            $em->flush();
            $historyFormSession['step'] = $bp->getSteps();
            $currentStepRoute = 'financial_season_index';
            $historyFormSession['currentStepRoute'] = $currentStepRoute;

            $session->set('bp_formulaire', $historyFormSession);

            return $this->redirectToRoute($currentStepRoute);
        }

        $infos_page = $em->getRepository('BPBundle:Page')->findOneBy(['step' => 2]);
        // dump($session);die();
        return $this->render('BPBundle:Frontend:Information/financiere.html.twig', [
            'form' => $form->createView(),
            'bp' => $bp,
            'steps' => $bp->getSteps(),
            'bpPaid' => $bp->getState() == 'validate',
            'bpHash' => $bp->getHash(),
            'infos_page' => $infos_page,
        ]);
    }

    /**
     * Etape 3 - Saisonnabilité
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function seasonAction(Request $request) {
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();

        $historyFormSession = $session->get('bp_formulaire');
        $response = $this->stepValid($historyFormSession, 3);
        if ($response instanceof Response) {
            return $response;
        }

        $idBusinessPlan = $historyFormSession['business_plan'];
        $bp = $em->getRepository('BPBundle:BusinessPlan')->find($idBusinessPlan);

        //check permissions
        $this->denyAccessUnlessGranted(null, $bp);

        $bp->addStep('3');
        $em->persist($bp);
        $em->flush();

        $form = $this->createForm(SaisonnaliteBPType::class, $bp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($bp);
            $em->flush();
            $historyFormSession['step'] = $bp->getSteps();
            $currentStepRoute = 'financial_income_index';
            $historyFormSession['currentStepRoute'] = $currentStepRoute;
            $session->set('bp_formulaire', $historyFormSession);

            return $this->redirectToRoute($currentStepRoute);
        }

        $infos_page = $em->getRepository('BPBundle:Page')->findOneBy(['step' => 3]);

        return $this->render('BPBundle:Frontend:Season/index.html.twig', [
            'form' => $form->createView(),
            'bp' => $bp,
            'steps' => $bp->getSteps(),
            'bpPaid' => $bp->getState() == 'validate',
            'bpHash' => $bp->getHash(),
            'infos_page' => $infos_page,
        ]);
    }

    /**
     * Etape 4 - Income
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function incomeAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        $historyFormSession = $session->get('bp_formulaire');
        $response = $this->stepValid($historyFormSession, 4);

        if ($response instanceof Response) {
            return $response;
        }

        // Recuperation du BP
        $idBusinessPlan = $historyFormSession['business_plan'];
        $bp = $em->getRepository('BPBundle:BusinessPlan')->find($idBusinessPlan);

        //check permissions
        $this->denyAccessUnlessGranted(null, $bp);

        $bp->addStep('4');
        $em->persist($bp);
        $em->flush();
        $historyFormSession['step'] = $bp->getSteps();
        $produit = new Produit($bp);

        foreach ($bp->getExercices() as $exercice) {
            $produit->generateOneInfoProduct($exercice);
        }

        $accountingNbPeriod = $bp->getInformation()->getAccountingPeriod();
        $accountingDate = $bp->getInformation()->getClosingDate();

        //Handle the changing of form mode (standard/advance). When the type has to be changed, 'isAdvanceMode' property is sent in the ajax request
        $options = [];
        if ($request->request->get('isAdvanceMode')) {
            $isAdvanceMode = $request->request->get('isAdvanceMode') === 'true';

            $options = ['attr' => ['isAdvanceMode' => $isAdvanceMode]];
        }
        $form = $this->createForm(ProduitType::class, $produit, $options);
        //END: form mode handling

        $form->handleRequest($request);

        $infos_page = $em->getRepository('BPBundle:Page')->findOneBy(['step' => 4]);
        $session->set('bp_formulaire', $historyFormSession);
        return $this->render('BPBundle:Frontend:Income/index.html.twig', [
            'form' => $form->createView(),
            'bp' => $bp,
            'steps' => $bp->getSteps(),
            'bpPaid' => $bp->getState() == 'validate',
            'bpHash' => $bp->getHash(),
            'accountingNbPeriod' => $accountingNbPeriod,
            'accountingDate' => $accountingDate,
            'idBusinessPlan' => $idBusinessPlan,
            'produits' => $bp->getProduits(),
            'infos_page' => $infos_page,
        ]);
    }

    /**
     * Etape 5 - Charge
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function chargeAction(Request $request) {
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();

        $historyFormSession = $session->get('bp_formulaire');

        $response = $this->stepValid($historyFormSession, 5);
        if ($response instanceof Response) {
            return $response;
        }

        // Recuperation du BP
        $idBusinessPlan = $historyFormSession['business_plan'];
        $bp = $em->getRepository('BPBundle:BusinessPlan')->find($idBusinessPlan);

        //check permissions
        $this->denyAccessUnlessGranted(null, $bp);

        $bp->addStep('5');
        $em->persist($bp);
        $em->flush();
        $historyFormSession['step'] = $bp->getSteps();
        $accountingNbPeriod = $bp->getInformation()->getAccountingPeriod();
        $accountingDate = $bp->getInformation()->getClosingDate();

        $infos_page = $em->getRepository('BPBundle:Page')->findOneBy(['step' => 5]);

        $session->set('bp_formulaire', $historyFormSession);
        return $this->render('BPBundle:Frontend:Charge/index.html.twig', [
            'steps' => $bp->getSteps(),
            'bpPaid' => $bp->getState() == 'validate',
            'bpHash' => $bp->getHash(),
            'bp' => $bp,
            'accountingNbPeriod' => $accountingNbPeriod,
            'accountingDate' => $accountingDate,
            'idBusinessPlan' => $idBusinessPlan,
            'infos_page' => $infos_page,
        ]);
    }

    /**
     * Etape 6 - Staff
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function staffAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        $historyFormSession = $session->get('bp_formulaire');
        $response = $this->stepValid($historyFormSession, 6);
        if ($response instanceof Response) {
            return $response;
        }

        // Recuperation du BP
        $idBusinessPlan = $historyFormSession['business_plan'];
        $bp = $em->getRepository('BPBundle:BusinessPlan')->find($idBusinessPlan);

        $dateStartActivity = $bp->getInformation()->getCreatedAt();
        $dateEndActivity = clone $bp->getInformation()->getClosingDate();
        $dateEndActivity = $dateEndActivity->modify('+' . ($bp->getInformation()->getAccountingPeriod() - 1) . ' years');

        //check permissions
        $this->denyAccessUnlessGranted(null, $bp);

        $bp->addStep('6');
        $em->persist($bp);
        $em->flush();
        $historyFormSession['step'] = $bp->getSteps();

        $infos_page = $em->getRepository('BPBundle:Page')->findOneBy(['step' => 6]);

        $session->set('bp_formulaire', $historyFormSession);
        return $this->render('BPBundle:Frontend:Staff/index.html.twig', [
            'steps' => $bp->getSteps(),
            'bpPaid' => $bp->getState() == 'validate',
            'bpHash' => $bp->getHash(),
            'bp' => $bp,
            'idBusinessPlan' => $idBusinessPlan,
            'dateStartActivity' => $dateStartActivity,
            'dateEndActivity' => $dateEndActivity,
            'infos_page' => $infos_page,
        ]);
    }

    /**
     * Etape 7 - BFR
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function bfrAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        $historyFormSession = $session->get('bp_formulaire');
        $response = $this->stepValid($historyFormSession, 7);
        if ($response instanceof Response) {
            return $response;
        }

        // Recuperation du BP
        $idBusinessPlan = $historyFormSession['business_plan'];
        $bp = $em->getRepository('BPBundle:BusinessPlan')->find($idBusinessPlan);

        //check permissions
        $this->denyAccessUnlessGranted(null, $bp);

        $bp->addStep('7');
        $em->persist($bp);
        $em->flush();

        // Récuperation du BFR
        $bfr = $bp->getInfoBfr();

        if (!$bfr) { //Si pas de bfr en BDD alors création d'un nouveau
            $bfr = new Bfr();
        }

        $bfr->setUser($this->getUser());
        $bfr->setBusinessPlan($bp);

        $form = $this->createForm(BfrType::class, $bfr);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($bfr);
            $em->flush();
            $historyFormSession['step'] = $bp->getSteps();
            $currentStepRoute = 'financial_investissement_index';
            $historyFormSession['currentStepRoute'] = $currentStepRoute;
            $session->set('bp_formulaire', $historyFormSession);

            return $this->redirectToRoute($currentStepRoute);
        } elseif ($bfr->getId() != null) {
            $session->set('bp_formulaire', $historyFormSession);
        }

        $infos_page = $em->getRepository('BPBundle:Page')->findOneBy(['step' => 7]);

        return $this->render('BPBundle:Frontend:Bfr/index.html.twig', [
            'bfr' => $bfr,
            'form' => $form->createView(),
            'steps' => $bp->getSteps(),
            'bpPaid' => $bp->getState() == 'validate',
            'bpHash' => $bp->getHash(),
            'bp' => $bp,
            'infos_page' => $infos_page,
        ]);
    }

    /**
     * Etape 8 - Investissement
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function investissementAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        $historyFormSession = $session->get('bp_formulaire');
        $response = $this->stepValid($historyFormSession, 8);
        if ($response instanceof Response) {
            return $response;
        }

        // Recuperation du BP
        $idBusinessPlan = $historyFormSession['business_plan'];
        $bp = $em->getRepository('BPBundle:BusinessPlan')->find($idBusinessPlan);

        $dateStartActivity = $bp->getInformation()->getCreatedAt();
        $dateEndActivity = clone $bp->getInformation()->getClosingDate();
        $dateEndActivity = $dateEndActivity->modify('+' . ($bp->getInformation()->getAccountingPeriod() - 1) . ' years');

        //check permissions
        $this->denyAccessUnlessGranted(null, $bp);

        $bp->addStep('8');
        $em->persist($bp);
        $em->flush();
        $historyFormSession['step'] = $bp->getSteps();
        // Récuperation de la liste des revenus
        $invests = $em->getRepository('BPBundle:Investissement')->myFindAllByBusinessPlan($bp);

        $infos_page = $em->getRepository('BPBundle:Page')->findOneBy(['step' => 8]);

        $session->set('bp_formulaire', $historyFormSession);
        return $this->render('BPBundle:Frontend:Investissement/index.html.twig', [
            'invests' => $invests,
            'steps' => $bp->getSteps(),
            'bpPaid' => $bp->getState() == 'validate',
            'bpHash' => $bp->getHash(),
            'idBusinessPlan' => $idBusinessPlan,
            'dateStartActivity' => $dateStartActivity,
            'dateEndActivity' => $dateEndActivity,
            'bp' => $bp,
            'infos_page' => $infos_page,
        ]
        );
    }

    /**
     * Etape 9 - Financement
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function fundingAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        $historyFormSession = $session->get('bp_formulaire');
        $response = $this->stepValid($historyFormSession, 9);
        if ($response instanceof Response) {
            return $response;
        }

        // Recuperation du BP
        $idBusinessPlan = $historyFormSession['business_plan'];
        $bp = $em->getRepository('BPBundle:BusinessPlan')->find($idBusinessPlan);

        $dateStartActivity = $bp->getInformation()->getCreatedAt();
        $dateEndActivity = clone $bp->getInformation()->getClosingDate();
        $dateEndActivity = $dateEndActivity->modify('+' . ($bp->getInformation()->getAccountingPeriod() - 1) . ' years');

        //check permissions
        $this->denyAccessUnlessGranted(null, $bp);

        $bp->addStep('9');
        $em->persist($bp);
        $em->flush();
        $historyFormSession['step'] = $bp->getSteps();
        $bp->setReference($idBusinessPlan);
        $bp->setUser($this->getUser());

        $infos_page = $em->getRepository('BPBundle:Page')->findOneBy(['step' => 9]);

        $session->set('bp_formulaire', $historyFormSession);
        return $this->render('BPBundle:Frontend:Funding/index.html.twig', [
            'idBusinessPlan' => $idBusinessPlan,
            'steps' => $bp->getSteps(),
            'bp' => $bp,
            'bpPaid' => $bp->getState() == 'validate',
            'bpHash' => $bp->getHash(),
            'dateStartActivity' => $dateStartActivity,
            'dateEndActivity' => $dateEndActivity,
            'infos_page' => $infos_page,
        ]);
    }

    /**
     * Vérification des etapes du formulaire : session et etape complétée
     * @param $$historyFormSession
     * @param $currentStep
     * @return bool|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function stepValid($historyFormSession, $currentStep) {
        //default path to step 1
        $route = 'user_my_projects';

        if (!empty($historyFormSession) && !empty($historyFormSession['step']) && !empty($historyFormSession['business_plan'])
            //If steps 1 and 2 are validated, user can go to any steps except paiement
             && (($currentStep > 2 && in_array(1, $historyFormSession['step']) && in_array(2, $historyFormSession['step']))
                //to go to step 2 step 1 have to be validated
                 || ($currentStep == 2 && in_array(1, $historyFormSession['step']))
                //user can go to step one at anytime
                 || ($currentStep == 1)
            )
        ) {
            return true;
        } elseif (!empty($historyFormSession['currentStepRoute'])) {
            $route = $historyFormSession['currentStepRoute'];
        }

        return $this->redirectToRoute($route);
    }

    /**
     * @param $coupon
     * @param $total_bp
     * @param $session
     * @return bool|mixed
     */
    public function checkPromotionAction($coupon, $total_bp, $session) {
        $totalHT = $total_bp;
        $totalTVA = 0;
        $total_cart = 0;
        $action = $coupon->getAction();
        $rule = $coupon->getRule();
        $command_qty = 0;
        $command_total_amount = 0;
        $tab = 'code_promo';
        $msgAmount = 'Promotion non applicable : le montant de la commande doit être au moins de ' . $rule->getConfiguration() . '€';
        $msgDate = 'Promotion non applicable : la date du coupon n\'est pas valide';

        // Verification des dates
        if (!DateEligibilityChecker::isEligible($coupon)) {
            die('ok');
            $this->msgSession($session, $tab, $msgDate);
            return false;
        }

        // Verification du montant
        if (!AmountTotalChecker::isEligible($totalHT, $coupon)) {
            $this->msgSession($session, $tab, $msgAmount);
            return false;
        }
        // Calcul du montant de la commande avec promotion
        $totalHT = $this->calculPromo($totalHT, $action->getConfiguration(), $action->getType());

        return $totalHT;
    }

    public function cronExpirationRemembererAction(Request $request) {
        $nbDays = 3;
        $em = $this->getDoctrine()->getManager();
        $bps = $em->getRepository('BPBundle:BusinessPlan')->findAllBusinessPlanExiredInXDays($nbDays);

        foreach ($bps as $bp) {
            $this->sendMailBPExpirationReminder($bp->getUser(), $nbDays, $this->get('mailer'));
            continue;
        }

        return new Response();
    }

    private function sendMailBPExpirationReminder($user, $nbDays, $mailer) {

        $message = (new \Swift_Message('Votre business plan expire bientôt !'))
            ->setFrom('noreply@' . $this->getParameter('mailer_domain'))
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView(
                    'BPBundle:Emails:bp_expiration_reminder.html.twig', [
                        'name' => $user->getUsername(),
                        'nbDays' => $nbDays,
                    ]
                ), 'text/html'
            )
        ;
        $mailer->send($message);
    }

    public function cronReportingUserWithoutPurchaseAction(Request $request) {
        $nbDays = 1;
        $date = new \DateTime();
        $date->sub(new \DateInterval('P' . $nbDays . 'D'));

        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('UserBundle:User')->findAllUserCreatedWithoutPurchase($date);

        if (sizeof($users) > 0) {
            $message = (new \Swift_Message('Planoo.ai - Nouveau(x) utilisateur(s) sans achat - ' . $date->format('d/m/Y')))
                ->setTo('sales@planoo.ai')
                ->setFrom('noreply@' . $this->getParameter('mailer_domain'), 'Planoo.ai')
                ->setBody($this->renderView('PaymentBundle:Mails:reporting_user_without_purchase.html.twig', [
                    'users' => $users,
                ]
                ), 'text/html'
                );

            $this->get('mailer')->send($message);
        }

        return new Response();
    }

    /**
     * Calcul du prix déduit de la promotion en fonction de la nature de la réduction (montant fixe / pourcentage)
     * @param $total
     * @param $applicator
     * @param $action
     * @return mixed
     */
    private function calculPromo($total, $applicator, $action) {
        if ($action == "Order_fixed_discount") {
            return $total - $applicator;
        }

        if ($action == "Order_percentage_discount") {
            return $total * (1 - ($applicator / 100));
        }

    }

    /**
     * Gestion des messages d'erreurs lors des contrôles des règles pour la validation de la promotion
     * @param $session
     * @param $element
     * @param $msg
     */
    private function msgSession($session, $element, $msg) {
        if ($session->has($element)) {
            $session->set($element, $msg);
        }

    }

    /**
     * @param $session
     * @param $sousTotal
     * @param $em
     * @return mixed
     */
    private function manageCatalog($items) {
        $total = 0;
        //die(dump($session->get('catalog')));
        foreach ($items as $item) {
            $total += $item->getPrice();
        }
        //die(dump($total));

        return $total;
    }

    private function getMontantTva($ht, $tauxTva) {
        return $ht * $tauxTva;
    }

    private function getMontantTtc($ht, $tva) {
        return $ht + $tva;
    }

    private function dispatchElement($arrayItems) {
        $array_tmp = [];
        foreach ($arrayItems as $item) {
            $array_tmp[$item->getId()] = $item->getPrice();
        }
        return $array_tmp;
    }

    // /**
    //  * Gestion de tableau de session
    //  * @param $session, $element
    //  */
    // private function isSessionElementExist($session, $element) {
    //     if (!$session->has($element)) {
    //         $session->set($element, array());
    //     }

    //     return $session->get($element);
    // }

}
