<?php

namespace BPBundle\Controller\Backend;

use BPBundle\Entity\Country;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BPBundle\Entity\Rate;
use BPBundle\Form\RateType;

/**
 * Rate controller.
 *
 */
class RateController extends Controller
{
    /**
     * Lists all Rate entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

//        $rates = $em->getRepository('BPBundle:Country')->MyFindAllByCountry();
        $rates = $em->getRepository('BPBundle:Rate')->findAll();
//die(dump($rates));
        return $this->render('BPBundle:Backend:Rate/index.html.twig', array(
            'rates' => $rates,
        ));
    }

    /**
     * Creates a new Rate entity.
     *
     */
    public function newAction(Request $request)
    {
        $rate = new Rate();
//        $country = new Country();
//        $rate->addCountry($country);
        $form = $this->createForm(RateType::class, $rate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
//            die(dump($country));
            $em->persist($rate);
            $em->flush();

            return $this->redirectToRoute('back_rate_index');
        }

        return $this->render('BPBundle:Backend:Rate/new.html.twig', array(
            'rate' => $rate,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Rate entity.
     *
     */
    public function showAction(Rate $rate)
    {
        $deleteForm = $this->createDeleteForm($rate);

        return $this->render('BPBundle:Backend:Rate/show.html.twig', array(
            'rate' => $rate,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Rate entity.
     *
     */
    public function editAction(Request $request, Rate $rate)
    {
        $deleteForm = $this->createDeleteForm($rate);
        $editForm = $this->createForm('BPBundle\Form\RateType', $rate);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($rate);
            $em->flush();

            return $this->redirectToRoute('back_rate_index');
        }

        return $this->render('BPBundle:Backend:Rate/edit.html.twig', array(
            'rate' => $rate,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Rate entity.
     *
     */
    public function deleteAction(Request $request, Rate $rate)
    {
        $form = $this->createDeleteForm($rate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($rate);
            $em->flush();
        }

        return $this->redirectToRoute('back_rate_index');
    }

    /**
     * Creates a form to delete a Rate entity.
     *
     * @param Rate $rate The Rate entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Rate $rate)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('back_rate_delete', array('id' => $rate->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Remove an entity from the list
     * @param Request $request
     * @param Rate $rate
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deletedAction(Request $request, Rate $rate)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($rate);
        $em->flush();

        return $this->redirectToRoute('back_rate_index');
    }
}
