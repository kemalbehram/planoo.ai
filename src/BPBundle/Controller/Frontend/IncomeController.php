<?php

namespace BPBundle\Controller\Frontend;

use BPBundle\Form\ProduitType;
use BPBundle\Form\ProductSeasonBPType;
use BPBundle\Form\ProductStockSeasonBPType;
use BPBundle\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class IncomeController extends Controller {

    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository('BPBundle:Produit')->find($id);

        //check permissions
        $this->denyAccessUnlessGranted(null, $produit->getBusinessPlan());

        $form = $this->createForm(ProduitType::class, $produit);

        $form->submit($request->request->get($form->getName()));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($produit);
            $em->flush();
            return new JsonResponse(array(
                'code' => 200,
                'message' => 'OK',
                'errors' => array('errors' => array(''))), 200);
        }

        return new JsonResponse(array(
            'code' => 400,
            'message' => 'Invalid Form',
            'errors' => $this->getErrorMessages($form)), 400);
    }

    public function createAction(Request $request, $idBusinessPlan) {
        $em = $this->getDoctrine()->getManager();
        $bp = $em->getRepository('BPBundle:BusinessPlan')->find($idBusinessPlan);

        //check permissions
        $this->denyAccessUnlessGranted(null, $bp);

        // Création d'une nouvelle source de revenu
        $produit = new Produit($bp);
        foreach ($bp->getExercices() as $exercice) {
            $produit->generateOneInfoProduct($exercice);
        }

        $form = $this->createForm(ProduitType::class, $produit);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($produit);
            $em->flush();

            return new JsonResponse(array(
                'code' => 201,
                'id' => $produit->getId(),
                'message' => 'OK',
                'errors' => array('errors' => array(''))), 200);
        }

        return new JsonResponse(array(
            'code' => 400,
            'message' => 'Invalid Form',
            'errors' => $this->getErrorMessages($form)), 400);
    }

    public function showAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository('BPBundle:Produit')->find($id);

        if ($produit) {
            //check permissions
            $this->denyAccessUnlessGranted(null, $produit->getBusinessPlan());

            $encoders = [new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);

            return new JsonResponse(array(
                'code' => 201,
                'data' => $serializer->serialize($produit, 'json'),
                'message' => 'OK',
                'errors' => array('errors' => array(''))), 200);
        } else {
            return new JsonResponse(array(
                'code' => 404,
                'id' => $id,
                'message' => 'Object not found')
            );
        }
    }

    public function deleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository('BPBundle:Produit')->find($id);

        //check permissions
        $this->denyAccessUnlessGranted(null, $produit->getBusinessPlan());

        $em->remove($produit);
        $em->flush();


        return new JsonResponse(array(
            'code' => 200,
            'message' => 'OK',
            'errors' => array('errors' => array(''))), 200);
    }

    public function listAction(Request $request) {

        // Recuperation du BP
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $historyFormSession = $session->get('bp_formulaire');
        $idBusinessPlan = $historyFormSession['business_plan'];
        $bp = $em->getRepository('BPBundle:BusinessPlan')->find($idBusinessPlan);

        //check permissions
        $this->denyAccessUnlessGranted(null, $bp);

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        if ($request->getRequestFormat() === 'json') {
            return new JsonResponse(array(
                'code' => 200,
                'data' => $serializer->serialize($bp->getProduits(), 'json'),
                'message' => 'OK',
                'errors' => array('errors' => array(''))), 200);
        } elseif ($request->getRequestFormat() === 'html') {
            return $this->render('BPBundle:Frontend:Income/list.html.twig', [
                        'produits' => $bp->getProduits(),
                        'totalIncome' => $bp->getTotalIncome(),
                        'accountingNbPeriod' => $bp->getInformation()->getAccountingPeriod(),
                        'accountingDate' => $bp->getInformation()->getClosingDate(),
            ]);
        }
    }

    public function getEditFormAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository('BPBundle:Produit')->find($id);

        if ($produit) {
            //check permissions
            $this->denyAccessUnlessGranted(null, $produit->getBusinessPlan());

            //Handle the changing of form mode (standard/advance). When the type has to be changed, 'isAdvanceMode' property is sent in the ajax request
            $options = [];
            if ($request->get('isAdvanceMode')) {
                $isAdvanceMode = $request->get('isAdvanceMode') === 'true';

                $options = ['attr' => ['isAdvanceMode' => $isAdvanceMode]];
            }
            $form = $this->createForm(ProduitType::class, $produit, $options);
            //END: form mode handling

            $form->handleRequest($request);

            return $this->render('BPBundle:Frontend:Income/edit.html.twig', [
                        'produit' => $produit,
                        'editForm' => $form->createView(),
                        'action' => 'update',
                        'idIncome' => $id,
                        'accountingNbPeriod' => $produit->getBusinessPlan()->getInformation()->getAccountingPeriod()
            ]);
        }
    }

    public function getAddFormAction(Request $request, $idBusinessPlan) {
        $em = $this->getDoctrine()->getManager();
        $bp = $em->getRepository('BPBundle:BusinessPlan')->find($idBusinessPlan);

        //check permissions
        $this->denyAccessUnlessGranted(null, $bp);

        $produit = new Produit($bp);

        foreach ($bp->getExercices() as $exercice) {
            $produit->generateOneInfoProduct($exercice);
        }

        //Handle the changing of form mode (standard/advance). When the type has to be changed, 'isAdvanceMode' property is sent in the ajax request
        $options = [];
        if ($request->get('isAdvanceMode')) {
            $isAdvanceMode = $request->get('isAdvanceMode') === 'true';

            $options = ['attr' => ['isAdvanceMode' => $isAdvanceMode]];
        }
        $form = $this->createForm(ProduitType::class, $produit, $options);
        //END: form mode handling

        $form->handleRequest($request);

        return $this->render('BPBundle:Frontend:Income/edit.html.twig', [
                    'produit' => $produit,
                    'editForm' => $form->createView(),
                    'action' => 'create',
                    'idIncome' => 0,
                    'idBusinessPlan' => $produit->getBusinessPlan()->getId(),
                    'accountingNbPeriod' => $produit->getBusinessPlan()->getInformation()->getAccountingPeriod()
        ]);
    }

    public function getEditProductSeasonsFormAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository('BPBundle:Produit')->find($id);

        if ($produit) {
            //check permissions
            $this->denyAccessUnlessGranted(null, $produit->getBusinessPlan());

            if (!$produit->hasCustomProductSeasons()) {
                $produit->initProductSeason();
                $em->persist($produit);
                $em->flush();
            }

            $form = $this->createForm(ProductSeasonBPType::class, $produit);

            $form->handleRequest($request);

            return $this->render('BPBundle:Frontend:Income/edit_product_season.html.twig', [
                        'produit' => $produit,
                        'form' => $form->createView(),
                        'action' => 'update',
                        'idIncome' => $id
            ]);
        }
    }

    public function updateProductSeasonsAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository('BPBundle:Produit')->find($id);

        //check permissions
        $this->denyAccessUnlessGranted(null, $produit->getBusinessPlan());

        $form = $this->createForm(ProductSeasonBPType::class, $produit);

        $form->submit($request->request->get($form->getName()));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($produit);
            $em->flush();
            return new JsonResponse(array(
                'code' => 200,
                'message' => 'OK',
                'errors' => array('errors' => array(''))), 200);
        }

        return new JsonResponse(array(
            'code' => 400,
            'message' => 'Invalid Form',
            'errors' => $this->getErrorMessages($form)), 400);
    }

    public function removeProductSeasonsAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository('BPBundle:Produit')->find($id);

        //check permissions
        $this->denyAccessUnlessGranted(null, $produit->getBusinessPlan());

        $produit->removeCustomProductSeasons();

        $em->persist($produit);
        $em->flush();
        return new JsonResponse(array(
            'code' => 200,
            'message' => 'OK',
            'errors' => array('errors' => array(''))), 200);
    }

    public function getEditProductStockSeasonsFormAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository('BPBundle:Produit')->find($id);

        if ($produit) {
            //check permissions
            $this->denyAccessUnlessGranted(null, $produit->getBusinessPlan());

            if (!$produit->hasCustomProductStockSeasons()) {
                $produit->initProductStockSeason();
                $em->persist($produit);
                $em->flush();
            }

            $form = $this->createForm(ProductStockSeasonBPType::class, $produit);

            $form->handleRequest($request);

            return $this->render('BPBundle:Frontend:Income/edit_product_stock_season.html.twig', [
                        'produit' => $produit,
                        'form' => $form->createView(),
                        'action' => 'update',
                        'idIncome' => $id
            ]);
        }
    }

    public function updateProductStockSeasonsAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository('BPBundle:Produit')->find($id);

        //check permissions
        $this->denyAccessUnlessGranted(null, $produit->getBusinessPlan());

        $form = $this->createForm(ProductStockSeasonBPType::class, $produit);

        $form->submit($request->request->get($form->getName()));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($produit);
            $em->flush();
            return new JsonResponse(array(
                'code' => 200,
                'message' => 'OK',
                'errors' => array('errors' => array(''))), 200);
        }

        return new JsonResponse(array(
            'code' => 400,
            'message' => 'Invalid Form',
            'errors' => $this->getErrorMessages($form)), 400);
    }

    public function removeProductStockSeasonsAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository('BPBundle:Produit')->find($id);

        //check permissions
        $this->denyAccessUnlessGranted(null, $produit->getBusinessPlan());

        $produit->removeCustomProductStockSeasons();

        $em->persist($produit);
        $em->flush();
        return new JsonResponse(array(
            'code' => 200,
            'message' => 'OK',
            'errors' => array('errors' => array(''))), 200);
    }

// Generate an array contains a key -> value with the errors where the key is the name of the form field
    protected function getErrorMessages(\Symfony\Component\Form\Form $form) {
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }
        }

        return $errors;
    }

}
