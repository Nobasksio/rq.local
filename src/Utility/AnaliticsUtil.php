<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 16/04/2019
 * Time: 21:39
 */

namespace App\Utility;



class AnaliticsUtil
{
    public static function countSummary($categories)
    {
        $all_vir = 0;
        foreach ($categories as $category) {

            $old_products = $category->getOldProducts();

            $val_vir = 0;
            $val_sale = 0;
            $val_marj = 0;

            foreach ($old_products as $product) {

                $price = $product->getPrice();
                $sale = $product->getSale();
                $cost_price = $product->getCostPrice();

                $vir = $price * $sale;

                $val_vir += $vir;
                $val_sale += $sale;
                $val_marj += $price * $sale - $cost_price * $sale;
                $val_ss = $val_vir - $val_marj;

            }
            $all_vir += $val_vir;
            $category->setValMarj($val_marj);
            $category->setValSs($val_ss);
            $category->setValVir($val_vir);
            $category->setValSale($val_sale);

        }
        foreach ($categories as $category) {
            $val_vir = $category->getValVir($val_vir);
            $category->setPerVir($val_vir/($all_vir/100));
        }


    }
    public static function setAbc($old_products)
    {
        $val_vir = 0;
        $val_sale = 0;
        $val_marj = 0;
        $marj_ed = array();

        function mySortSale($f1, $f2)
        {
            if ($f1->getSale() < $f2->getSale()) return 1;
            elseif ($f1->getSale() > $f2->getSale()) return -1;
            else return 0;
        }

        function mySortMarj($f1, $f2)
        {
            if ($f1->getMarj() < $f2->getMarj()) return 1;
            elseif ($f1->getMarj() > $f2->getMarj()) return -1;
            else return 0;
        }

        function mySortVir($f1, $f2)
        {
            if ($f1->getVir() < $f2->getVir()) return 1;
            elseif ($f1->getVir() > $f2->getVir()) return -1;
            else return 0;
        }

        foreach ($old_products as $product) {

            $price = $product->getPrice();
            $sale = $product->getSale();
            $cost_price = $product->getCostPrice();

            $vir = $price * $sale;

            $val_vir += $vir;
            $val_sale += $sale;
            $val_marj += $price * $sale - $cost_price * $sale;
            $val_ss = $val_vir - $val_marj;
            $marj_ed[] = $price - $cost_price;

            $product->setVir($price * $sale);
            $product->setMarj($price * $sale - $cost_price * $sale);

            if ($vir != 0) {
                $product->setPerCostPrice($val_ss / $vir);
            } else {
                $product->setPerCostPrice(0);
            }

        }


        usort($old_products, 'App\Utility\mySortSale');
        $old_products = self::countAbc($old_products, $val_sale, 'Sale');

        usort($old_products, 'App\Utility\mySortVir');
        $old_products = self::countAbc($old_products, $val_vir, 'Vir');

        usort($old_products, 'App\Utility\mySortMarj');
        $old_products = self::countAbc($old_products, $val_marj, 'Marj');

        $mean_ss = ($val_vir - $val_marj) / $val_vir;
        return array('val_vir' => $val_vir,
            'val_sale' => $val_sale,
            'val_marj' => $val_marj,
            'val_ss' => $val_ss,
            'sr_ss' => round($mean_ss, 2),
            'sr_marj' => $val_marj / count($marj_ed),
            'mean_price' => ($val_vir) / $val_sale,
            'sr_marj_ed' => array_sum($marj_ed) / count($marj_ed));

    }
    public static function countAbc($old_products, $all_sum, $name_param)
    {
        $sum_abc = 0;

        $get_name = 'get' . $name_param;
        $set_name = 'setAbc' . $name_param;

        foreach ($old_products as $product) {

            if ($product->getDisregard()) {
                $product->$set_name('');
            } else {
                $sale = $product->$get_name();
                $sum_abc += $sale / ($all_sum / 100);
                if ($sum_abc < 80) $product->$set_name('A');
                else if ($sum_abc < 95) $product->$set_name('B');
                else $product->$set_name('C');

            }

        }

        return $old_products;

    }
    public static function countPriceCat($old_products, $mean_price)
    {

        $arraySums['min_sr_price'] = $mean_price * 0.8;
        $arraySums['max_sr_price'] = $mean_price * 1.2;
        $arraySums['min_price'] = $mean_price * 0.5;
        $arraySums['max_price'] = $mean_price * 1.5;

        foreach ($old_products as $id_tov => $product) {


            if ($product->getPrice() < $arraySums['min_price']) {
                $kateg_price = 'Поднемите цену';
            } else if ($product->getPrice() > $arraySums['min_price'] and $product->getPrice() < $arraySums['min_sr_price']) {
                $kateg_price = 'Дешевая';
            } else if ($product->getPrice() > $arraySums['min_sr_price'] and $product->getPrice() < $arraySums['max_sr_price']) {
                $kateg_price = 'Средняя';
            } else if ($product->getPrice() > $arraySums['max_sr_price'] and $product->getPrice() < $arraySums['max_price']) {
                $kateg_price = 'Высока';
            } else {
                $kateg_price = 'Слишком дорого';
            }
            $product->setProductCat($kateg_price);

        }
    }
    public static function setAbcFirstOtch($old_products, $arraySums)
    {

        $oth1 = "";
        $oth1_sh = '';
        foreach ($old_products as $tov_id => $produst) {


            if ($produst->getPerCostPrice() >= $arraySums['sr_ss']) {

                if ($produst->getPerCostPrice() > $arraySums['sr_ss'] * 1.15) {
                    if ($produst->getMarj() > $arraySums['sr_marj']) {

                        if ($produst->getMarj() > $arraySums['sr_marj'] * 1.15) {
                            $oth1 = 'Стандарт (ок)';
                            $oth1_sh = 'Поднять цену или снизить сс.';
                        } else {
                            $oth1 = 'Стандарт (или проблема)*';
                            $oth1_sh = 'Поднять цену или снизить сс.';

                        }
                    } else {
                        if ($produst->getMarj() < $arraySums['sr_marj'] * 0.85) {
                            $oth1 = 'Проблема (нет шанса)';
                            $oth1_sh = 'Лучше убрать';
                        } else {
                            $oth1 = 'Проблема (есть шанс)*';
                            $oth1_sh = 'Лучше убрать';


                        }
                    }
                } else {
                    $oth1 = 'Колеблющийся';
                    $oth1_sh = 'Смотри другие модели';
                }
            } else if ($produst->getPerCostPrice() < $arraySums['sr_marj']) {
                if ($produst->getPerCostPrice() > $arraySums['sr_marj'] * 0.85) {
                    if ($produst->getMarj() < $arraySums['sr_marj']) {
                        if ($produst->getMarj() < $arraySums['sr_marj'] * 0.85) {
                            $oth1 = 'Спящие';
                            $oth1_sh = 'Стимулировать продажу. Поставить в выгодные позиции меню. Если не поможет — удаляем.';
                        } else {
                            $oth1 = 'Спящие (потенциал прайм)';
                            $oth1_sh = 'Стимулировать продажу. Поставить в выгодные позиции меню. Если не поможет — удаляем.';
                        }
                    } else {
                        if ($produst->getMarj() > $arraySums['sr_marj'] * 1.15) {
                            $oth1 = 'Прайм';
                            $oth1_sh = 'Радуемся и хорошо готовим.';
                        } else {
                            $oth1 = 'Прайм (потенциал спящ)';
                            $oth1_sh = 'Радуемся и хорошо готовим.';
                        }
                    }
                } else {
                    $oth1 = 'Колеблющийся';
                    $oth1_sh = 'Смотри другие модели';
                }
            }
            $produst->setFirstOtchName($oth1);
            $produst->setFirstOtchComment($oth1_sh);

        }

    }


