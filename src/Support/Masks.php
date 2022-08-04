<?php

namespace Devesharp\Support;

class Masks {
    static function mask($val, $mask): string {
        $maskared = '';
        $k = 0;
        for($i = 0; $i<=strlen($mask)-1; $i++) {
            if($mask[$i] == '#') {
                if(isset($val[$k])) $maskared .= $val[$k++];
            } else {
                if(isset($mask[$i])) $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }
    static function onlyNumbers($string): string {
        return preg_replace('/[^0-9]/', '', $string);
    }

    static function CEP($string): string {
        $string = Masks::onlyNumbers((string) $string);

        return Masks::mask($string, "#####-###");
    }

    static function CNPJ($string): string {
        $string = Masks::onlyNumbers((string) $string);

        return Masks::mask($string, "##.###.###/####-##");
    }

    static function CPF($string): string {
        $string = Masks::onlyNumbers((string) $string);

        return Masks::mask($string, "###.###.###-##");
    }

    static function CNPJAndCPF($string): string {
        $string = Masks::onlyNumbers((string) $string);

        if (strlen($string) <= 11) {
            return Masks::CPF($string);
        }

        return Masks::CNPJ($string);
    }

    static function RG($string): string {
        $string = preg_replace('/[^0-9A-Za-z]/', '', $string);

        return Masks::mask($string, "##.###.###-##");
    }

    static function PhoneMask($string): string {
        $string = Masks::onlyNumbers((string) $string);

        if(strlen($string) <= 10) {
            return Masks::mask($string, "(##) ####-####");
        }

        return Masks::mask($string, "(##) #####-####");
    }

    static function NoSpaces($string): string {
        return str_replace(' ', '', $string);
    }

    static function DateMask($string): string {
        $string = Masks::onlyNumbers((string) $string);

        return Masks::mask($string, "##/##/####");
    }

}
