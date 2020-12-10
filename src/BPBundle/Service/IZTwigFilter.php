<?php

namespace BPBundle\Service;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class IZTwigFilter extends AbstractExtension {

    public function getFilters() {
        return array(
            new TwigFilter('formatNumber', array($this, 'formatNumber'), array('needs_context' => true)),
        );
    }

    public function getFunctions() {
        return array(
            new TwigFunction('calculDureeAmortissement', array($this, 'calculDureeAmortissement')),
        );
    }

    public function formatNumber($context, $number, $unit = '', $digits = 0, $preview=false, $request=null) {
        $locale = 'fr';
        
        if($request === null && $context !=null){
            $request = $context['app']->getRequest();
            $locale = $request->getLocale();
        } 

        if($preview){
            $format_number = 'essai gratuit';
        } else {
        $roundedNumber = round ($number , $digits);
                
        if ($roundedNumber == 0) {
            $format_number = '-';
        } else {
            if ($locale == 'fr') {
                $format_number = number_format($roundedNumber, $digits, ',', ' ');
            } elseif ($locale == 'en') {
                $format_number = number_format($roundedNumber, $digits, '.', '\'');
            }
            if (strpos($format_number, '-') !== false) {
                $format_number = str_replace('-', '(', $format_number);
                $format_number = $format_number .$unit. ')';
            } else {
                $format_number = $format_number .$unit;
            }
        }
    }

        return $format_number;
    }

    public function calculDureeAmortissement($investissement, $codeCountry) {
        $duree = 0;
        foreach ($investissement->getChargeLabel()->getAmortissements() as $amortissement) {
            if ($amortissement->getCountry()->getId() == $codeCountry) {
                $duree = $amortissement->getDuree();
                break;
            }
        }

        return $duree;
    }

    public function getName() {
        return 'TwigFilter';
    }

}
