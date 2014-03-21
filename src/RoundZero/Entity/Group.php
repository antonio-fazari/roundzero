<?php
namespace RoundZero\Entity;

use RoundZero\Entity\Round;
use RoundZero\Entity\User;

/**
 * @Entity @Table(name="groups")
 */
class Group
{
    /**
     * @Id @Column(type="integer") @GeneratedValue
     * @var int
     */
    protected $id;

    /**
     * @Column(type="datetime")
     * @var \DateTime
     */
    protected $created;

    /**
     * @Column(type="string")
     * @var  string
     */
    protected $name;

    /**
     * @ManyToMany(targetEntity="User")
     * @var User[]
     */
    protected $members;

    /**
     * @OneToMany(targetEntity="Round", mappedBy="group")
     * @var Round[]
     */
    protected $rounds;

    public function __construct()
    {
        $this->members = new \Doctrine\Common\Collections\ArrayCollection();
    }

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

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getMembers()
    {
        return $this->members;
    }

    public function addMember(User $user)
    {
        $this->members[] = $user;
    }

    public function removeMember(User $user)
    {
        $this->members->removeElement($user);
    }

    public function getRounds()
    {
        return $this->rounds;
    }

    public function toArray()
    {
        return array(
            'id' => $this->id,
            'created' => $this->created->format(\DateTime::ISO8601),
            'name' => $this->name,
        );
    }
}
