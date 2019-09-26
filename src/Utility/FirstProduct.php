<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 10/06/2019
 * Time: 23:02
 */

namespace App\Utility;


use App\Entity\DescriptionMenu;
use App\Entity\Product;
use App\Entity\Ttk;
use App\Repository\DescriptionMenuRepository;
use App\Repository\IikoProductRepository;
use App\Repository\NameMenuRepository;
use App\Repository\PhotoRepository;
use App\Repository\ProductRepository;
use App\Repository\TtkComponentRepository;
use App\Repository\TtkRepository;
use Doctrine\ORM\EntityManagerInterface;

class FirstProduct
{

    private $ProductRepository;
    private $TtkRepository;
    private $TtkComponentRepository;
    private $em;
    private $iikoProductRepository;
    private $photoRepository;
    private $namemenuRepository;
    private $descriptionMenuRepository;

    public function __construct(ProductRepository $productRepository,
                                TtkRepository $ttkRepository,
                                IikoProductRepository $iikoProductRepository,
                                TtkComponentRepository $ttkComponentRepository,
                                PhotoRepository $photoRepository,
                                NameMenuRepository $namemenuRepository,
                                DescriptionMenuRepository $descriptionMenuRepository,
                                EntityManagerInterface $em)
    {
        $this->ProductRepository = $productRepository;
        $this->iikoProductRepository = $iikoProductRepository;
        $this->TtkRepository = $ttkRepository;
        $this->TtkComponentRepository = $ttkComponentRepository;
        $this->photoRepository = $photoRepository;
        $this->namemenuRepository = $namemenuRepository;
        $this->descriptionMenuRepository = $descriptionMenuRepository;
        $this->em = $em;
    }

    public function getFirstProduct($category)
    {

        $prooducts = $this->ProductRepository->findBy(['category' => $category, 'old_status' => 2]);

        if (count($prooducts) > 0) {

            $product = $this->ProductRepository->findOneBy(['id' => $prooducts[0]->getId()]);

            $response_arr = $this->getProductInfoArray($product);

        } else {
            $response_arr = array('id' => 0);
        }

        //need make it separate class
        $prooducts = $this->ProductRepository->findBy(['category' => $category, 'old_status' => 2]);
        foreach ($prooducts as $product_item) {
            $response_arr['products'][] = array(
                'id' => $product_item->getId(),
                'name' => $product_item->getNameWork()
            );
        }

        return $response_arr;
    }

