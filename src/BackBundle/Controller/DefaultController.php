<?php

namespace BackBundle\Controller;

use PartnersBundle\Service\IzIdentificationPartnerService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Entity\User;
use Google_Client;

class DefaultController extends Controller {

    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $identificationPartnerService = $this->container->get(IzIdentificationPartnerService::class);
        $partner = $identificationPartnerService->getCurrentPartner();

        //set google authorization to access charts
        $client = new Google_Client();

        $client->setAuthConfig($this->container->getParameter('kernel.root_dir') . '/../src/PartnersBundle/Resources/config/Izypitch-a1e15d7d721f.json');
        $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);

        //retrieve Token [if cURL error see : https://stackoverflow.com/questions/18255203/google-php-api-client-ca-cert-error]
        $client->fetchAccessTokenWithAssertion();

        $all_time_created_users = $em->getRepository('UserBundle:User')->countUser(null, null, $partner);
        $all_time_nb_purchase = $em->getRepository('PaymentBundle:Cart')->countCart(true, null, null, $partner);
        $all_time_amount_purchase = $em->getRepository('PaymentBundle:Cart')->countAmountCart(null, null, $partner);

        $year = (new \DateTime())->format("Y");
        $mounth = (new \DateTime())->format("m");

        $this_mounth_nb_purchase = $em->getRepository('PaymentBundle:Cart')->countCart(true, $year, $mounth, $partner);
        $this_mounth_nb_aband_cart = $em->getRepository('PaymentBundle:Cart')->countCart(false, $year, $mounth, $partner);
        $this_mounth_amount_purchase = $em->getRepository('PaymentBundle:Cart')->countAmountCart($year, $mounth, $partner);
        $this_mounth_created_users = $em->getRepository('UserBundle:User')->countUser($year, $mounth, $partner);

        $this_year_nb_purchase = $em->getRepository('PaymentBundle:Cart')->countCart(true, $year, null, $partner);
        $this_year_nb_aband_cart = $em->getRepository('PaymentBundle:Cart')->countCart(false, $year, null, $partner);
        $this_year_amount_purchase = $em->getRepository('PaymentBundle:Cart')->countAmountCart($year, null, $partner);


        return $this->render('BackBundle:Default:index.html.twig', [
                    'all_time_created_users' => $all_time_created_users,
                    'all_time_nb_purchase' => $all_time_nb_purchase,
                    'all_time_amount_purchase' => $all_time_amount_purchase,
                    'this_mounth_nb_purchase' => $this_mounth_nb_purchase,
                    'this_mounth_nb_aband_cart' => $this_mounth_nb_aband_cart,
                    'this_mounth_amount_purchase' => $this_mounth_amount_purchase,
                    'this_mounth_created_users' => $this_mounth_created_users,
                    'this_year_nb_purchase' => $this_year_nb_purchase,
                    'this_year_nb_aband_cart' => $this_year_nb_aband_cart,
                    'this_year_amount_purchase' => $this_year_amount_purchase,
                    'partner' => $partner,
                    'this_mounth_start_date' => $year . '-' . $mounth . '-' . '01',
                    'this_year_start_date' => $year . '-' . '01' . '-' . '01',
                    'gaAccessToken' => $client->getAccessToken()['access_token'],
                    'gaIdView' => $this->container->getParameter('ga_view_all_id')
        ]);
    }

    public function sidebarAction() {
        return $this->render('BackBundle:_components:sidebar.html.twig');
    }

    public function listCartsAction($sold,$isAdminView) {

        $identificationPartnerService = $this->container->get(IzIdentificationPartnerService::class);
        $partner = $identificationPartnerService->getCurrentPartner();

        return $this->render('BackBundle:Back_Office:list_carts.html.twig', [
                    'partner' => $partner,
                    'isAdminView' => $isAdminView,
                    'sold' => $sold
        ]);
    }

    public function ajaxCartDataTableAction(Request $request,$sold, $isAdminView) {

        $length = $request->get('length');
        $length = $length && ($length != -1) ? $length : 0;

        $start = $request->get('start');
        $start = $length ? ($start && ($start != -1) ? $start : 0) / $length : 0;

        $search = $request->get('search');
        $filters = [
            'query' => @$search['value']
        ];

        $sold = $sold === 'true';

        if ($isAdminView === 'true') {
            $partner = null;
        } else {
            $identificationPartnerService = $this->container->get(IzIdentificationPartnerService::class);
            $partner = $identificationPartnerService->getCurrentPartner();
        }

        $carts = $this->getDoctrine()->getRepository('PaymentBundle:Cart')->search(
                $filters,$sold, $start, $length, false, $partner
        );

        $output = array(
            'data' => array(),
            'recordsFiltered' => $this->getDoctrine()->getRepository('PaymentBundle:Cart')->search($filters,$sold, 0, null, true, $partner),
            'recordsTotal' => $this->getDoctrine()->getRepository('PaymentBundle:Cart')->search(array(),$sold, 0, null, true, $partner)
        );


        $amountFormatter = $this->container->get('izypitch.twig_extension');

        foreach ($carts as $cart) {

            $articles = '';
            foreach ($cart->getCommandeCatalogs() as $commandeCatalog) {
                $articles = $articles . '<div>' . $commandeCatalog->getCatalog()->getName() . '</div>';
            }

            $output['data'][] = [
                'date' => $sold ? $cart->getPayment()->getPaymentDate()->format('d/m/Y') : $cart->getUpdatedAt()->format('d/m/Y'),
                'time' => $sold ? $cart->getPayment()->getPaymentDate()->format('H:i:s') : $cart->getUpdatedAt()->format('H:i:s'),
                'owner' => '<a href="' . $this->generateUrl('back_user_show', array('id' => $cart->getUser()->getId())) . '">' . $cart->getUser()->getUsername() . '</a></td>',
                'mail' => '<a href="' . $this->generateUrl('back_user_show', array('id' => $cart->getUser()->getId())) . '">' . $cart->getUser()->getEmail() . '</a></td>',
                'amount' => $amountFormatter->formatNumber(null, $cart->getTotalAmountHT(), '€', 2,false, $request),
                'promotion' => $cart->getCoupon() ? $cart->getCoupon()->getCode() : null,
                'articles' => $articles,
                'partner' => $cart->getUser()->getPartner() ? $cart->getUser()->getPartner()->getNom() : null
            ];
        }

        return new Response(json_encode($output), 200, ['Content-Type' => 'application/json']);
    }


    public function ajaxProjectsDataTableAction(Request $request, $sold, $isAdminView) {

        $length = $request->get('length');
        $length = $length && ($length != -1) ? $length : 0;

        $start = $request->get('start');
        $start = $length ? ($start && ($start != -1) ? $start : 0) / $length : 0;

        $search = $request->get('search');
        $filters = [
            'query' => @$search['value']
        ];

        $sold= $sold === 'true';

        if ($isAdminView === 'true') {
            $partner = null;
        } else {
            $identificationPartnerService = $this->container->get(IzIdentificationPartnerService::class);
            $partner = $identificationPartnerService->getCurrentPartner();
        }

        $bps = $this->getDoctrine()->getRepository('BPBundle:BusinessPlan')->search(
                $filters, $sold, $start, $length, false, $partner
        );

        $output = array(
            'data' => array(),
            'recordsFiltered' => $this->getDoctrine()->getRepository('BPBundle:BusinessPlan')->search($filters, $sold, 0, null, true, $partner),
            'recordsTotal' => $this->getDoctrine()->getRepository('BPBundle:BusinessPlan')->search(array(), $sold, 0, null, true, $partner)
        );


        $amountFormatter = $this->container->get('izypitch.twig_extension');
        
        foreach ($bps as $bp) {

            $paymentDate = null;
            $paymentTime = null;
            if( $bp->getCommandeCatalogs() && $bp->getCommandeCatalogs()[0] && $bp->getCommandeCatalogs()[0]->getCart() && $bp->getCommandeCatalogs()[0]->getCart()->getPayment() && $bp->getCommandeCatalogs()[0]->getCart()->getPayment()->getProcessingDate()){
                $paymentDate=$bp->getCommandeCatalogs()[0]->getCart()->getPayment()->getPaymentDate()->format('d/m/Y');
                $paymentTime=$bp->getCommandeCatalogs()[0]->getCart()->getPayment()->getPaymentDate()->format('H:i:s');
            }

            $output['data'][] = [
                'createdDate' => $bp->getCreatedAt()->format('d/m/Y'),
                'createdTime' => $bp->getCreatedAt()->format('H:i:s'),
                'mail' => '<a href="' . $this->generateUrl('back_user_show', array('id' => $bp->getUser()->getId())) . '">' . $bp->getUser()->getEmail() . '</a></td>',
                'step' => count($bp->getSteps()) > 0 ? $bp->getSteps()[count($bp->getSteps()) - 1] : 1,
                'capital' => $bp->getInformation() ? $amountFormatter->formatNumber(null, $bp->getInformation()->getCapital(), '€', 2,false, $request) : null,
                'actions' => $bp->getHash() ? '<a href="' . $this->generateUrl('financial_information_edit', array('hash' => $bp->getHash())) . '" target="_blank" class="btn btn-warning"><i class="fa fa-pencil"></i></a>' : null,
                'partner' => $bp->getUser()->getPartner() ? $bp->getUser()->getPartner()->getNom() : null,
                'projectKind' => $bp->getCatalog()->getName(),
                'paymentDate' => $paymentDate,
                'paymentTime' => $paymentTime
            ];
        }

        return new Response(json_encode($output), 200, ['Content-Type' => 'application/json']);
    }

    public function listProjectsAction($isAdminView,$sold) {

        $identificationPartnerService = $this->container->get(IzIdentificationPartnerService::class);
        $partner = $identificationPartnerService->getCurrentPartner();

        return $this->render('BackBundle:Back_Office:list_project.html.twig', [
                    'partner' => $partner,
                    'isAdminView' => $isAdminView,
                    'sold' => $sold
        ]);
    }

}
