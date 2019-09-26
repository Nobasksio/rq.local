<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 11/06/2019
 * Time: 21:19
 */

namespace App\Utility;


use App\Entity\Department;
use App\Entity\IikoCategory;
use App\Repository\DepartmentRepository;
use App\Repository\IikoCategoryRepository;
use App\Repository\IikoProductRepository;
use App\Repository\IikoTtkComponentRepository;
use App\Repository\IikoTtkRepository;
use Curl\Curl;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class iikoDownloadUtil
{
    protected $session;
    protected $mylogin = 'ElchenkovA';
    protected $PASS = '123456';
    protected $hash_password;
    protected $key_hash;
    protected $curl_object;
    protected $targetDirectory;
    protected $em;
    protected $ipr;
    protected $iikoCategoryRepository;
    protected $departmentRepository;
    protected $ittkr;
    protected $ittk_componentr;
    protected $myseting_place;

    public function __construct(SessionInterface $session,
                                $targetDirectory,
                                $myseting_place,
                                EntityManagerInterface $em,
                                IikoProductRepository $ipr,
                                IikoCategoryRepository $iikoCategoryRepository,
                                IikoTtkRepository $ittkr,
                                IikoTtkComponentRepository $ittk_componentr,
                                DepartmentRepository $departmentRepository)
    {
        $this->ipr = $ipr;
        $this->ittkr = $ittkr;
        $this->ittk_componentr = $ittk_componentr;
        $this->departmentRepository = $departmentRepository;
        $this->iikoCategoryRepository = $iikoCategoryRepository;
        $this->session = $session;
        $this->em = $em;
        $this->targetDirectory = $targetDirectory;
        $this->myseting_place = $myseting_place;

        $this->curl_object = new Curl();
        $old_hash = $this->session->get("key_hash");
        $this->curl_object->get('http://195.206.46.94:9080/resto/api/logout?key='.$old_hash);

        $this->hash_password = sha1($this->PASS);

        $this->curl_object->get('http://195.206.46.94:9080/resto/api/auth?login='.$this->mylogin.'&pass='.$this->hash_password);
        $this->key_hash = $this->curl_object->response;

        $this->session->set("key_hash", $this->key_hash);
    }


    public function getSales(array $departments, $start, $finish){

        $date_start_str =  $start->format('Y-m-d').'T00:00:00.000';
        $date_finish_str =  $finish->format('Y-m-d').'T00:00:00.000';



            $request = array(
                "reportType" => "SALES",
                "groupByRowFields" => array(
                    "DishName",
                    "DishGroup",
                    "DishGroup.Num",
                    "DishCode"
                ),
                "groupByColFields" => array(),
                "aggregateFields" => array(
                    "ProductCostBase.OneItem",
                    "DishDiscountSumInt.averagePrice",
                    "DishAmountInt"
                ),
                "filters" => array(
                    "OpenDate.Typed" => array(
                        "filterType" => "DateRange",
                        "periodType" => "CUSTOM",
                        "from" => "$date_start_str",
                        "to" => "$date_finish_str"
                    ),
                    "DeletedWithWriteoff" => array(
                        "filterType" => "ExcludeValues",
                        "values" => array("DELETED_WITH_WRITEOFF","DELETED_WITHOUT_WRITEOFF")
                    ),
                    "OrderDeleted" => array(
                        "filterType" => "IncludeValues",
                        "values" => ["NOT_DELETED"]
                    ),
                    "Department.Code" =>array(
                        "filterType" => "IncludeValues",
                        "values" => $departments
                    ),
                )
            );
            set_time_limit(0);
            $this->curl_object->setConnectTimeout(300);
            $this->curl_object->setTimeout(300);
            $req = json_encode($request);
            $this->curl_object->setHeader('Content-Type', 'application/json');
            $this->curl_object->post('http://195.206.46.94:9080/resto/api/v2/reports/olap?key='.$this->key_hash."&reportType=TRANSACTIONS", $req);


            $data = $this->curl_object->response;
            $this->closeConnection();
            return $data;

    }
    public function saveDepartments($project){
        $response = $this->responseDepartments();

        $array_response = $this->toArray($response);

        foreach ($array_response  as $department_item){
             $department = new Department();

            $department->setName($department_item['name']);
            $department->setType($department_item['type']);
            if (isset($department_item['code']) and $department_item['code'] !='') {
                $department->setCode($department_item['code']);
            }
            $department->setIikoId($department_item['id']);
            $department->addProject($project);
            if (isset($department_item['parentId']) and $department_item['parentId'] !='') {
                $department->setIikoParentId($department_item['parentId']);
            }
            if (isset($department_item['taxpayerIdNumder']) and $department_item['taxpayerIdNumder'] !='') {
                $department->setTaxpayerIdNum($department_item['taxpayerIdNumder']);
            }

            $this->em->persist($department);

        }

        $this->em->flush();

        return count($array_response);

    }

    public function saveIikoCategory(){
        $response = $this->responseCategory();
        $num_added_early = 0;
        foreach ($response  as $category_item) {

            $category = $this->iikoCategoryRepository->findOneBy(['iiko_id'=>$category_item->id]);
            if (!$category) {
                $iiko_category = new IikoCategory;

                $iiko_category->setIikoId($category_item->id);
                $iiko_category->setDeleted($category_item->deleted);
                $iiko_category->setName($category_item->name);
                $iiko_category->setDescription($category_item->description);
                $iiko_category->setNum($category_item->num);
                $iiko_category->setCode($category_item->code);
                $iiko_category->setParent($category_item->parent);
                $iiko_category->setUserCategory($category_item->category);
                $iiko_category->setPosition($category_item->position);
                //$iiko_category->setVisibilityFilter($category_item->visibilityFilter);

                $this->em->persist($iiko_category);
            }
        }
        try {
            $this->em->flush();
        } catch (UniqueConstraintViolationException $exception){
            $num_added_early++;
        }
        return count($response)-$num_added_early;
    }
    public function updateIikoCategory(){
        $response = $this->responseCategory();

        $encoder = new JsonEncoder();

        $normalizer = new ObjectNormalizer();
        $serializer = new Serializer([$normalizer], [$encoder]);
        foreach ($response  as $key=>$category_item) {
            $category = $this->iikoCategoryRepository->findOneBy(['iiko_id'=> $category_item->id]);

            if ($category) {

                if ($category_item->visibilityFilter) {
                    $visible = $serializer->serialize($category_item->visibilityFilter->departments, 'json');
                    foreach ($category_item->visibilityFilter->departments as $department_id) {
                        $department = $this->departmentRepository->findOneBy(['iiko_id' => $department_id]);
                        if ($department) {
                            $category->addDepartment($department);
                        }
                    }


                }
                if ($category_item->visibilityFilter) {
                    $this->em->persist($category);
                }

                if ($key % 300 == 0) {
                    $this->em->flush();
                }
            }
        }
        $this->em->flush();
        return count($response);
    }

    public function responseCategory(){
        $curl = new Curl();
        $curl->setHeader('Content-Type', 'application/json');

        $curl->get('http://195.206.46.94:9080/resto/api/v2/entities/products/group/list?key='.$this->key_hash);
        $this->closeConnection();

        $curl_response = $curl->response;

        return $curl->response;
    }

    public function responseDepartments(){
        $curl = new Curl();
        $curl->setHeader('Content-Type', 'application/json');

        $curl->get('http://195.206.46.94:9080/resto/api/corporation/departments?key='.$this->key_hash);
        $this->closeConnection();

        $curl_response = $curl->response;

        return $curl->response;
    }
    public function toArray($curl_response){
        $string = '';
        $array_response = array();
        $i = 0;
        foreach ($curl_response as $item) {

            $array_response[$i] = array();
            foreach ($item as $properties) {

                $property_name = $properties->getName();
                $property_value = $properties->__toString();
                $array_response[$i][$property_name] = $property_value;
            }
            $i++;
        }

        return $array_response;
    }

    public function saveToFile($array_response){
        $encoder = new JsonEncoder();

        $normalizer = new ObjectNormalizer();
        $serializer = new Serializer([$normalizer], [$encoder]);

        $products_json = $serializer->serialize($array_response, 'json');

        $name_file = $this->targetDirectory.'/depatment.txt';
        $file_content = file_put_contents($name_file,$products_json);

        return $file_content;
    }

    public function parsResponse(){
        $depatment_text = file_get_contents($this->targetDirectory.'/depatment.txt');
        $crawler = new Crawler($depatment_text);

    }

    public function closeConnection(){
        $old_hash = $this->session->get("key_hash");
        $this->curl_object->get('http://195.206.46.94:9080/resto/api/logout?key='.$old_hash);
    }

}