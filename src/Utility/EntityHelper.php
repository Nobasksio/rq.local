<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 19/06/2019
 * Time: 09:39
 */

namespace App\Utility;


class EntityHelper
{

    public function getArrayParam($array = ['id','name']){

        $array_param = [];

        foreach ($array as $name_property){
            $name_getter = 'get'.ucfirst($name_property);
            if (isset($this->$name_getter)){

                $array_param[$name_property] = $this->$name_getter;
            }
        }
        return $array_param;


    }

}