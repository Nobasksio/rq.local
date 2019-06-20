<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 04/04/2019
 * Time: 13:48
 */

namespace App\Controller;

use App\Entity\Analitics;
use App\Entity\Category;
use App\Entity\OldCategory;
use App\Entity\OldProduct;
use App\Entity\Product;
use App\Entity\Project;
use App\Repository\AnaliticsRepository;
use App\Repository\CategoryRepository;
use App\Repository\OldCategoryRepository;
use App\Repository\OldProductRepository;
use App\Repository\ProjectRepository;
use App\Utility\iikoDownloadProduct;
use App\Utility\iikoDownloadUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(){
        return $this->render('index.html.twig');
    }

    /**
     * @Route("/migrate", name="migrate")
     */
    public function migrate(ProjectRepository $projectRepository){

        $old_projects = $projectRepository->findOldProject();

        $all_analitic = $projectRepository->findOldAnalitic();

        $old_categories = $projectRepository->findOldCat();
        $old_products = $projectRepository->findOldProduct();

        $old_new_categories = $projectRepository->findOldnewCategory();
        $old_new_products = $projectRepository->findOldnewProduct();
        $entityManager = $this->getDoctrine()->getManager();

        foreach($old_projects as $old_project){
            $project = new Project();

            $project->setProjectName($old_project['name_project']);
            $project->setMId($old_project['id_project']);
            $user = $this->getUser();

            $project->addUser($user);
            $entityManager->persist($project);


        }
        $entityManager->flush();
        return $this->render('migrat_ok.html.twig');
    }
    /**
     * @Route("/migrate_a", name="migrate_a")
     */
    public function migrateA(ProjectRepository $projectRepository){

        $old_projects = $projectRepository->findOldProject();
        $all_analitic = $projectRepository->findOldAnalitic();

        $entityManager = $this->getDoctrine()->getManager();

        foreach($all_analitic as $all_anl_key=>$old_analitic){

                $project = $projectRepository->findOneBy(['m_id'=>$old_analitic['id_project']]);
                if ($project) {
                    $analitic = new Analitics();

                    $analitic->setProject($project);
                    $analitic->setName($old_analitic['name']);

                    $analitic->setDateCreate(new \DateTime($old_analitic['date_create']));
                    $analitic->setMId($old_analitic['id_menu']);

                    $analitic->setDateStart(new \DateTime($old_analitic['date_start']));

                    $analitic->setDateFinish(new \DateTime($old_analitic['date_end']));


                    $entityManager->persist($analitic);

                    $entityManager->flush();


                    unset($all_analitic[$all_anl_key]);
                }

        }

        $entityManager->flush();

        return $this->render('migrat_ok.html.twig');
    }

    /**
     * @Route("/migrate_c", name="migrate_c")
     */
    public function migratec(ProjectRepository $projectRepository,
                            AnaliticsRepository $analiticsRepository){

        $old_projects = $projectRepository->findOldProject();
        $old_categories = $projectRepository->findOldCat();

        $entityManager = $this->getDoctrine()->getManager();

        foreach ($old_categories as $key_oold=>$old_category){

                $analitic = $analiticsRepository->findOneBy(['m_id'=>$old_category['id_menu']]);
                if ($analitic) {


                    $o_category = new OldCategory();

                    $o_category->setName($old_category['name']);
                    $o_category->setMId($old_category['id_cat']);
                    $o_category->setAnalitics($analitic);

                    $entityManager->persist($o_category);
                }
            }

        $entityManager->flush();

        return $this->render('migrat_ok.html.twig');
    }

    /**
     * @Route("/migrate_p", name="migrate_p")
     */
    public function migrate_p(ProjectRepository $projectRepository,
                                OldCategoryRepository $oldCategoryRepository)
    {

        $old_projects = $projectRepository->findOldProject();
        $old_products = $projectRepository->findOldProduct();

        $entityManager = $this->getDoctrine()->getManager();

        foreach ($old_products as $key_oold => $old_product) {

            $old_category = $oldCategoryRepository->findOneBy(['m_id' => $old_product['id_cat']]);
            if ($old_category) {
                $o_product = new OldProduct();

                $o_product->setName($old_product['name']);

                $o_product->setSale((float)$old_product['kol_sale']);

                $o_product->setCategory($old_category);
                $o_product->setPrice((float)$old_product['price']);
                $o_product->setMId($old_product['id_tov']);
                $o_product->setcostPrice((float)$old_product['ss']);

                $entityManager->persist($o_product);
            }

        }

        $entityManager->flush();

        return $this->render('migrat_ok.html.twig');

    }

    /**
     * @Route("/migrate_nc", name="migrate_mc")
     */
    public function migrate_nc(ProjectRepository $projectRepository,
                              OldCategoryRepository $oldCategoryRepository)
    {

        $entityManager = $this->getDoctrine()->getManager();


        $old_new_categories = $projectRepository->findOldnewCategory();
        foreach ($old_new_categories as $old_new_cat_key=>$old_new_cat){

                $old_category = $oldCategoryRepository->findOneBy(['m_id'=>$old_new_cat['id_cat']]);
                $project = $projectRepository->findOneBy(['m_id'=>$old_new_cat['id_project']]);
                if ($old_category and $project) {
                    $new_category = new Category();

                    $new_category->setName($old_new_cat['name']);
                    $new_category->setProjectId($project);
                    $new_category->setMId($old_new_cat['id_cat']);
                    $new_category->setOldCategory($old_category->getId());

                    $entityManager->persist($new_category);

                }



        }
        $entityManager->flush();
        return $this->render('migrat_ok.html.twig');
    }


    /**
     * @Route("/migrate_np", name="migrate_np")
     */
    public function migrate_np(ProjectRepository $projectRepository,
                               OldProductRepository $oldProductRepository, CategoryRepository $categoryRepository)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $old_new_products = $projectRepository->findOldnewProduct();


        foreach ($old_new_products as $key_old_p => $old_product) {

            $new_product = new Product();

            $old_product_ent = $oldProductRepository->findOneBy(['m_id' => $old_product['id_tov']]);

            $category = $categoryRepository->findOneBy(['m_id' => $old_product['id_nm_cat']]);
            $project = $projectRepository->findOneBy(['m_id' => $old_product['id_project']]);

            if ($category and $project) {
                $new_product->setNameWork($old_product['name_work']);
                $new_product->setOldProduct($old_product_ent);
                $new_product->setCategory($category);
                $new_product->setMId($old_product['id_tov']);
                $new_product->setProject($project);
                $new_product->setOldStatus($old_product['status']);
                $new_product->setCostPrice((float)$old_product['new_ss']);
                $new_product->setWeight((float)$old_product['ves']);


                $entityManager->persist($new_product);
            }
        }
        $entityManager->flush();
        return $this->render('migrat_ok.html.twig');
    }

    /**
     * @Route("/down", name="testDown")
     */
    public function testDown(iikoDownloadUtil $downloadUtil ,ProjectRepository $projectRepository){

        $project = $projectRepository->findOneBy(['id'=>47]);
        $count = $downloadUtil->saveDepartments($project);
        return $this->render('download.html.twig',['count'=>$count]);
    }

    /**
     * @Route("/down_c", name="testDown2")
     */
    public function testDown2(iikoDownloadUtil $downloadUtil ,ProjectRepository $projectRepository){

        $project = $projectRepository->findOneBy(['id'=>47]);
        $count = $downloadUtil->saveIikoCategory($project);
        return $this->render('download.html.twig',['count'=>$count]);
    }
    /**
     * @Route("/down_p", name="testDown3")
     */
    public function testDown3(iikoDownloadProduct $downloadProduct ,ProjectRepository $projectRepository){

        $project = $projectRepository->findOneBy(['id'=>47]);
        $count = $downloadProduct->saveProduct($project);
        return $this->render('download.html.twig',['count'=>$count]);
    }
    /**
     * @Route("/down_ttk", name="testDown4")
     */
    public function testDown4(iikoDownloadProduct $downloadProduct ,ProjectRepository $projectRepository){

        $project = $projectRepository->findOneBy(['id'=>47]);
        $count = $downloadProduct->saveTtk($project);
        return $this->render('download.html.twig',['count'=>$count]);
    }

    /**
     * @Route("/down_category2", name="testDown6")
     */
    public function testDown5(iikoDownloadUtil $downloadUtil, ProjectRepository $projectRepository){

        $project = $projectRepository->findOneBy(['id'=>47]);
        $count = $downloadUtil->updateIikoCategory();
        return $this->render('download.html.twig',['count'=>$count]);
    }




}