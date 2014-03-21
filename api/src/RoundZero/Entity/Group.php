<?php
namespace RoundZero\Entity;

/**
 * @Entity @Table(name="groups")
 * @HasLifecycleCallbacks
 */
class Group extends Base
{
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
        return parent::toArray() + array(
            'name' => $this->name,
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
    }
}
