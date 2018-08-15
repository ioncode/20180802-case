<?php
/**
 * Created by PhpStorm.
 * User: Андрей Сергеевич
 * Date: 09.08.2018
 * Time: 15:55
 */

namespace App\ArgumentResolver;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Security\Core\Security;

class UserValueResolver implements ArgumentValueResolverInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function supports(Request $request, ArgumentMetadata $argument)
    {
        if (User::class !== $argument->getType()) {
            return false;
        }

        return $this->security->getUser() instanceof User;
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        yield $this->security->getUser();
    }
}