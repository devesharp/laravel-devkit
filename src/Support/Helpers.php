<?php

namespace Devesharp\Support;

class Helpers {

    /**
     * Remove espaços duplos, caracteres especiais e acentos e deixa minusculo de string
     *
     * @param $string
     * @return string|string[]|null
     */
    static public function searchableString($string)
    {
        $string = trim(Helpers::normalizeString($string));
        $string = preg_replace('/\s+/', ' ', $string);

        return $string;
    }

    /**
     * Verificar se algum dos itens existe na array
     *
     * @param $needles
     * @param $haystack
     * @return bool
     */
    static public function inArrayAny($needles, $haystack) {
        return !empty(array_intersect($needles, $haystack));
    }


    /**
     * Normaliza string
     *
     * @param $str
     * @return string
     */
    static public function normalizeString($str){
        $str = Helpers::removeAccents($str);
        $str = str_replace("-", " ", $str);
        $str = preg_replace('/[^a-zA-Z0-9 ]+/', '', $str);
        $str = trim($str);
        $str = strtolower($str);

        return $str;
    }

    /**
     * Remove todos os acentos da string
     *
     * @param $str
     * @return string
     */
    static public function removeAccents($str){
        if(empty($str)) return $str;
        return strtr(utf8_decode($str), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
    }

    /**
     * Remover valores null de array
     *
     * @param array $array
     * @return array
     */
    static public function arrayFilterNull(array $array)
    {
        foreach ($array as &$value) {
            if (is_array($value)) {
                if (\Devesharp\Support\Helpers::isArrayAssoc($value)) {
                    $value = Helpers::arrayFilterNull($value);
                } else {
                    $value = array_values(Helpers::arrayFilterNull($value));
                }
            }
        }

        return array_filter($array, fn ($value) => null !== $value);
    }

    /**
     * Remove todas as keys que não estiverm em $keys
     *
     * @param $array
     * @param $keys
     * @return array|Collection
     */
    public static function arrayOnly($array, $keys)
    {
        // Converte string em array
        if (is_string($keys)) {
            $keys = explode(',', $keys);
        }

        // Verifica se é Collection ou Array
        $isCollection = false;
        if ($array instanceof \Devesharp\Support\Collection) {
            $isCollection = true;
            $array = $array->toArray();
        }

        $arrayDot = \Illuminate\Support\Arr::dot($array);

        $arrayOnly = [];
        foreach ($arrayDot as $key => $value) {
            // Remove valores numéricos das arrays array.0.name
            $keyD = preg_replace('/\.[0-9]+\./', '.', $key);
            $keyD = preg_replace('/\.[0-9]+$/', '', $keyD);
            $keyD = preg_replace('/^[0-9]+\./', '', $keyD);

            // Deixar apenas as keys foram passada
            foreach ($keys as $_key) {
                $_key = str_replace('.*', '', $_key);

                if (
                    $_key === $keyD ||
                    preg_match('/' . $_key . '\.(.*)/', $keyD)
                ) {
                    $arrayOnly[$key] = $arrayDot[$key];
                }
            }
        }

        // Converte array novamente a original
        $newArray = [];
        foreach ($arrayOnly as $key => $value) {
            \Illuminate\Support\Arr::set($newArray, $key, $value);
        }

        return $isCollection
            ? new \Devesharp\Support\Collection($newArray)
            : $newArray;
    }

    /**
     * Remove todas as keys da array que estiverem em $keys
     *
     * @param $array
     * @param $keys
     * @return array|Collection
     */
    public static function arrayExclude($array, $keys)
    {
        // Converte string em array
        if (is_string($keys)) {
            $keys = explode(',', $keys);
        }

        // Verifica se é Collection ou Array
        $isCollection = false;
        if ($array instanceof \Devesharp\Support\Collection) {
            $isCollection = true;
            $array = $array->toArray();
        }

        $arrayDot = \Illuminate\Support\Arr::dot($array);

        foreach ($arrayDot as $key => $value) {
            // Remove valores numéricos das arrays array.0.name
            $keyD = preg_replace('/\.[0-9]+\./', '.', $key);
            $keyD = preg_replace('/^[0-9]+\./', '', $keyD);

            // Remove keys foram passada
            foreach ($keys as $_key) {
                $_key = str_replace('.*', '', $_key);

                if (
                    $_key === $keyD ||
                    preg_match('/' . $_key . '\.(.*)/', $keyD)
                ) {
                    unset($arrayDot[$key]);
                }
            }
        }

        // Converte array novamente a original
        $newArray = [];
        foreach ($arrayDot as $key => $value) {
            \Illuminate\Support\Arr::set($newArray, $key, $value);
        }

        return $isCollection
            ? new \Devesharp\Support\Collection($newArray)
            : $newArray;
    }

    /**
     * Verifica se array é associativa ou sequencial
     *
     * @param $arr
     * @return bool
     */
    public static function isArrayAssoc($arr)
    {
        if (! is_array($arr)) {
            return false;
        }

        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * Converte object apra array
     *
     * @param $object
     * @return mixed
     */
    public static function objectToArray($object)
    {
        return json_decode(json_encode($object), true);
    }

    /**
     * Remove espaços duplos
     * @param $string
     * @return string
     */
    public static function trim_spaces($string)
    {
        $string = preg_replace('/\s+/', ' ', $string);

        return trim($string);
    }

    /**
     * Verifica se array é uma array de strings
     *
     * @param $value
     * @return bool
     */
    public static function isArrayString($value)
    {
        if (! is_array($value)) {
            return false;
        }

        foreach ($value as $v) {
            if (! is_string($v)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Verifica se array é uma array de numeros
     *
     * @param $value
     * @return bool
     */
    public static function isArrayNumber($value)
    {
        if (! is_array($value)) {
            return false;
        }

        foreach ($value as $v) {
            if (! is_int($v) && ! is_float($v) && ! is_double($v)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Gerar uma quantidade $size de letras randomicas
     *
     * @param  int    $size
     * @return string
     */
    public static function randomLetters(int $size)
    {
        $seed = str_split(
            'abcdefghijklmnopqrstuvwxyz' . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
        ); // and any other characters
        shuffle($seed); // probably optional since array_is randomized; this may be redundant
        $rand = '';
        foreach (array_rand($seed, $size) as $k) {
            $rand .= $seed[$k];
        }

        return $rand;
    }

    /**
     * Remove qualquer caracter que não seja um numero
     *
     * @param $string
     * @return string|string[]|null
     */
    public static function onlyNumbers($string)
    {
        return preg_replace('/[^0-9]/', '', $string);
    }

    /**
     * Converte string para url
     * @param $str
     * @return string|string[]
     */
    public static function convertUrl($str) {
        $str = Helpers::normalizeString($str);

        $str = str_replace(" ", "-", $str);

        return $str;
    }
}

//if (! function_exists('validator')) {
//    /**
//     * Gets the value of an validatorironment variable.
//     *
//     * @param string $key
//     * @param mixed  $default
//     * @param array  $data
//     * @param array  $rules
//     *
//     * @return Illuminate\Validation\Validator
//     */
//    function validator(array $data, array $rules)
//    {
//        $validator = (new \Devesharp\Support\Validator())->make($data, $rules);
//
//        $validator->addExtension('numeric_array', function (
//            $attribute,
//            $value,
//            $parameters
//        ) {
//            if (! is_array($value)) {
//                return false;
//            }
//            foreach ($value as $v) {
//                if (! is_int($v)) {
//                    return false;
//                }
//            }
//
//            return true;
//        });
//
//        return $validator;
//    }
//}
