<?php
namespace RoundZero\Entity;

use RoundZero\Entity\Group;
use RoundZero\Entity\User;

/**
 * @Entity @Table(name="rounds")
 */
class Round
{
    /**
     * @Id @Column(type="integer") @GeneratedValue 
     * @var  int
     */
    protected $id;

    /**
     * @Column(type="datetime")
     * @var \DateTime
     */
    protected $created;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="rounds")
     * @var User
     */
    protected $creator;

    /**
     * @ManyToOne(targetEntity="Group", inversedBy="rounds")
     * @var Group
     */
    protected $group;

    /**
     * @ManyToMany(targetEntity="User")
     * @var User[]
     */
    protected $recipients;

    public function __construct()
    {
        $this->recipients = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function getCreator()
    {
        return $this->creator;
    }

    public function setCreator(User $user)
    {
        $this->creator = $user;
    }

    public function getGroup()
    {
        return $this->group;
    }

    public function setGroup(Group $group)
    {
        $this->group = $group;
    }

    public function getRecipients()
    {
        return $this->recipients;
    }

    public function addRecipient(User $user)
    {
        $this->recipients[] = $user;
    }

    public function removeRecipient(User $user)
    {
        $this->recipients->removeElement($user);
    }

    public function toArray()
    {
        return array(
            'id' => $this->id,
            'created' => $this->created->format(\DateTime::ISO8601),
            'creator' => $this->getCreator()->getId(),
            'group' => $this->getGroup()->getId(),
        );
    }
}