    public static function setAbcSecond($old_products, $arraySums)
    {

        $oth2 = "";
        $oth2_sh = "";

        $mean_part = 100 / count($old_products);
        foreach ($old_products as $tov_id => $product) {

            $part_sale = $product->getSale() / ($arraySums['val_sale'] / 100);

            $marj_one = $product->getPrice() - $product->getCostPrice();

            //print "$tov_id) $dolay_sale {$item['marj_ed']} > {$arraySums['sr_marj_ed_wd']} and {$dolay_sale} > {$arraySums['sr_dolya_wd']} * 0.8 <br> {$arraySums['sr_dolya_wd']}<br><br>";

            if (($marj_one > $arraySums['sr_marj_ed']) and ($part_sale > $mean_part * 0.8)) {
                $oth2 = 'Звезды';
                $oth2_sh = 'Всё ок. Следите за качеством.';
            } else if ($marj_one > $arraySums['sr_marj_ed'] and $part_sale < $mean_part * 0.8) {
                $oth2 = 'Загадки';
                $oth2_sh = 'Следует стимулировать спрос.';
            } else if ($marj_one < $arraySums['sr_marj_ed'] and $part_sale < $mean_part * 0.8) {
                $oth2 = 'Собаки';
                $oth2_sh = 'Желательно исключить. продаются мало, наценка тоже не большая.';
            } else if ($marj_one < $arraySums['sr_marj_ed'] and $part_sale > $mean_part * 0.8) {
                $oth2 = 'Темные лошадки';
                $oth2_sh = 'Требуется увеличить цену или снизисть себестоимость.';
            }
            $product->setSecondOtchName($oth2);
            $product->setSecondOtchComment($oth2_sh);
        }
    }
    public function mySortSale($f1, $f2)
    {
        if ($f1->getSale() < $f2->getSale()) return 1;
        elseif ($f1->getSale() > $f2->getSale()) return -1;
        else return 0;
    }


}