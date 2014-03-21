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

    public function toArray()
    {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created' => $this->created->format(\DateTime::ISO8601),
        );
    }

    public function getTotalMadeForGroup(Group $group)
    {
        $query = $em->createQuery(
            'SELECT COUNT(u.id) 
            FROM RoundZero\Entity\Round r 
            JOIN r.recipients u
            WHERE r.creator = :creator
            AND r.group = :group'
        );
        $query->setParameter('creator', $user);
        $query->setParameter('group', $group);
        $count = $query->getSingleScalarResult();
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

    public function getTotalReceivedFromGroup(Group $group)
    {
        /*
        SELECT COUNT(*) FROM rounds
        INNER JOIN round_user ON rounds.id = round_user.round_id
        WHERE round_user.user_id = ?
        AND rounds.group_id = ?
         */
    }

    public function getBalanceForGroup(Group $group)
    {
        return $this->getTotalReceivedFromGroup($group) - $this->getTotalMadeForGroup($group);
    }
}
