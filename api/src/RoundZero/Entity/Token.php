<?php
namespace RoundZero\Entity;

use RoundZero\Entity\User;

/**
 * @Entity @Table(name="tokens")
 */
class Token
{
    /**
     * @Id @Column(type="string")
     * @var int
     */
    protected $id;

    /**
     * @Column(type="datetime")
     * @var \DateTime
     */
    protected $created;

    /**
     * @ManyToOne(targetEntity="User")
     * @var User
     */
    protected $user;

    public function getId()
    {
        return $this->id;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setCreated(\DateTime $created)
    {
        $this->created = $created;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function generateId()
    {
        $this->id = bin2hex(openssl_random_pseudo_bytes(16));
    }
}
