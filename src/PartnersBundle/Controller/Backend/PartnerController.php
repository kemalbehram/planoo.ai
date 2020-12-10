<?php

namespace PartnersBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use PartnersBundle\Entity\Partner;

/**
 * Partner controller.
 *
 */
class PartnerController extends Controller
{
    /**
     * Lists all Partner entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $partners = $em->getRepository('PartnersBundle:Partner')->findAll();

        return $this->render('PartnersBundle:Backend:index.html.twig', array(
            'partners' => $partners,
        ));
    }

    /**
     * Creates a new Partner entity.
     *
     */
    public function newAction(Request $request)
    {
        $partner = new Partner();
        $form = $this->createForm('PartnersBundle\Form\PartnerType', $partner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($partner);
            $em->flush();

            return $this->redirectToRoute('partner_back_partner_index');
        }

        return $this->render('PartnersBundle:Backend:new.html.twig', array(
            'partner' => $partner,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Partner entity.
     *
     */
    public function showAction(Partner $partner)
    {
        $deleteForm = $this->createDeleteForm($partner);

        return $this->render('PartnersBundle:Backend:show.html.twig', array(
            'partner' => $partner,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Partner entity.
     *
     */
    public function editAction(Request $request, Partner $partner)
    {
        $deleteForm = $this->createDeleteForm($partner);
        $editForm = $this->createForm('PartnersBundle\Form\PartnerType', $partner);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($partner);
            $em->flush();

            return $this->redirectToRoute('partner_back_partner_edit', array('id' => $partner->getId()));
        }

        return $this->render('PartnersBundle:Backend:edit.html.twig', array(
            'partner' => $partner,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
}
