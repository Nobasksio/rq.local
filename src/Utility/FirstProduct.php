<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 10/06/2019
 * Time: 23:02
 */

namespace App\Utility;


use App\Entity\Ttk;
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

    public function __construct(ProductRepository $productRepository,
                                TtkRepository $ttkRepository,
                                TtkComponentRepository $ttkComponentRepository,
                                PhotoRepository $photoRepository,
                                EntityManagerInterface $em)
    {
        $this->ProductRepository = $productRepository;
        $this->TtkRepository = $ttkRepository;
        $this->TtkComponentRepository = $ttkComponentRepository;
        $this->photoRepository = $photoRepository;
        $this->em = $em;
    }

    public function getFirstProduct($categoory)
    {

        $prooducts = $this->ProductRepository->findBy(['category' => $categoory, 'old_status' => 2]);

        if (count($prooducts) > 0) {

            $product = $this->ProductRepository->findOneBy(['id' => $prooducts[0]->getId()]);
            $response_arr = array('id' => $prooducts[0]->getId());
            $response_arr['selected_category'] = $product->getCategory()->getId();
            $subcategory = $product->getSubcategory();

            $response_arr['name'] = $product->getNameWork();

            $ttk = $this->TtkRepository->findOneBy(['product' => $product]);

            if ($subcategory) {
                $response_arr['selected_subcategory'] = $subcategory->getId();
            } else {
                $response_arr['selected_subcategory'] = 0;
            }

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
            if ($ttk->getName() == null){
                $response_arr['ttk_name'] = '';
            }

            $photos = $this->photoRepository->findBy(['product'=>$product, 'type'=>5,'status'=>4],['date_create' => 'DESC']);
            if (!$photos){
                $photos = $this->photoRepository->findBy(['product'=>$product, 'type'=>1,'status'=>4]);
            }
            if ($photos) {
                $photo = '/uploads/file/'.$photos[0]->getImg();
            } else {
                $photo = null;
            }

            $response_arr['photo'] = $photo;


            $response_arr['comment'] = $ttk->getComment();
            if ($ttk->getComment() == null) {
                $response_arr['comment'] = '';
            }
            $response_arr['technology'] = $ttk->getTechnology();
            if ($ttk->getTechnology() == null) {
                $response_arr['technology'] = '';
            }
            if ($product->getType()) {
                $response_arr['type'] = $product->getType()->getId();
            } else {
                $response_arr['type'] = "0";
            }

            $Components = $this->TtkComponentRepository->findBy(['Ttk' => $ttk]);
            $response_arr['components'] = array();
            foreach ($Components as $component_item) {
                $component = $component_item->getComponent();
                $measure = $component_item->getMeasure();
                $count = $component_item->getCount();

                $response_arr['components'][] = array(
                    'component_name' => $component->getName(),
                    'component_id' => $component->getId(),
                    'measure' => $measure->getId(),
                    'measure_name' => $measure->getName(),
                    'count' => $count
                );
            }
        } else {
            $response_arr = array('id' => 0);
        }
        $prooducts = $this->ProductRepository->findBy(['category' => $categoory, 'old_status' => 2]);
        foreach ($prooducts as $product_item) {
            $response_arr['products'][] = array(
                'id' => $product_item->getId(),
                'name' => $product_item->getNameWork()
            );
        }

        return $response_arr;
    }

}