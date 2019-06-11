<?php

namespace App\Controller;

use App\Entity\Analitics;

use App\Entity\OldCategory;
use App\Entity\OldProduct;
use App\Form\AnaliticsType;
use App\Form\Analitics2Type;
use App\Repository\AnaliticsRepository;
use App\Repository\OldCategoryRepository;
use App\Repository\OldProductRepository;
use App\Repository\ProjectRepository;
use App\Utility\AnaliticsUtil;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AnaliticsController extends AbstractController
{

    /**
     * @Route("/project/{project_id}/analitics", name="analitics", methods={"GET"})
     */
    public function index($project_id, ProjectRepository $projectRepository, AnaliticsRepository $analiticsRepository): Response
    {
        $project = $projectRepository->findOneBy(['id' => $project_id]);
        return $this->render('analitics/index.html.twig', [
            'analitics' => $analiticsRepository->findBy(['status' => true,'project'=>$project]),
            'project' => $project
        ]);
    }

    /**
     * @Route("/project/{project_id}/analitics/new", name="analitics_new", methods={"GET","POST"})
     */
    public function new($project_id, ProjectRepository $projectRepository, Request $request): Response
    {
        $project = $projectRepository->findOneBy(['id' => $project_id]);
        $analitic = new Analitics();
        $form = $this->createForm(AnaliticsType::class, $analitic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('file')->getData();
            $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();

            try {
                $file->move(
                    $this->getParameter('upload_file'),
                    $fileName
                );
            } catch (FileException $e) {

            }


            $analitic->setFile($fileName);

            $date_start = \DateTime::createFromFormat('d.m.Y', $analitic->getDateStartStr());
            $analitic->setDateStart($date_start);

            $date_finish = \DateTime::createFromFormat('d.m.Y', $analitic->getDateFinishStr());
            $analitic->setDateFinish($date_finish);

            $analitic->setProject($project);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($analitic);
            $entityManager->flush();

            $file_content = file_get_contents(__DIR__ . '/../../public/uploads/file/' . $fileName);

            $this->saveProducts($file_content, $analitic);

            return $this->redirectToRoute('analitics', ['project_id' => $project_id]);
        }


        return $this->render('analitics/new.html.twig', [
            'analitic' => $analitic,
            'form' => $form->createView(),
            'project' => $project
        ]);
    }

    /**
     * @Route("/project/{project_id}/analitics/{id}", name="analitics_show", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function show($project_id, ProjectRepository $projectRepository, Analitics $analitic, OldProductRepository $oldProductRepository): Response
    {
        $project = $projectRepository->findOneBy(['id' => $project_id]);
        $categoryes = $analitic->getOldCategories();
        $active_category_id = $categoryes[0]->getId();

        $old_products = $oldProductRepository->findBy(['category' => $active_category_id]);

        $val_arr = AnaliticsUtil::setAbc($old_products);

        AnaliticsUtil::countPriceCat($old_products, $val_arr['mean_price']);
        AnaliticsUtil::setAbcFirstOtch($old_products, $val_arr);
        AnaliticsUtil::setAbcSecond($old_products, $val_arr);
        usort($old_products, 'App\Utility\mySortSale');

        return $this->render('analitics/show.html.twig', [
            'analitic' => $analitic,
            'categoryes' => $categoryes,
            'project' => $project,
            'old_products' => $old_products,
            'val_array' => $val_arr,
            'old_category_id' => false
        ]);
    }

    /**
     * @Route("/project/{project_id}/analitics/{id}/{old_category_id}", name="analitics_show_category",requirements={"id"="\d+", "old_category_id"="\d+"}, methods={"GET"})
     */
    public function showCategory($project_id, $old_category_id, ProjectRepository $projectRepository, Analitics $analitic, OldProductRepository $oldProductRepository): Response
    {
        $project = $projectRepository->findOneBy(['id' => $project_id]);
        $categoryes = $analitic->getOldCategories();
        $active_category_id = $old_category_id;

        $old_products = $oldProductRepository->findBy(['category' => $active_category_id]);

        $val_arr = AnaliticsUtil::setAbc($old_products);

        AnaliticsUtil::countPriceCat($old_products, $val_arr['mean_price']);
        AnaliticsUtil::setAbcFirstOtch($old_products, $val_arr);
        AnaliticsUtil::setAbcSecond($old_products, $val_arr);
        usort($old_products, 'App\Utility\mySortSale');
        return $this->render('analitics/show.html.twig', [
            'analitic' => $analitic,
            'categoryes' => $categoryes,
            'project' => $project,
            'old_products' => $old_products,
            'val_array' => $val_arr,
            'old_category_id' => $old_category_id
        ]);
    }

    /**
     * @Route("/ajax/project/{project_id}/analitics/{id}/{old_category_id}", name="analitics_ajax", methods={"GET"})
     */
    public function showAjax($project_id, $old_category_id, ProjectRepository $projectRepository, Analitics $analitic, OldProductRepository $oldProductRepository): Response
    {

        $project = $projectRepository->findOneBy(['id' => $project_id]);
        $categoryes = $analitic->getOldCategories();
        $active_category_id = $old_category_id;

        $old_products = $oldProductRepository->findBy(['category' => $active_category_id]);

        try {
            $val_arr = AnaliticsUtil::setAbc($old_products);
        } catch (Exception $ex) {
            return $this->render('analitics/_abc_table.html.twig');
        }

        AnaliticsUtil::countPriceCat($old_products, $val_arr['mean_price']);
        AnaliticsUtil::setAbcFirstOtch($old_products, $val_arr);
        AnaliticsUtil::setAbcSecond($old_products, $val_arr);
        return $this->render('analitics/_abc_table.html.twig', [
            'analitic' => $analitic,
            'categoryes' => $categoryes,
            'project' => $project,
            'old_products' => $old_products,
            'val_array' => $val_arr
        ]);

    }
    /**
     * @Route("project/{project_id}/analitics/svodka/{analitics_id}", name="analitics_svodka", requirements={"project_id"="\d+", "analitics_id"="\d+"}, methods={"GET"})
     */
    public function svodka($project_id, $analitics_id, ProjectRepository $projectRepository, AnaliticsRepository $analiticsRepository): Response
    {
        $project = $projectRepository->findOneBy(['id' => $project_id]);
        $analitic = $analiticsRepository->findOneBy(['id' => $analitics_id]);

        $categoryes = $analitic->getOldCategories();

        AnaliticsUtil::countSummary($categoryes);

        return $this->render('analitics/show_svodka.html.twig', [
            'categoryes' => $categoryes,
            'project' =>$project,
            'analitic' => $analitic,
            'old_category_id' => false
        ]);

    }

    /**
     * @Route("ajax/svodka/{id}", name="analitics_svodka_ajax", methods={"GET"})
     */
    public function svodkaAjax(ProjectRepository $projectRepository, Analitics $analitic, OldProductRepository $oldProductRepository): Response
    {

        $categoryes = $analitic->getOldCategories();

        AnaliticsUtil::countSummary($categoryes);

        return $this->render('analitics/_svodka.html.twig', [
            'categoryes' => $categoryes
        ]);
    }

    /**
     * @Route("project/{project_id}/analitics_edit/{id}", name="analitics_edit", methods={"GET","POST"})
     */
    public function edit($project_id, ProjectRepository $projectRepository, Request $request, Analitics $analitic): Response
    {
        $project = $projectRepository->findOneBy(['id' => $project_id]);
        $form = $this->createForm(Analitics2Type::class, $analitic);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('analitics', [
                'id' => $analitic->getId(),
                'project_id' => $project_id
            ]);
        }

        return $this->render('analitics/edit.html.twig', [
            'analitic' => $analitic,
            'form' => $form->createView(),
            'project' =>$project,
        ]);
    }

    /**
     * @Route("ajax/{project_id}/analitics/hide/{id}", name="analitics_hide")
     */
    public function hide($project_id, ProjectRepository $projectRepository, Request $request, Analitics $analitic): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        if ($analitic->getStatus()){
            $status = false;
            $string_status = 0;
        } else {
            $status = true;
            $string_status = 1;
        }

        $analitic->setStatus($status);
        $entityManager->persist($analitic);
        $entityManager->flush();

        return new Response($string_status);
    }

    /**
     * @Route("project/{project_id}/analitics/{id}", name="analitics_delete", methods={"DELETE"})
     */
    public function delete($project_id, ProjectRepository $projectRepository, Request $request, Analitics $analitic): Response
    {
        if ($this->isCsrfTokenValid('delete' . $analitic->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($analitic);
            $entityManager->flush();
        }

        return $this->redirectToRoute('analitics', ['project_id' => $project_id]);
    }

    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

    //todo вынести это в отдельный класс
    public function saveProducts($file_contents,
                                 Analitics $analitics)
    {
        $f_array_str = explode("\r", $file_contents);

        //delete last string if it empty
        $last_key = count($f_array_str) - 1;
        if ($f_array_str[$last_key] == '') {
            unset($f_array_str[$last_key]);
        }

        //get one product from file
        foreach ($f_array_str as $key => $value) {

            if ($key == 0) continue;

            $array_value[$key] = explode(";", $f_array_str[$key]);
            $group = $array_value[$key][0];
            $array_for_group[$group][] = $array_value[$key];

        }
        $doctrin = $this->getDoctrine();
        //перебираем весь массив чтобы сохранить
        foreach ($array_for_group as $cat_name => $cat_arr) {

            $old_category = new OldCategory();
            $old_category->setName($cat_name);
            $old_category->setAnalitics($analitics);

            $entityManager = $doctrin->getManager();
            $entityManager->persist($old_category);


            foreach ($cat_arr as $key_tov => $tov_arr) {

                $name_tov = $tov_arr[1];
                $sale = str_replace(",", '.', $tov_arr[2]);
                $sale = (float)preg_replace("/[^x\d|*\.]/", "", $sale);
                $price = str_replace(",", '.', $tov_arr[3]);
                $price = (float)preg_replace("/[^x\d|*\.]/", "", $price);
                $cost_price = str_replace(",", '.', $tov_arr[4]);
                $cost_price = (float)preg_replace("/[^x\d|*\.]/", "", $cost_price);


                $old_product = new OldProduct();

                $old_product->setName($name_tov);
                $old_product->setCategory($old_category);
                $old_product->setPrice($price);
                $old_product->setcostPrice($cost_price);
                $old_product->setSale($sale);

                $entityManager->persist($old_product);

            }

        }

        $entityManager->flush();
    }

}
