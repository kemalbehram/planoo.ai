<?php

namespace PaymentBundle\Controller;

use PaymentBundle\Form\RequestInvoiceType;
use PaymentBundle\Entity\RequestInvoice;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Page controller.
 *
 */
class RequestInvoiceController extends Controller {

    public function requestInvoiceAction(Request $request, $idPayment) {
        $em = $this->getDoctrine()->getManager();

        $payment = $em->getRepository('PaymentBundle:Payment')->find($idPayment);

        $requestInvoice = new RequestInvoice();

        $form = $this->createForm(RequestInvoiceType::class, $requestInvoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->sendNotificationToJoorney($requestInvoice, $payment);
            $payment->setInvoiceSent(true);
            $em->persist($payment);
            $em->flush();
            return $this->redirectToRoute('payment_index', array('requestSent' => true));
        }

        return $this->render('PaymentBundle:Invoices:request.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function sendNotificationToJoorney($requestInvoice, $payment) {
        $message = (new \Swift_Message('Planoo.ai - Demande de facture - ' . $requestInvoice->getCompany() . '- [paiement' . $payment->getId() . ']'))
            ->setFrom('noreply@' . $this->getParameter('mailer_domain'), 'Planoo.ai')
            ->setTo('contact@joorneybp.fr')
            ->setBody(
                $this->renderView(
                    'PaymentBundle:Invoices:notification.html.twig', [
                        'requestInvoice' => $requestInvoice,
                        'payment' => $payment,
                    ]
                ), 'text/html'
            );

        $this->get('mailer')->send($message);
    }

}
