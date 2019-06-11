<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 12/04/2019
 * Time: 11:00
 */

namespace App\Controller;


use App\Entity\Analitics;
use App\Entity\OldCategory;
use App\Entity\OldProduct;
use App\Repository\OldCategoryRepository;
use App\Repository\OldProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UtilController extends AbstractController
{
    public function __construct() {

    }

    public function saveProducts($file_contents,
                                 Analitics $analitics){
        $f_array_str = explode("\r", $file_contents);

        //delete last string if it empty
        $last_key=count($f_array_str)-1;
        if ($f_array_str[$last_key]==''){
            unset($f_array_str[$last_key]);
        }

        //get one product from file
        foreach ($f_array_str as $key=>$value){

            if ($key==0) continue;

            $array_value[$key] = explode(";", $f_array_str[$key]);
            $group=$array_value[$key][0];
            $array_for_group[$group][]=$array_value[$key];

        }
        $doctrin = $this->getDoctrine();
        //перебираем весь массив чтобы сохранить
        foreach ($array_for_group as $cat_name=>$cat_arr){

            $old_category = new OldCategory();
            $old_category->setName($cat_name);
            $old_category->setAnalitics($analitics);

            $entityManager = $doctrin->getManager();
            $entityManager->persist($old_category);
            $entityManager->flush();


            foreach ($cat_arr as $key_tov=>$tov_arr){

                $name_tov=$tov_arr[1];
                $sale = (float)str_replace(' ', '', $tov_arr[2]);
                $price = (float)$tov_arr[3];
                $cost_price = (float)$tov_arr[4];


                $old_product = new OldProduct();

                $old_product->setName($name_tov);
                $old_product->setCategory($old_category);
                $old_product->setcostPrice($cost_price);
                $old_product->setSale($sale);

                $entityManager->persist($old_product);
                $entityManager->flush();


            }
        }
    }

}