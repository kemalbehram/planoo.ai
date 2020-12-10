<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use PromotionBundle\Entity\Catalog;

class DefaultController extends Controller {

    public function indexAction() {
        return $this->render('UserBundle:Default:index.html.twig');
    }

    private function getTargetUrlFromSession(SessionInterface $session) {

        $key = sprintf('_security.%s.target_path', $this->get('security.token_storage')->getToken()->getProviderKey());

        if ($session->has($key)) {
            return $session->get($key);
        }

        return null;
    }

    public function latestUsersAction() {
        $em = $this->getDoctrine()->getManager();
//        $latest_users = $em->getRepository('UserBundle:User')->getLatestUsers('8');
        $q = $em->createQuery("SELECT u FROM UserBundle:User u order by u.id desc");
        $latest_users = $q->setMaxResults('8')->getResult();

        return $this->render('UserBundle:Default:latest_users.html.twig', [
            'latest_users' => $latest_users,
        ]);
    }

    public function userMyProjectsAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $bps = $em->getRepository('BPBundle:BusinessPlan')->findBy([
            'user' => $this->getUser(),
        ], [
            'createdAt' => 'desc',
        ]);

        if (sizeof($bps) == 0) {
            $firstFormula = $em->getRepository('PromotionBundle:Catalog')->getFirstFormula();
            $response = new RedirectResponse($this->generateUrl('promotion_catalog_index'));
            return $response;
        }

        $popup = null;
        $catalog = null;
        if ($request->get('paidCart')){
            $cartId = $request->get('paidCart');
            $cart=$em->getRepository('PaymentBundle:Cart')->find($cartId);

            $catalog = $cart->getCommandeCatalogs()[0]->getCatalog();

            if($catalog->getId() == Catalog::CATALOG_FORMULE_ESSENTIELLE) {
                $popup = 'purchase-confirm-essentielle';
            } else if ($catalog->getId() == Catalog::CATALOG_FORMULE_PRO) {
                $popup = 'purchase-confirm-pro';
            } else if ($catalog->getId() == Catalog::CATALOG_FORMULE_PREMIUM) {
                $popup = 'purchase-confirm-premium';
            } else if ($catalog->getType() == Catalog::CATALOG_TYPE_BP_UPGRADE) {
                $popup = 'purchase-confirm-upgrade';
            }
        }

        if ($request->get('orderConfirm')){
            $popup = $request->get('orderConfirm');
        }

        return $this->render('UserBundle:Frontend:MyProjects/projects_list.html.twig', [
            'bps' => $bps,
            'popup' => $popup,
            'catalog' => $catalog
        ]);
    }

    public function userMyDocumentsAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $bps = $em->getRepository('BPBundle:BusinessPlan')->findBy([
            'user' => $this->getUser(),
        ], [
            'createdAt' => 'desc',
        ]);
        
        return $this->render('UserBundle:Frontend:MyDocuments/documents_list.html.twig', [
            'bps' => $bps,
        ]);
    }
}
