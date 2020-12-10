<?php

namespace BackBundle\Controller;

use BackBundle\Entity\JoorneyOrder;
use BackBundle\Form\JoorneyOrderType;
use PromotionBundle\Entity\Catalog;
use Swift_Attachment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Page controller.
 *
 */
class JoorneyOrderController extends Controller {

    public function orderBPInternalServiceAction(Request $request, $idCatalog, $idBusinessPlan) {
        $em = $this->getDoctrine()->getManager();

        $bp = $idBusinessPlan ? $em->getRepository('BPBundle:BusinessPlan')->find($idBusinessPlan) : null;

        $catalog = $em->getRepository('PromotionBundle:Catalog')->find($idCatalog);

        //create order
        $order = $bp->getCurrentInternalOrder($idCatalog);
        if (!$order) {
            $order = new JoorneyOrder();
            $order->setBusinessPlan($bp);
            $order->setCatalog($catalog);
            $order->setUser($this->getUser());
            $order->setStatut(JoorneyOrder::ORDER_STATUT_1_PAYMENT_AWAITED);
        }

        // GESTION DU FORMULAIRE
        $form = $this->createForm(JoorneyOrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($order);
            $em->flush();

            
            $service = $bp->getService();
            $nbAdviceHourAvailable = $service->getAdviceHour();
            $nbWordingAvailable = $service->getNbWording();

            // If Formula is Essentiel, popup to ask to change formula
            if ($catalog->getId() == Catalog::CATALOG_1H_EXPERT_FORMULA_ID) {
                //check if trial is active
                if ($bp->isTrial() && $bp->getCatalog()->getNbAdviceHour()){
                    $popup = 'ask-for-formula-end-trial-acc';
                    return $this->render('BackBundle:JoorneyOrder:front/bp_internal_service.html.twig', [
                        'form' => $form->createView(),
                        'orderId' => $catalog->getId(),
                        'bp' => $bp,
                        'popup' => $popup,
                        'catalog' => $em->getRepository('PromotionBundle:Catalog')->find(Catalog::CATALOG_FORMULE_PRO),
                    ]);
                } else 
                if ($bp->isTrial()){
                    return $this->render('BackBundle:JoorneyOrder:front/bp_internal_service.html.twig', [
                        'form' => $form->createView(),
                        'orderId' => $catalog->getId(),
                        'bp' => $bp,
                        'popup' => 'ask-for-formula-change-acc',
                        'catalog' =>  $em->getRepository('PromotionBundle:Catalog')->find(Catalog::CATALOG_FORMULE_PRO),
                    ]);
                } else
                if (!$bp->getCatalog()->getNbAdviceHour()) {
                    $nextFormulaCatalog = $em->getRepository('PromotionBundle:Catalog')->getProUpgrade($bp->getCatalog());
                    return $this->render('BackBundle:JoorneyOrder:front/bp_internal_service.html.twig', [
                        'form' => $form->createView(),
                        'orderId' => $catalog->getId(),
                        'bp' => $bp,
                        'popup' => 'ask-for-formula-change-acc',
                        'catalog' => $nextFormulaCatalog,
                    ]);
                } else               
                // Check is user has enough available advice hour
                if ($nbAdviceHourAvailable <= 0) {
                    return $this->render('BackBundle:JoorneyOrder:front/bp_internal_service.html.twig', [
                        'form' => $form->createView(),
                        'orderId' => $catalog->getId(),
                        'bp' => $bp,
                        'popup' => 'ask-for-more-hour',
                        'catalog' => $catalog,
                    ]);
                }
            } else if ($catalog->getId() == Catalog::CATALOG_PREZ_PROJECT_FORMULA_ID) {
                //check if trial is active
                if ($bp->isTrial() && $bp->getCatalog()->getHasWording()){
                    $popup = 'ask-for-formula-end-trial-wording';
                    return $this->render('BackBundle:JoorneyOrder:front/bp_internal_service.html.twig', [
                        'form' => $form->createView(),
                        'orderId' => $catalog->getId(),
                        'bp' => $bp,
                        'popup' => $popup,
                        'catalog' => $em->getRepository('PromotionBundle:Catalog')->find(Catalog::CATALOG_FORMULE_PREMIUM),
                    ]);
                } else 
                if ($bp->isTrial()) {
                    return $this->render('BackBundle:JoorneyOrder:front/bp_internal_service.html.twig', [
                        'form' => $form->createView(),
                        'orderId' => $catalog->getId(),
                        'bp' => $bp,
                        'popup' => 'ask-for-formula-change-wording',
                        'catalog' => $em->getRepository('PromotionBundle:Catalog')->find(Catalog::CATALOG_FORMULE_PREMIUM),
                    ]);
                } else  
                //Check if actual formule include prez
                if (!$bp->getCatalog()->getHasWording()) {
                    $formulaCatalog = $em->getRepository('PromotionBundle:Catalog')->getPremiumUpgrade($bp->getCatalog());
                    return $this->render('BackBundle:JoorneyOrder:front/bp_internal_service.html.twig', [
                        'form' => $form->createView(),
                        'orderId' => $catalog->getId(),
                        'bp' => $bp,
                        'popup' => 'ask-for-formula-change-wording',
                        'catalog' => $formulaCatalog,
                    ]);
                } else  
                // Check is user has enough wording available
                if ($nbWordingAvailable <= 0) {
                    return $this->render('BackBundle:JoorneyOrder:front/bp_internal_service.html.twig', [
                        'form' => $form->createView(),
                        'orderId' => $catalog->getId(),
                        'bp' => $bp,
                        'popup' => 'ask-for-more-wording',
                        'catalog' => $catalog,
                    ]);
                }
            }

            // If Everthing is OK, we validate the order
            $order->setStatut(JoorneyOrder::ORDER_STATUT_4_ORDER_VALIDATED);
            $urlRedirect = $this->generateUrl('user_my_projects');
            if ($catalog->getId() == Catalog::CATALOG_1H_EXPERT_FORMULA_ID) {
                $service->setAdviceHour($nbAdviceHourAvailable - 1);
                $urlRedirect = $this->generateUrl('user_my_projects', ['orderConfirm' => 'request-confirmed-acc']);
            } elseif($catalog->getId() == Catalog::CATALOG_PREZ_PROJECT_FORMULA_ID) {
                $service->setNbWording($nbWordingAvailable - 1);
                $urlRedirect = $this->generateUrl('user_my_projects', ['orderConfirm' => 'request-confirmed-presentation']);
            }

            $em->persist($service);

            $em->persist($order);
            $em->flush();
            $this->sendNotificationToJoorney($order);

            return $this->redirect($urlRedirect);
        }

        return $this->render('BackBundle:JoorneyOrder:front/bp_internal_service.html.twig', [
            'form' => $form->createView(),
            'orderId' => $catalog->getId(),
            'bp' => $bp,
            'popup' => null,
            'catalog' => $catalog,
        ]);
    }

    private function sendNotificationToJoorney($order) {
        $message = (new \Swift_Message('Planoo.ai - ' . $order->getCatalog()->getName() . '- [' . $order->getHash() . ']'))
            ->setFrom('noreply@' . $this->getParameter('mailer_domain'), 'Planoo.ai')
            ->setTo('contact@joorneybp.fr')
            ->setBody(
                $this->renderView(
                    'BackBundle:JoorneyOrder:back/joorney_order_notification_email.html.twig', [
                        'order' => $order,
                    ]
                ), 'text/html'
            );

        if ($order->getFile()) {
            $message->attach(Swift_Attachment::fromPath($order->getFilename()));
        }

        $this->get('mailer')->send($message);
    }

}
