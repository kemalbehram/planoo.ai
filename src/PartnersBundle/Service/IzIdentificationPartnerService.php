<?php

namespace PartnersBundle\Service;

use Symfony\Component\Routing\RequestContext;
use Symfony\Component\HttpFoundation\RequestStack;

class IzIdentificationPartnerService {

    private $partner;
    private $defaultPartner;
    private $logoPartnerBase64;
    private $defaultLogoBase64;
    private $favicon16PartnerBase64;
    private $favicon32PartnerBase64;
    private $defaultFavicon16Base64;
    private $defaultFavicon32Base64;
    private $cobrandingLogoBase64;
    private $em;
    private $white_mark;

    public function __construct(RequestContext $requestContext,RequestStack $requestStack, $em, $white_mark) {
        $this->white_mark = $white_mark;
        $this->em = $em;

        $domain = $requestContext->getHost();

        $this->partner = $this->em->getRepository('PartnersBundle:Partner')->findOneBy(['customDomain' => $domain]);
        if(!$this->partner){
            $cookies = $requestStack->getCurrentRequest() ? $requestStack->getCurrentRequest()->cookies : null;

            if ($cookies && $cookies->has('wpam_id'))
            {
                $idWordpressAffiliate = $cookies->get('wpam_id');
                $this->partner = $em->getRepository('PartnersBundle:Partner')->findOneBy(['idWordpressAffiliate' => $idWordpressAffiliate]);
            }
        }

        if ($this->partner != null && $this->partner->getId() != 1 && $this->partner->getLogo() != null) {
            $this->logoPartnerBase64 = base64_encode(stream_get_contents($this->partner->getLogo()));
        }

        if ($this->partner != null && $this->partner->getId() != 1 && $this->partner->getCobrandingLogo() != null) {
            $this->cobrandingLogoBase64 = base64_encode(stream_get_contents($this->partner->getCobrandingLogo()));
        }

        if ($this->partner != null && $this->partner->getId() != 1 && $this->partner->getFavicon16() != null) {
            $this->favicon16PartnerBase64 = base64_encode(stream_get_contents($this->partner->getFavicon16()));
        }

        if ($this->partner != null && $this->partner->getId() != 1 && $this->partner->getFavicon32() != null) {
            $this->favicon32PartnerBase64 = base64_encode(stream_get_contents($this->partner->getFavicon32()));
        }

        $this->defaultPartner = $this->em->getRepository('PartnersBundle:Partner')->find(1);
        $this->defaultLogoBase64 = base64_encode(stream_get_contents($this->defaultPartner->getLogo()));
        $this->defaultFavicon16Base64 = base64_encode(stream_get_contents($this->defaultPartner->getFavicon16()));
        $this->defaultFavicon32Base64 = base64_encode(stream_get_contents($this->defaultPartner->getFavicon32()));
    }

    public function getCurrentPartner() {
        if ($this->partner == null) {
            return $this->defaultPartner;
        } else {
            return $this->partner;
        }
    }

    public function getPartnerColorCssPath() {
        if ($this->partner == null || count(glob('build/theme-' . $this->partner->getNom() . '*.css')) == 0 ) {
            return 'theme-' . $this->defaultPartner->getNom();
        } else {
            return 'theme-' . $this->partner->getNom();
            // return 'public/Frontend/css/themes/' . $this->partner->getNom() . '.css';
        }
    }

    public function getPartnerPdfColorCssPath() {
        if (!$this->white_mark || $this->partner == null || !file_exists('public/PDF/css/themes/' . $this->partner->getNom() . '.css')) {
            return 'theme-pdf-' . $this->defaultPartner->getNom();
        } else {
            return 'theme-pdf-' . $this->partner->getNom();
        }
    }

    public function getPartnerLogoBase64() {
        return $this->logoPartnerBase64;
    }

    public function getCobrandingLogoBase64() {
        if ($this->partner != null && $this->cobrandingLogoBase64 != null) {
            return $this->cobrandingLogoBase64;
        } else {
            return $this->defaultLogoBase64;
        }
    }

    public function getFavicon16Base64() {
        if ($this->partner != null && $this->favicon16PartnerBase64 != null) {
            return $this->favicon16PartnerBase64;
        } else {
            return $this->defaultFavicon16Base64;
        }
    }

    public function getFavicon32Base64() {
        if ($this->partner != null && $this->favicon32PartnerBase64 != null) {
            return $this->favicon32PartnerBase64;
        } else {
            return $this->defaultFavicon32Base64;
        }
    }

    public function getDefaultPartner() {
        return $this->defaultPartner;
    }

}
