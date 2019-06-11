<?php

namespace App\DataFixtures;

use App\Entity\Project;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;
    /**
     * @var UserPasswordEncoderInterface
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder) {
       $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $this->loadUsers($manager);
        $this->loadProjects($manager);

    }
    public function loadProjects(ObjectManager $manager){
        for($i = 0; $i<10;$i++){
            $project = new Project();
            $project->setProjectName('some project_'.rand(1,434));
            $project->setCompanyId(rand(1,434));
            $project->setKitchenType(rand(1,434));
            $project->setTypeMenu(rand(1,3));
            $project->setOldMenuId(rand(1,334));
            $project->setDateCreate(new \DateTime(2003-12-21));
            $project->setHash(rand(1,334));
            $manager->persist($project);
        }

        $manager->flush();
    }

    public function loadUsers(ObjectManager $manager){
        $user = new User();
        $user->setEmail('test@email.ru');
        $user->setName('Test Testovich');
        $user->setPassword($this->passwordEncoder->encodePassword($user,'test'));
        $user->setDateCreate(new \DateTime('2003-12-21'));
        $user->setStatus(true);
        $manager->persist($user);
        $manager->flush();
    }
}
