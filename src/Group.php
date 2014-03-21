<?php
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
     * @Column(type="string")
     * @var  string
     */
    protected $name;

    /**
     * @ManyToMany(targetEntity="User")
     * @var Users[]
     */
    protected $members;

    public function __construct()
    {
        $this->members = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
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

    public function toArray()
    {
        return array(
            'id' => $this->id,
            'name' => $this->name,
        );
    }
}
