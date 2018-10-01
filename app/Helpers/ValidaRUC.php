<?php
/**
 * Created by PhpStorm.
 * User: Halain
 * Date: 30/9/2018
 * Time: 10:35:24
 */

namespace App\Helpers\ValidaRUC;


class ValidaRUC
{

    public static function valida_ruc($ruc) {

        $errors['ruc'] = "OK";
//    if( !is_numeric($ruc))
        //        $errors['ruc'] = "El RUC/CI debe tener formato numérico";
        if (strlen($ruc) < 10 || strlen($ruc)=='999999999') {
            //convertir a consumidor final 999999999 si es menor que 10
            $errors['ruc'] = "CF";
        } elseif ( strlen($ruc) == 10 ) {
            $dig3 = $ruc[2];
            if ($dig3 == '7' || $dig3 == '8')
                $errors['ruc'] = "El formato es incorrecto";
            else {
                if ($dig3 < 6) {
                    $dos = substr($ruc, 0, 2);
                    if ($dos > 24 || $dos < 1)
                        $errors['ruc'] = "El formato es incorrecto";
                    elseif ($dig3 >= 6)
                        $errors['ruc'] = "El formato es incorrecto";
                    else {
                        $coef = "212121212";
                        $prim9 = substr($ruc, 0, 9);
                        $total = 0;
                        for ($i = 0; $i < 9; $i++) {
                            $res = $coef[$i] * $prim9[$i];
                            if ($res > 9) {
                                $res = $res % 10 + 1;
                            }
                            $total += $res;
                        }
                        $resto = $total % 10;
                        $dig = 10 - $resto;
                        if ($dig != $ruc[9])
                            $errors['ruc'] = "El formato es incorrecto";
                    }
                } else {
                    $dos = substr($ruc, 0, 2);
                    if ($dos > 22 || $dos < 1)
                        $errors['ruc'] = "El formato es incorrecto";
                    elseif (strlen($ruc) != 13)
                        $errors['ruc'] = "El formato es incorrecto";
                    else {
                        if ($dig3 == 9) {
                            $coef = "432765432";
                            $prim9 = substr($ruc, 0, 9);
                            $total = 0;
                            for ($i = 0; $i < 9; $i++) {
                                $res = $coef[$i] * $prim9[$i];
                                $total += $res;
                            }
                            $resto = $total % 11;
                            $dig = 11 - $resto;
                            if ($dig != $ruc[9])
                                $errors['ruc'] = "El formato es incorrecto";
                        } else {
                            $coef = "32765432";
                            $prim8 = substr($ruc, 0, 8);
                            $total = 0;
                            for ($i = 0; $i < 8; $i++) {
                                $res = $coef[$i] * $prim8[$i];
                                $total += $res;
                            }
                            $resto = $total % 11;
                            $dig = 11 - $resto;
                            if ($dig != $ruc[8])
                                $errors['ruc'] = "El formato es incorrecto";
                        }
                    }
                }

            }
        }


//    echo $errors['ruc'];
        return $errors['ruc'];

    }

}