    public function getProductInfoArray(Product $product){


        $response_arr = array('id' => $product->getId());
        $response_arr['selected_category'] = $product->getCategory()->getId();
        $subcategory = $product->getSubcategory();
        $response_arr['name_work'] = $product->getNameWork();
        $ttk = $this->TtkRepository->findOneBy(['product' => $product]);

        $response_arr['status'] = $product->getOldStatus();

        if ($subcategory) {
            $response_arr['selected_subcategory'] = $subcategory->getId();
        } else {
            $response_arr['selected_subcategory'] = '';
        }

        if ($product->getType()) {
            $response_arr['type'] = $product->getType()->getId();
        } else {
            $response_arr['type'] = 0;
        }


        $response_arr['ves'] = $product->getWeight();
        $response_arr['cost_price'] = $product->getCostPrice();

        $response_arr['price'] = $product->getPrice();

        $name_menu = $this->namemenuRepository->findOneBy(['product'=>$product,'active'=>true]);

        if ($name_menu){
            $name_menu_str = $name_menu->getName();
        } else {
            $name_menu_str = null;
        }
        $response_arr['name_menu'] = $name_menu_str;

        $response_arr['plates'] = [];

        $description_menu = $this->descriptionMenuRepository->findOneBy(['Product'=>$product,'active'=>true]);

        if ($description_menu){
            $description_menu_str = $description_menu->getDescription();
        } else {
            $description_menu_str= null;
        }
        $response_arr['description_menu'] = $description_menu_str;

        if ($product->getOldProduct()){
            $response_arr['old_price'] = $product->getOldProduct()->getPrice();


        } else {
            $response_arr['old_price'] = $product->getOldPrice();
        }


        $photos = $this->photoRepository->findBy(['product'=>$product, 'status'=>4],['date_create' => 'DESC']);
        if (!$photos){
            $photos = $this->photoRepository->findBy(['product'=>$product, 'type'=>1,'status'=>4]);
        }
        if ($photos) {
            $photo = '/uploads/file/'.$photos[0]->getImg();
            if ($photos[0]->getPreview()){
                $preview = '/uploads/file/preview/'.$photos[0]->getPreview();
            } else {
                $preview = $photo;
            }
        } else {
            $photo = null;
            $preview = null;
        }
        $response_arr['photo'] = $photo;

        $response_arr['photos'] = [];
        foreach ($photos as $photo) {
            if ($photo->getStatus() == 4) {
                $preview = $photo->getPreview();
                $img = $photo->getImg();
                $id = $photo->getId();
                $type = $photo->gettype();
                $isMain = $photo->getisMain();

                $response_arr['photos'][] = ['preview' => $preview,
                    'img' => $img,
                    'id' => $id,
                    'isMain' => $isMain,
                    'type' => $type];
            }

        }


        $response_arr['preview'] = $preview;

        if (!$ttk) {
            $ttk = new Ttk();

            $ttk->setProduct($product);
            $this->em->persist($ttk);
            $this->em->flush();
        }

        $response_arr['ttk_num'] = $ttk->getNumber();
        if ($ttk->getNumber() == null) {
            $response_arr['ttk_num'] = '';
        }

        $response_arr['ttk_name'] = $ttk->getName();
        if (($ttk->getName() != null) and ($ttk->getName() !='')){
            $response_arr['name'] = $ttk->getName();
            $response_arr['ttk_name'] = $ttk->getName();
        }

        if ($ttk->getIikoId()){
           $iiko_product = $this->iikoProductRepository->findOneBy(['iiko_id'=> $ttk->getIikoId()]);

           $response_arr['iiko_ttk'] = ['id'=>$iiko_product->getId(),
               'name'=>$iiko_product->getName()];
        } else {
            $response_arr['iiko_ttk'] = [];
        }


        $response_arr['comment'] = $ttk->getComment();
        if ($ttk->getComment() == null) {
            $response_arr['comment'] = '';
        }

        $comments = $product->getComments();
        $response_arr['comments'] = [];
        foreach ($comments as $comment){
            $response_arr['comments'][] = ['text'=>$comment->getText(),
                'user_name' => $comment->getUser()->getName(),
                'date' => $comment->getDate()->format('d.m.Y')
                ];
        }

        $response_arr['technology'] = str_replace(array("\r\n", "\r", "\n","\t"), '', $ttk->getTechnology());
        $response_arr['technology'] = preg_replace('/[^a-zA-Zа-яА-Я0-9\s]/ui', '',$response_arr['technology']);
        //$response_arr['technology'] = $ttk->getTechnology();
        if ($response_arr['technology'] == null) {
            $response_arr['technology'] = '';
        }


        $Components = $this->TtkComponentRepository->findBy(['Ttk' => $ttk]);

        $response_arr['consist'] = '';
        $response_arr['components'] = array();
        foreach ($Components as $component_item) {
            $component = $component_item->getComponent();
            $measure = $component_item->getMeasure();
            $count = $component_item->getCount();
            if ($response_arr['consist'] == '') {
                $delimetr = '';
            } else {
                $delimetr = ', ';
            }
            $response_arr['consist'] .= $delimetr.$component->getName();
            $response_arr['components'][] = array(
                'name' => $response_arr['consist'] = preg_replace('/[^a-zA-Zа-яА-Я0-9\s]/ui','',$component->getName()),
                'id' => $component->getId(),
                'measure' => $measure->getId(),
                'measure_name' => $measure->getName(),
                'count' => $count
            );
        }


        if ($response_arr['consist'] == ''){
            $response_arr['consist'] = $product->getConsist();
        }

        $response_arr['consist'] = preg_replace('/[^a-zA-Zа-яА-Я0-9\s]/ui', '',$response_arr['consist'] );
//        $response_arr['consist'] = '';

        return $response_arr;
    }

}