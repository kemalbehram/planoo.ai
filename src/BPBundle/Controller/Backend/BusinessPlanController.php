<?php
/**
 * Created by PhpStorm.
 * User: wac28
 * Date: 11/10/16
 * Time: 14:30
 */

namespace BPBundle\Controller\Backend;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class BusinessPlanController extends Controller
{
    /**
     * Lists all Catalog entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $catalogs = $em->getRepository('PromotionBundle:Catalog')->findAll();

        return $this->render('BPBundle:Catalog:index.html.twig', array(
            'catalogs' => $catalogs,
        ));
    }
}