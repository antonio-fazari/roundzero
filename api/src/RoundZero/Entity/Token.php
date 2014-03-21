<?php
namespace RoundZero\Entity;

use RoundZero\Entity\User;

/**
 * @Entity @Table(name="tokens")
 * @HasLifecycleCallbacks
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

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @PrePersist @PreUpdate
     */
    public function preSave()
    {
        $time = new \DateTime(date('Y-m-d H:i:s'));
        $this->created = $time;
        $this->id = bin2hex(openssl_random_pseudo_bytes(16));
    }
}
