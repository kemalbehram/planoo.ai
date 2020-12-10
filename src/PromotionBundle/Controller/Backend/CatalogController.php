<?php

namespace PromotionBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use PromotionBundle\Entity\Catalog;
use PromotionBundle\Form\CatalogType;

/**
 * Catalog controller.
 *
 */
class CatalogController extends Controller
{
    /**
     * Lists all Catalog entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $catalogs = $em->getRepository('PromotionBundle:Catalog')->findAll();

        return $this->render('PromotionBundle:Backend:Catalog/index.html.twig', array(
            'catalogs' => $catalogs,
        ));
    }

    /**
     * Creates a new Catalog entity.
     *
     */
    public function newAction(Request $request)
    {
        $catalog = new Catalog();
        $form = $this->createForm('PromotionBundle\Form\CatalogType', $catalog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($catalog);
            $em->flush();

            return $this->redirectToRoute('promotion_back_catalog_show', array('id' => $catalog->getId()));
        }

        return $this->render('PromotionBundle:Backend:Catalog/new.html.twig', array(
            'catalog' => $catalog,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Catalog entity.
     *
     */
    public function showAction(Catalog $catalog)
    {
        $deleteForm = $this->createDeleteForm($catalog);

        return $this->render('PromotionBundle:Backend:Catalog/show.html.twig', array(
            'catalog' => $catalog,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Catalog entity.
     *
     */
    public function editAction(Request $request, Catalog $catalog)
    {
        $deleteForm = $this->createDeleteForm($catalog);
        $editForm = $this->createForm('PromotionBundle\Form\CatalogType', $catalog);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($catalog);
            $em->flush();

            return $this->redirectToRoute('promotion_back_catalog_edit', array('id' => $catalog->getId()));
        }

        return $this->render('PromotionBundle:Backend:Catalog/edit.html.twig', array(
            'catalog' => $catalog,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Catalog entity.
     *
     */
    public function deleteAction(Request $request, Catalog $catalog)
    {
        $form = $this->createDeleteForm($catalog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($catalog);
            $em->flush();
        }

        return $this->redirectToRoute('promotion_back_catalog_index');
    }

    /**
     * Creates a form to delete a Catalog entity.
     *
     * @param Catalog $catalog The Catalog entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Catalog $catalog)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('promotion_back_catalog_delete', array('id' => $catalog->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Remove an entity from the list
     * @param Request $request
     * @param Catalog $catalog
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deletedAction(Request $request, Catalog $catalog)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($catalog);
        $em->flush();

        return $this->redirectToRoute('promotion_back_catalog_index');
    }
}
