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
use App\Entity\Degustation;
use App\Entity\DegustationScore;
use App\Entity\OldCategory;
use App\Entity\OldProduct;
use App\Entity\Product;
use App\Entity\Project;
use App\Repository\AnaliticsRepository;
use App\Repository\CategoryRepository;
use App\Repository\DegustationRepository;
use App\Repository\OldCategoryRepository;
use App\Repository\OldProductRepository;
use App\Repository\ProductRepository;
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
    public function index()
    {
        return $this->render('index.html.twig');
    }

    /**
     * @Route("/migrate", name="migrate")
     */
    public function migrate(ProjectRepository $projectRepository)
    {

        $old_projects = $projectRepository->findOldProject();

        $all_analitic = $projectRepository->findOldAnalitic();

        $old_categories = $projectRepository->findOldCat();
        $old_products = $projectRepository->findOldProduct();

        $old_new_categories = $projectRepository->findOldnewCategory();
        $old_new_products = $projectRepository->findOldnewProduct();
        $entityManager = $this->getDoctrine()->getManager();

        foreach ($old_projects as $old_project) {
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
    public function migrateA(ProjectRepository $projectRepository)
    {

        $old_projects = $projectRepository->findOldProject();
        $all_analitic = $projectRepository->findOldAnalitic();

        $entityManager = $this->getDoctrine()->getManager();

        foreach ($all_analitic as $all_anl_key => $old_analitic) {

            $project = $projectRepository->findOneBy(['m_id' => $old_analitic['id_project']]);
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
                             CategoryRepository $categoryRepository,
                             AnaliticsRepository $analiticsRepository)
    {

        $old_projects = $projectRepository->findOldProject();
        $old_categories = $projectRepository->findOldCat();

        $entityManager = $this->getDoctrine()->getManager();

        foreach ($old_categories as $key_oold => $old_category) {

            $analitic = $analiticsRepository->findOneBy(['m_id' => $old_category['id_menu']]);
            if ($analitic) {

                $o_category = $categoryRepository->findOneBy(['m_id' => $old_category['id_cat']]);
                if (!$o_category) {
                    $o_category = new OldCategory();

                    $o_category->setName($old_category['name']);
                    $o_category->setMId($old_category['id_cat']);
                    $o_category->setAnalitics($analitic);

                    $entityManager->persist($o_category);
                }
            }
        }

        $entityManager->flush();

        return $this->render('migrat_ok.html.twig');
    }

    /**
     * @Route("/migrate_p", name="migrate_p")
     */
    public function migrate_p(ProjectRepository $projectRepository,
                              OldCategoryRepository $oldCategoryRepository,
                              OldProductRepository $productRepository
    )
    {
        set_time_limit(0);
        ini_set('memory_limit', '500M');
        $old_projects = $projectRepository->findOldProject();
        $old_products = $projectRepository->findOldProduct();

        $entityManager = $this->getDoctrine()->getManager();

        foreach ($old_products as $key_oold => $old_product) {

            $old_category = $oldCategoryRepository->findOneBy(['m_id' => $old_product['id_cat']]);
            if ($old_category) {

                $o_product = $productRepository->findOneBy(['m_id' => $old_product['id_tov']]);

                if (!$o_product) {
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

        }

        $entityManager->flush();

        return $this->render('migrat_ok.html.twig');

    }

    /**
     * @Route("/migrate_nc", name="migrate_mc")
     */
    public function migrate_nc(ProjectRepository $projectRepository,
                               CategoryRepository $categoryRepository,
                               OldCategoryRepository $oldCategoryRepository)
    {

        $entityManager = $this->getDoctrine()->getManager();


        $old_new_categories = $projectRepository->findOldnewCategory();
        foreach ($old_new_categories as $old_new_cat_key => $old_new_cat) {

            $old_category = $oldCategoryRepository->findOneBy(['m_id' => $old_new_cat['id_cat']]);
            $project = $projectRepository->findOneBy(['m_id' => $old_new_cat['id_project']]);
            if ($old_category and $project) {

                $new_category = $categoryRepository->findOneBy(['m_id' => $old_new_cat['id_cat']]);
                if (!$new_category) {
                    $new_category = new Category();

                    $new_category->setName($old_new_cat['name']);
                    $new_category->setProjectId($project);
                    $new_category->setMId($old_new_cat['id_cat']);
                    $new_category->setOldCategory($old_category->getId());

                    $entityManager->persist($new_category);
                }

            }


        }
        $entityManager->flush();
        return $this->render('migrat_ok.html.twig');
    }


    /**
     * @Route("/migrate_np", name="migrate_np")
     */
    public function migrate_np(ProjectRepository $projectRepository,
                               OldProductRepository $oldProductRepository,
                               ProductRepository $productRepository,
                               CategoryRepository $categoryRepository)
    {

        set_time_limit(0);
        ini_set('memory_limit', '500M');
        $entityManager = $this->getDoctrine()->getManager();

        $old_new_products = $projectRepository->findOldnewProduct();


        foreach ($old_new_products as $key_old_p => $old_product) {


            $old_product_ent = $oldProductRepository->findOneBy(['m_id' => $old_product['id_tov']]);

            $category = $categoryRepository->findOneBy(['m_id' => $old_product['id_nm_cat']]);
            $project = $projectRepository->findOneBy(['m_id' => $old_product['id_project']]);

            if ($category and $project) {

                $new_product = $productRepository->findOneBy(['m_id' => $old_product['id_tov']]);

                if (!$new_product) {
                    $new_product = new Product();
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
        }
        $entityManager->flush();
        return $this->render('migrat_ok.html.twig');
    }

    /**
     * @Route("/migrate_degustation", name="migrate_degustation")
     */
    public function migrate_deg(ProjectRepository $projectRepository,
                                DegustationRepository $degustationRepository,
                                CategoryRepository $categoryRepository)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $old_degustation = $projectRepository->findOldDegustation();


        set_time_limit(0);
        ini_set('memory_limit', '500M');
        foreach ($old_degustation as $key => $old_degustation) {

            if ($old_degustation['id_project'] == 0) {
                continue;
            }
            $project = $projectRepository->findOneBy(['m_id' => $old_degustation['id_project']]);

            if (!$project) {
                continue;
            }

            $Degustation = $degustationRepository->findOneBy(['old_id' => $old_degustation['id_tasting']]);

            if (!$Degustation) {
                $Degustation = new Degustation();
            }

            if ($old_degustation['hash'] == null) {
                $old_degustation['hash'] = md5(uniqid());
            }
            $Degustation->setHash($old_degustation['hash']);
            $Degustation->setDate(new \DateTime($old_degustation['date_tasting']));


            $Degustation->setproject($project);

            $Degustation->setName($old_degustation['name_degust']);

            $Degustation->setDescription($old_degustation['discription']);
            $Degustation->setPlace($old_degustation['place']);
            $Degustation->setStatus($old_degustation['status']);
            $Degustation->setOldId($old_degustation['id_tasting']);

            $entityManager->persist($Degustation);
            $entityManager->flush();

        }

        $entityManager->flush();
        return $this->render('migrat_ok.html.twig', ['count' => 1]);
    }

    /**
     * @Route("/migrate_degustation_p", name="migrate_degustation_p")
     */

    public function migrate_degust_product(OldProductRepository $oldProductRepository,
                                           ProjectRepository $projectRepository,
                                           DegustationRepository $degustationRepository,
                                           ProductRepository $productRepository)
    {


        $entityManager = $this->getDoctrine()->getManager();

        $Degustations = $degustationRepository->findAll();

        foreach ($Degustations as $degustation) {
            $old_products = $projectRepository->findOldProductDegustation($degustation->getOldId());

            foreach ($old_products as $old_product) {

                $product = $productRepository->findOneBy(['m_id' => $old_product['id_tov']]);

                if ($product) {

                    $degustation->addProduct($product);
                    $entityManager->persist($degustation);
                }

            }

        }
        $entityManager->flush();

//        $old_ratings = $projectRepository->findOldRating();
//
//        foreach ($old_ratings as $old_rating){
//            $old_rating['id_tov'];
//            $product = $productRepository->findOneBy(['m_id'=>$old_rating['id_tov']]);
//            if ($product){
//
//                $degustation_score = new DegustationScore();
//
//                $degustation_score->setProduct($product);
//                $degustation_score->setTasteScore($old_rating['taste']);
//                $degustation_score->setViewScore($old_rating['view']);
//                $degustation_score->setConceptScore($old_rating['concept']);
//                $degustation_score->setPriceScore((float)$old_rating['price']);
//                $degustation_score->setComment($old_rating['coment']);
//                $degustation_score->setDegustationUserHash($old_rating['id_user']);
//                $degustation_score->setDateUpdate(new \DateTime($old_rating['date']));
//                $degustation_score->setDegustation($Degustation);
//                $entityManager->persist($degustation_score);
//            }
//
//
//        }
        return $this->render('migrat_ok.html.twig', ['count' => 1]);
    }

    /**
     * @Route("/migrate_degustation_score", name="migrate_degustation_score")
     */

    public function migrate_degust_rait(OldProductRepository $oldProductRepository,
                                        ProjectRepository $projectRepository,
                                        DegustationRepository $degustationRepository,
                                        ProductRepository $productRepository)
    {

        $old_ratings = $projectRepository->findOldRating();
        $entityManager = $this->getDoctrine()->getManager();

        $i= 0 ;
        $i2 = 0;

        set_time_limit(0);
        ini_set('memory_limit', '500M');

        foreach ($old_ratings as $old_rating) {
            $old_rating['id_tov'];
            $product = $productRepository->findOneBy(['m_id' => $old_rating['id_tov']]);
            if ($product) {


                $degustation_score = new DegustationScore();

                $degustation_score->setProduct($product);
                $degustation_score->setTasteScore($old_rating['taste']);
                $degustation_score->setViewScore($old_rating['view']);
                $degustation_score->setConceptScore($old_rating['concept']);
                $degustation_score->setPriceScore((float)$old_rating['price']);
                $degustation_score->setComment($old_rating['coment']);
                $degustation_score->setDegustationUserHash($old_rating['id_user']);
                $degustation_score->setDateUpdate(new \DateTime($old_rating['date']));
                $degustation_score->setDegustation($product->getDegustations()[0]);
                $entityManager->persist($degustation_score);

                $i++;
                $i2++;
            }

            if ($i > 500){
                $entityManager->flush();

                $i = 0;
            }


        }

        return $this->render('migrat_ok.html.twig', ['count' => $i2]);
    }


    /**
     * @Route("/down", name="testDown")
     */
    public function testDown(iikoDownloadUtil $downloadUtil, ProjectRepository $projectRepository)
    {

        $project = $projectRepository->findOneBy(['id' => 47]);
        $count = $downloadUtil->saveDepartments($project);
        return $this->render('download.html.twig', ['count' => $count]);
    }

    /**
     * @Route("/down_c", name="testDown2")
     */
    public function testDown2(iikoDownloadUtil $downloadUtil, ProjectRepository $projectRepository)
    {

        $project = $projectRepository->findOneBy(['id' => 47]);
        $count = $downloadUtil->saveIikoCategory($project);
        return $this->render('download.html.twig', ['count' => $count]);
    }

    /**
     * @Route("/down_p", name="testDown3")
     */
    public function testDown3(iikoDownloadProduct $downloadProduct, ProjectRepository $projectRepository)
    {

        $project = $projectRepository->findOneBy(['id' => 47]);
        $count = $downloadProduct->saveProduct($project);
        return $this->render('download.html.twig', ['count' => $count]);
    }

    /**
     * @Route("/down_ttk", name="testDown4")
     */
    public function testDown4(iikoDownloadProduct $downloadProduct, ProjectRepository $projectRepository)
    {

        $project = $projectRepository->findOneBy(['id' => 47]);
        $count = $downloadProduct->saveTtk($project);
        return $this->render('download.html.twig', ['count' => $count]);
    }

    /**
     * @Route("/down_category2", name="testDown6")
     */
    public function testDown5(iikoDownloadUtil $downloadUtil, ProjectRepository $projectRepository)
    {

        $project = $projectRepository->findOneBy(['id' => 47]);
        $count = $downloadUtil->updateIikoCategory();
        return $this->render('download.html.twig', ['count' => $count]);
    }


}