<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 24/06/2019
 * Time: 10:32
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{

    public function make_array($entities_array){

        $return_array = [];
        foreach ($entities_array as $entity_item) {
            $return_array[] = $entity_item->getArrayParam();
        }

        return $return_array;
    }
}