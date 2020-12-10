<?php

namespace PromotionBundle\Controller\Backend;

use PromotionBundle\Entity\Action;
use PromotionBundle\Entity\Coupon;
use PromotionBundle\Form\CouponPartnerType;
use PromotionBundle\Form\CouponType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PartnersBundle\Service\IzIdentificationPartnerService;

/**
 * Coupon controller.
 *
 */
class CouponController extends Controller {

    /**
     * Lists all coupon entities.
     *
     */
    public function indexAction($isAdminView) {

        $identificationPartnerService = $this->container->get(IzIdentificationPartnerService::class);
        $partner = $identificationPartnerService->getCurrentPartner();

        return $this->render('PromotionBundle:Backend:Coupon/index.html.twig', [
                    'partner' => $partner,
                    'isAdminView' => $isAdminView
        ]);
    }

    /**
     * Creates a new coupon entity.
     *
     */
    public function newAction(Request $request) {
        $coupon = new Coupon();
        $form = $this->createForm(CouponType::class, $coupon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($coupon);
            $em->flush($coupon);

            return $this->redirectToRoute('promotion_admin_coupon_index', array('isAdminView' => true));
        }

        return $this->render('PromotionBundle:Backend:Coupon/new.html.twig', array(
                    'coupon' => $coupon,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new coupon entity.
     *
     */
    public function newPartnerCouponAction(Request $request) {
        $coupon = new Coupon();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CouponType::class, $coupon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $nbCouponToGenerate = $form['nbCouponToGenerate']->getData();

            for ($i = 0; $i < $nbCouponToGenerate; $i++) {
                $cstrong = true;
                $bytes = openssl_random_pseudo_bytes(4, $cstrong);
                $hexCode = bin2hex($bytes);

                $couponPartner = new Coupon();
                $couponPartner->setKind($coupon->getKind());
                $couponPartner->setName($coupon->getName());
                $couponPartner->setNbMaxUsed($coupon->getNbMaxUsed());
                $couponPartner->setStartsAt($coupon->getStartsAt());
                $couponPartner->setMinimumAmount($coupon->getMinimumAmount());
                $couponPartner->setRange($coupon->getRange());
                $couponPartner->setPartner($coupon->getPartner());
                $couponPartner->setCode($hexCode);
                $couponPartner->setEndsAt($coupon->getEndsAt());
                $couponPartner->setValue($coupon->getValue());

                $em->persist($couponPartner);
            }

            $em->flush();

            return $this->redirectToRoute('promotion_admin_coupon_index', array('isAdminView' => true));
        }

        return $this->render('PromotionBundle:Backend:Coupon/new.partner.coupon.html.twig', array(
                    'coupon' => $coupon,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a coupon entity.
     *
     */
    public function showAction(Coupon $coupon) {
        $deleteForm = $this->createDeleteForm($coupon);

        return $this->render('PromotionBundle:Backend:Coupon/show.html.twig', array(
                    'coupon' => $coupon,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing coupon entity.
     *
     */
    public function editAction(Request $request, Coupon $coupon) {
        $deleteForm = $this->createDeleteForm($coupon);
        $editForm = $this->createForm(CouponType::class, $coupon);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('promotion_admin_coupon_index', array('isAdminView' => true));
        }

        return $this->render('PromotionBundle:Backend:Coupon/edit.html.twig', array(
                    'coupon' => $coupon,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a coupon entity.
     *
     */
    public function deleteAction(Request $request, Coupon $coupon) {
        $form = $this->createDeleteForm($coupon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($coupon);
            $em->flush($coupon);
        }

        return $this->redirectToRoute('promotion_admin_coupon_index', array('isAdminView' => true));
    }

    /**
     * Creates a form to delete a coupon entity.
     *
     * @param Coupon $coupon The coupon entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Coupon $coupon) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('promotion_admin_coupon_delete', array('id' => $coupon->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

    /**
     * Remove an entity from the list
     * @param Request $request
     * @param Coupon $coupon
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deletedAction(Request $request, Coupon $coupon) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($coupon);
        $em->flush();

        return $this->redirectToRoute('promotion_admin_coupon_index', array('isAdminView' => true));
    }

    public function ajaxListAction(Request $request, $isAdminView) {

        $length = $request->get('length');
        $length = $length && ($length != -1) ? $length : 0;

        $start = $request->get('start');
        $start = $length ? ($start && ($start != -1) ? $start : 0) / $length : 0;

        $search = $request->get('search');
        $filters = [
            'query' => @$search['value']
        ];

        if ($isAdminView === 'true') {
            $partner = null;
        } else {
            $identificationPartnerService = $this->container->get(IzIdentificationPartnerService::class);
            $partner = $identificationPartnerService->getCurrentPartner();
        }

        $coupons = $this->getDoctrine()->getRepository('PromotionBundle:Coupon')->search(
                $filters, $start, $length, false, $partner
        );

        $output = array(
            'data' => array(),
            'recordsFiltered' => $this->getDoctrine()->getRepository('PromotionBundle:Coupon')->search($filters, 0, null, true, $partner),
            'recordsTotal' => $this->getDoctrine()->getRepository('PromotionBundle:Coupon')->search(array(), 0, null, true, $partner)
        );

        foreach ($coupons as $coupon) {

            $output['data'][] = [
                'name' => $coupon->getName(),
                'code' => $coupon->getCode(),
                'value' => $coupon->getKind() === 'AMOUNT' ? $coupon->getValue() . '€' : $coupon->getValue() . '%',
                'range' => $coupon->getRange(),
                'begin' => $coupon->getStartsAt() ? $coupon->getStartsAt()->format('d/m/Y') : null,
                'end' => $coupon->getEndsAt() ? $coupon->getEndsAt()->format('d/m/Y') : null,
                'used' => $coupon->getNbUsed() . '/' . $coupon->getNbMaxUsed(),
                'partner' => $coupon->getPartner()->getNom(),
                'actions' => '<a href="' . $this->generateUrl('promotion_admin_coupon_edit', array('id' => $coupon->getId())) . '" target="_blank" class="btn btn-warning"><i class="fa fa-pencil"></i></a>'
                . '<a href="' . $this->generateUrl('promotion_admin_coupon_deleted', array('id' => $coupon->getId())) . '" target="_blank" class="btn btn-warning"><i class="fa fa-trash"></i></a>'
            ];
        }

        return new Response(json_encode($output), 200, ['Content-Type' => 'application/json']);
    }

}
