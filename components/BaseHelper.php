<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace jonmer09\report\components;

/**
 * Description of BaseHelper
 *
 * @author jcarpio
 */
class BaseHelper
{

    public static function getRequest()
    {
        return \Yii::$app->getRequest();
    }

    public static function get($id = null, $defaultValue = null)
    {
        return self::getRequest()->get($id, $defaultValue);
    }

    public static function post($id = null, $defaultValue = null)
    {
        return self::getRequest()->post($id, $defaultValue);
    }

    public static function moveElement(&$array, $a, $b)
    {
        $out = array_splice($array, $a, 1);
        array_splice($array, $b, 0, $out);
    }

    public static function moveElements(&$array, $elems = [])
    {
        $_t = [];
        foreach ($elems as $v)
        {
            $_t[] = $array[$v];
        }
        $array = array_diff_key($array, array_flip($elems));
        array_splice($array, 0, 0, $_t);
        return $_t;
    }

}
