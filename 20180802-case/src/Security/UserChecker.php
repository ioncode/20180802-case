<?php
/**
 * Created by PhpStorm.
 * User: Андрей Сергеевич
 * Date: 20.08.2018
 * Time: 16:02
http://symfony.com/doc/current/security/user_checkers.html*/
//http://symfony.com/doc/current/security/user_checkers.html
namespace App\Security {

    use App\Entity\User as AppUser;
    use Symfony\Component\Security\Core\Exception\AccountStatusException;
    use Symfony\Component\Security\Core\User\UserCheckerInterface;
    use Symfony\Component\Security\Core\User\UserInterface;

    class UserChecker implements UserCheckerInterface
    {

        /**
         * Checks the user account before authentication.
         *
         * @throws AccountStatusException
         */
        public function checkPreAuth(UserInterface $user)
        {
            dump([__CLASS__, $_COOKIE, $user, AppUser::class]);
            if (!$user instanceof AppUser) {
                return;
            }
        }

        /**
         * Checks the user account after authentication.
         *
         * @throws AccountStatusException
         */
        public function checkPostAuth(UserInterface $user)
        {
            if (!$user instanceof AppUser) {
                return;
            }
        }
    }
}