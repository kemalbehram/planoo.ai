<?php
/**
 * Created by PhpStorm.
 * User: wac28
 * Date: 23/01/17
 * Time: 16:18
 */

namespace BPBundle\Controller\Backend;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function staffAction()
    {
        return $this->render('BPBundle:Backend:Staff/index.html.twig');
    }
}