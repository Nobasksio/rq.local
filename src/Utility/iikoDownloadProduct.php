<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 12/06/2019
 * Time: 21:48
 */

namespace App\Utility;


use App\Entity\IikoProduct;
use App\Entity\IikoTtk;
use App\Entity\IikoTtkComponent;
use Curl\Curl;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class iikoDownloadProduct extends iikoDownloadUtil
{

    public function responseProduct(){
        $curl = new Curl();
        $curl->setHeader('Content-Type', 'application/json');
        set_time_limit(0);
        ini_set('memory_limit', '500M');
        $curl->get('http://195.206.46.94:9080/resto/api/v2/entities/products/list?includeDeleted=false&key='.$this->key_hash);
        $this->closeConnection();

        $curl_response = $curl->response;

        return $curl->response;
    }

    public function responseTtk(){
        http://localhost:8080/resto/api/v2/assemblyCharts/getAll?dateFrom=2010-01-01&dateTo=2010-01-02

        $curl = new Curl();
        $curl->setHeader('Content-Type', 'application/json');
        set_time_limit(0);
        ini_set('memory_limit', '700M');
        $curl->get('http://195.206.46.94:9080/resto/api/v2/assemblyCharts/getAll?dateFrom=2019-06-01&dateTo=2019-06-02&key='.$this->key_hash);
        $this->closeConnection();

        $curl_response = $curl->response;

        return $curl->response;
    }

    public function saveTtk($project){
        $response = $this->responseTtk();
        $response = $response->assemblyCharts;

        $encoder = new JsonEncoder();

        $normalizer = new ObjectNormalizer();
        $serializer = new Serializer([$normalizer], [$encoder]);

        $i = 0;
        foreach ($response  as $key=>$ttk_item) {


            $this->session->set("key_p", $key);
            $ttk_item_id = $ttk_item->id;

            if (!$this->isTtk($ttk_item_id)) {

                $iiko_ttk = New IikoTtk();
                $iiko_ttk->setIikoId($ttk_item->id);
                $iiko_ttk->setIikoProductId($ttk_item->assembledProductId);
                $iiko_ttk->setDateFrom($ttk_item->dateFrom);
                $iiko_ttk->setDateTo($ttk_item->dateTo);
                $iiko_ttk->setAssembledAmount($ttk_item->assembledAmount);
                $iiko_ttk->setProductSizeAssemblyStrategy($ttk_item->productSizeAssemblyStrategy);
                $iiko_ttk->setDescription($ttk_item->technologyDescription);
                $iiko_ttk->setDescriptionSecond($ttk_item->description);
                $iiko_ttk->setApperance($ttk_item->appearance);
                $iiko_ttk->setOutputComment($ttk_item->outputComment);

                $this->em->persist($iiko_ttk);

                foreach ($ttk_item->items as $item) {


                    $iiko_ttk_component = new IikoTtkComponent();

                    $iiko_ttk_component->setIikoId($item->id);
                    $iiko_ttk_component->setSort($item->sortWeight);
                    $iiko_ttk_component->setIikoProductId($item->productId);


                    $iiko_ttk_component->setProductSizeSpecification($item->productSizeSpecification);

                    $stores = $serializer->serialize($item->storeSpecification, 'json');
                    $iiko_ttk_component->setStoreSpecification($stores);

                    $iiko_ttk_component->setAmountIn($item->amountIn);
                    $iiko_ttk_component->setNetto($item->amountMiddle);
                    $iiko_ttk_component->setAmountOut($item->amountOut);
                    $iiko_ttk_component->setSort($item->sortWeight);
                    $iiko_ttk_component->setIikoTtk($iiko_ttk);

                    $this->em->persist($iiko_ttk_component);

                    $i++;
                }
                $i++;
            }

            if ($i > 500){
                $this->em->flush();

                $i = 0;
            }

        }

        return count($response);


    }

    public function saveProduct($project){
        $response = $this->responseProduct();
        $num_added_early = 0;
        $encoder = new JsonEncoder();

        $normalizer = new ObjectNormalizer();
        $serializer = new Serializer([$normalizer], [$encoder]);
        $i = 0;

        //$this->em->getConnection()->getConfiguration()->setSQLLogger(null);
        foreach ($response  as $key=>$product_item) {

            $iiko_id = $product_item->id;
            $this->session->set("key_p", $key);

            if (!$this->isProduct($iiko_id)) {
                $iiko_product = New IikoProduct();
                $iiko_product->addProject($project);
                $iiko_product->setIikoId($product_item->id);
                $iiko_product->setDeleted($product_item->deleted);
                $iiko_product->setName($product_item->name);
                $iiko_product->setDescription($product_item->description);
                $iiko_product->setNum($product_item->num);
                $iiko_product->setCode($product_item->code);
                $iiko_product->setParent($product_item->parent);


                $modifiers = $serializer->serialize($product_item->modifiers, 'json');
                $iiko_product->setModifiers($modifiers);
                $iiko_product->setCategory($product_item->category);

                $iiko_product->setFrontImageId($product_item->frontImageId);
                $iiko_product->setPosition($product_item->position);
                $iiko_product->setMainUnit($product_item->mainUnit);

                $excludedSections = $serializer->serialize($product_item->excludedSections, 'json');
                $iiko_product->setExcludedSections($excludedSections);

                $iiko_product->setPlaceType($product_item->placeType);
                $iiko_product->setType($product_item->type);
                $iiko_product->setUnitWeight($product_item->unitWeight);
                $iiko_product->setUnitCapacity($product_item->unitCapacity);

                $this->em->persist($iiko_product);

                //unset($iiko_product);

                $i++;
            } else {
                continue;
            }

            if ($i > 500){
                $this->em->flush();

                $i = 0;
            }

        }

        return count($response)-$num_added_early;
    }

    public function isProduct($iiko_id){

        $product = $this->ipr->findOneBy(['iiko_id'=>$iiko_id]);

        if ($product){
            $product = null;
            return true;
        } else {
            return false;
        }
    }
    public function isTtk($ttk_item_id){

        $product = $this->ittkr->findOneBy(['iiko_id'=>$ttk_item_id]);

        if ($product){
            $product = null;
            return true;
        } else {
            return false;
        }
    }


}