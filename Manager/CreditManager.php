<?php

namespace Eoko\CreditBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Eoko\CreditBundle\Entity\UserCredit;
use Eoko\CreditBundle\Exception\NoCreditException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CreditManager
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var int
     */
    protected $baseCredit;

    /**
     * CreditManager constructor.
     * @param $baseCredit
     * @param TokenStorageInterface $tokenStorage
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        $baseCredit,
        TokenStorageInterface $tokenStorage,
        EntityManagerInterface $entityManager
    ) {
        $this->baseCredit = $baseCredit;
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     * @param $credit
     * @throws NoCreditException
     */
    public function check(Request $request, $credit)
    {
        $id = $this->getUserId();
        $ip = $request->getClientIp();

        /** @var UserCredit $userCredit */
        $userCredit = $this->entityManager
            ->getRepository(UserCredit::class)
            ->findFromIpOrId($ip, $id)
        ;

        if ($userCredit && $userCredit->getCredit() <= 0) {
            throw new NoCreditException();
        }

        if (!$userCredit) {
            $userCredit = (new UserCredit())
                ->setUserId($id)
                ->setIp($ip)
                ->setCredit($this->baseCredit);
            $this->entityManager->persist($userCredit);
        }

        $newCredit = $userCredit->getCredit() - $credit;
        if ($newCredit < 0) {
            $newCredit = 0;
        }
        $userCredit->setCredit($newCredit);
        $this->entityManager->flush();
    }

    /**
     * @param Request $request
     * @param $credit
     */
    public function substract(Request $request, $credit)
    {
        $id = $this->getUserId();
        $ip = $request->getClientIp();

        /** @var UserCredit $userCredit */
        $userCredit = $this->entityManager
            ->getRepository(UserCredit::class)
            ->findFromIpOrId($ip, $id)
        ;

        if ($userCredit) {
            $newCredit = $userCredit->getCredit() - $credit;
            if ($newCredit < 0) {
                $newCredit = 0;
            }
            $userCredit->setCredit($newCredit);
        } else {
            $newUserCredit = (new UserCredit())
                ->setUserId($id)
                ->setIp($ip)
                ->setCredit($this->baseCredit - $credit);
            $this->entityManager->persist($newUserCredit);
        }

        $this->entityManager->flush();
    }

    /**
     * @param Request $request
     * @param $credit
     */
    public function add(Request $request, $credit)
    {
        $id = $this->getUserId();
        $ip = $request->getClientIp();

        /** @var UserCredit $userCredit */
        $userCredit = $this->entityManager
            ->getRepository(UserCredit::class)
            ->findFromIpOrId($ip, $id)
        ;

        if (!$userCredit) {
            $newUserCredit = (new UserCredit())
                ->setUserId($id)
                ->setIp($ip)
                ->setCredit($this->baseCredit + $credit);
            $this->entityManager->persist($newUserCredit);
        } else {
            $userCredit->addCredit($credit);
        }

        $this->entityManager->flush();
    }

    /**
     * @return int|null
     */
    protected function getUserId()
    {
        $user = $this->tokenStorage->getToken()->getUser();
        if (method_exists($user, 'getId')) {
            return $user->getId();
        }

        return null;
    }
}
