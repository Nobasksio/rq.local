<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 09/04/2019
 * Time: 11:48
 */

namespace App\Security;


use App\Entity\Project;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;


class ProjectVoter extends Voter
{
    const EDIT = 'edit';
    const DELETE = 'delete';
    const READ = 'read';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute,[self::READ])){
            return false;
        }

        if (!$subject instanceof Project){
            return false;
        }
        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $authenticateduser = $token->getUser();

        if (!$authenticateduser instanceof User){
            return false;
        }

        /**
         * @var project $project
         */
        $project = $subject;

        $user_arr = $project->getUsers()->toArray();
        $user_id = $this->getUser()->getId();

        foreach($user_arr as $user){
            if ($user['id'] === $authenticateduser->getId()){
                return true;
            }
        }

        return false;
    }
}