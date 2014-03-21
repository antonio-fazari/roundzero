<?php
/**
 * @Entity @Table(name="users")
 */
class User
{
    /**
     * @Id @Column(type="integer") @GeneratedValue 
     * @var int
     */
    protected $id;

    /**
     * @Column(type="string")
     * @var string
     */
    protected $name;

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

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setCreated(\DateTime $created)
    {
        $this->created = $created;
    }

    public function toArray()
    {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'created' => $this->created->format(\DateTime::ISO8601),
        );
    }
}
