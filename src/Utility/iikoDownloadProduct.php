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

    private $arr_category_search = [];

    public function responseProduct()
    {
        $curl = new Curl();
        $curl->setHeader('Content-Type', 'application/json');
        set_time_limit(0);
        ini_set('memory_limit', '500M');
        $curl->get('http://195.206.46.94:9080/resto/api/v2/entities/products/list?includeDeleted=false&key=' . $this->key_hash);
        $this->closeConnection();

        $curl_response = $curl->response;

        return $curl->response;
    }

    public function responseTtk()
    {
        $curl = new Curl();
        $curl->setHeader('Content-Type', 'application/json');
        set_time_limit(0);
        ini_set('memory_limit', '900M');

        $date_finish = new \DateTime('tomorrow');
        $date_finish = $date_finish->format("Y-m-d");
        $curl->get('http://195.206.46.94:9080/resto/api/v2/assemblyCharts/getAll?dateFrom=2019-06-01&dateTo=' . $date_finish . '&key=' . $this->key_hash);
        $this->closeConnection();

        $curl_response = $curl->response;

        return $curl->response;
    }

    public function saveTtk($response_data = null)
    {
        set_time_limit(0);
        ini_set('memory_limit', '900M');
//        $place = $this->myseting_place;
//        $file_content = file_get_contents($place.'/response.txt');

//        $file_size = filesize(  $place.'/response.txt' );
//
//        $response = json_decode($file_content);
//
        $response = $this->responseTtk();


        //$response_json = json_encode($response);
        //$state = file_put_contents($place.'/response.txt',$response_json);

        //$file_size2 = filesize(  $place.'/response.txt' );

        if ($response_data!=null){

            //set_time_limit(20);
            $this->make_head_category_array($response_data['chosen_category']);
            $category_arr = array_unique($this->arr_category_search);

        }


        $products = [];
        foreach ($category_arr as $category_id) {
            foreach($this->ipr->findIikoIdByCategories($category_id) as $iiko_product){
                $products[] = $iiko_product['iiko_id'];
            }
        }

        $products = array_unique($products);
        $response = $response->assemblyCharts;

        $encoder = new JsonEncoder();

        $normalizer = new ObjectNormalizer();
        $serializer = new Serializer([$normalizer], [$encoder]);

        $i = 0;
        $new_object = 0;
        $update_object = 0;
        $uowSize = 0;
        $test = 0;
        foreach ($response as $key => $ttk_item) {

            $this->session->set("key_p", $key);
            $ttk_item_id = $ttk_item->id;

            if ( $ttk_item->assembledProductId == '667068a6-921c-4fd8-ba14-1a1792c1eac6'){
                $stop = 0;
            }
            if (!in_array($ttk_item->assembledProductId, $products)){
                continue;
            }

            if (!$this->isTtk($ttk_item_id)) {

                $iiko_ttk = New IikoTtk();
                $new_object++;
            } else {
                $iiko_ttk = $this->ittkr->findOneBy(['iiko_id' => $ttk_item_id]);
                $update_object++;
            }

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
            $iiko_ttk->setDateUpdate(new \DateTime('now'));

            //сделать добавление компонентов. его блять нет.
            $this->em->persist($iiko_ttk);

            foreach ($ttk_item->items as $item) {

                $iiko_ttk_component = $this->ittk_componentr->findOneBy(['iiko_id' => $item->id]);
                if (!$iiko_ttk_component) {
                    $iiko_ttk_component = new IikoTtkComponent();
                }

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
                $iiko_ttk_component->setDateUpdate(new \DateTime('now'));

                $this->em->persist($iiko_ttk_component);
                //$this->em->flush();
                $i++;
            }

            unset($response[$key]);
            $i++;
            $uowSize = $this->em->getUnitOfWork()->size();

            if ($i > 1000) {
               $this->em->flush();

                //$response_json = json_encode($response);
                $i = 0;


            }

        }
        $this->em->flush();

        return count($response);


    }

    public function saveProduct($project)
    {
        $response = $this->responseProduct();
        $num_added_early = 0;
        $encoder = new JsonEncoder();

        $normalizer = new ObjectNormalizer();
        $serializer = new Serializer([$normalizer], [$encoder]);
        $i = 0;

        //$this->em->getConnection()->getConfiguration()->setSQLLogger(null);
        foreach ($response as $key => $product_item) {

            $iiko_id = $product_item->id;
            $this->session->set("key_p", $key);

            if (!$this->isProduct($iiko_id)) {
                $iiko_product = New IikoProduct();
                //$iiko_product->addProject($project);
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

            if ($i > 500) {
                $this->em->flush();

                $i = 0;
            }

        }
        $this->em->flush();

        return count($response) - $num_added_early;
    }

    public function isProduct($iiko_id)
    {

        $product = $this->ipr->findOneBy(['iiko_id' => $iiko_id]);

        if ($product) {
            $product = null;
            return true;
        } else {
            return false;
        }
    }

    public function isTtk($ttk_item_id)
    {

        $product = $this->ittkr->findOneBy(['iiko_id' => $ttk_item_id]);

        if ($product) {
            $product = null;
            return true;
        } else {
            return false;
        }
    }

    public function CreateTreeCategory($up_category_array)
    {
        $array_search = [];
        foreach ($up_category_array as $iiko_id ) {
            $array_search[] = $iiko_id['iiko_id'];
            $this->arr_category_search[] = $iiko_id['iiko_id'];
        }

        foreach($array_search as $iiko_category_id){
            $iiko_categories_this_level = $this->iikoCategoryRepository->findIikoIdByIikoId($iiko_category_id);

            $this->CreateTreeCategory($iiko_categories_this_level);
        }

    }

    public function make_head_category_array($array_categories_response){
        $array_out = [];

        foreach($array_categories_response as $category_item){
            $iiko_category_ids = $this->iikoCategoryRepository->findIikoId($category_item['id']);
            $this->CreateTreeCategory($iiko_category_ids);
        }

    }

}