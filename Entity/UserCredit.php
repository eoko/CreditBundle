<?php

namespace Eoko\CreditBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class UserCredit
 * @package Eoko\CreditBundle\Entity
 *
 * @ORM\Table(name="user_credit")
 * @ORM\Entity(repositoryClass="Eoko\CreditBundle\Repository\UserCreditRepository")
 */
class UserCredit
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var integer
     * @ORM\Column(name="user_id", type="integer", nullable=true)
     */
    protected $user_id;

    /**
     * @var string
     * @ORM\Column(name="ip", type="string", nullable=false)
     */
    protected $ip;

    /**
     * @var integer
     * @ORM\Column(name="credit", type="integer")
     */
    protected $credit;

    /**
     * UserCredit constructor.
     */
    public function __construct()
    {
        $this->credit = 0;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return UserCredit
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     * @return UserCredit
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     * @return UserCredit
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * @return int
     */
    public function getCredit()
    {
        return $this->credit;
    }

    /**
     * @param int $credit
     * @return UserCredit
     */
    public function setCredit($credit)
    {
        $this->credit = $credit;

        return $this;
    }

    /**
     * @param $credit
     * @return $this
     */
    public function addCredit($credit)
    {
        $this->credit += $credit;

        return $this;
    }
}
