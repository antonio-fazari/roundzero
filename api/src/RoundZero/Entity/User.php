<?php
namespace RoundZero\Entity;

/**
 * @Entity @Table(name="users")
 * @HasLifecycleCallbacks
 */
class User extends Base
{
    /**
     * @Column(type="string")
     * @var string
     */
    protected $name;

    /**
     * @Column(type="string")
     * @var string
     */
    protected $password;

    /**
     * @Column(type="string")
     * @var string
     */
    protected $salt;

    /**
     * @Column(type="string")
     * @var string
     */
    protected $email;

    /**
     * @Column(type="datetime")
     * @var \DateTime
     */
    protected $created;

    /**
     * @OneToMany(targetEntity="Round", mappedBy="creator")
     * @var Round[]
     */
    protected $rounds;

    /**
     * @ManyToMany(targetEntity="Group", mappedBy="members")
     * @var Group[]
     */
    protected $groups;

    public function __construct()
    {
        $this->groups = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($password)
    {
        $this->salt = uniqid(mt_rand(), true);
        $this->password = sha1($this->salt . $password);
    }

    public function authenticate($password)
    {
        return $this->password == sha1($this->salt . $password);
    }

    public function toArray()
    {
        return parent::toArray() + array(
            'name' => $this->name,
            'email' => $this->email,
        );
    }

    /**
     * @PrePersist @PreUpdate
     */
    public function validate()
    {
        if ($this->name == null) {
            throw new ValidateException('Name is required');
        }
        if ($this->email == null) {
            throw new ValidateException('Email is required');
        }
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new ValidateException('Invalid email address');
        }
    }
}
