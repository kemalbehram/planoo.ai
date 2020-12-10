<?php

namespace UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use DateTime;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class RegistrationInitializeListener implements EventSubscriberInterface {

    private $router;
    private $identificationPartnerService;
    private $em;
    private $logger;
    private $hubspotPrivateKey;

    public function __construct(UrlGeneratorInterface $router, $identificationPartnerService,EntityManagerInterface $em, LoggerInterface $logger, $hubspotPrivateKey) {
        $this->router = $router;
        $this->identificationPartnerService = $identificationPartnerService;
        $this->em = $em;
        $this->logger = $logger;
        $this->hubspotPrivateKey = $hubspotPrivateKey;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents() {
        return array(
            FOSUserEvents::REGISTRATION_INITIALIZE => 'onRegistrationInitialize',
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
        );
    }

    public function onRegistrationInitialize(GetResponseUserEvent $event) {
        $event->getUser()->setPartner($this->identificationPartnerService->getCurrentPartner());
        
        //set formule name
        $catalogId = $event->getRequest()->get('p');
        if ($catalogId){
            $catalog = $this->em->getRepository('PromotionBundle:Catalog')->find($catalogId);
            $event->getRequest()->attributes->set('formuleName',$catalog->getName());
        }
    }

    public function onRegistrationSuccess(FormEvent $event) {
        $nlAccepted = $event->getRequest()->get('newsletterAccepted');
        $cAccepted = $event->getRequest()->get('commercialAccepted');
        if ($nlAccepted && $nlAccepted == 'on') {
            $event->getForm()->getData()->setNewsletterAcceptedAt(new DateTime());
        }

        if ($cAccepted && $cAccepted == 'on') {
            $event->getForm()->getData()->setCommercialAcceptedAt(new DateTime());
        }

        $event->getForm()->getData()->setPrivacyPolicyAcceptedAt(new DateTime());

        $productId = $event->getRequest()->get('p');
        if($productId){
            $url = $this->router->generate('bp_init',['idCatalog' => $productId]);
            $response = new RedirectResponse($url);
            $event->setResponse($response);
        } else {
            $response = new RedirectResponse($this->router->generate('user_my_projects'));
        }

        //send user to hubspot
        
        $hubspot = \SevenShores\Hubspot\Factory::create($this->hubspotPrivateKey);

        $user=$event->getForm()->getData();

        $properties = [];
        $properties = [
                [
                    'property' => 'hs_lead_status',
                    'value' => 'NEW'
                ],
                [
                    'property' => 'planoo_contacts',
                    'value' => 'true'
                ]
        ];

        try {
            $contact = $hubspot->contacts()->createOrUpdate($user->getEmail(),$properties);
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
        $event->setResponse($response);
    }

}
