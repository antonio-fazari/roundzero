<?php
namespace RoundZero\Entity;

/**
 * @Entity @Table(name="orders")
 */
class Order extends Base
{
    /**
     * @ManyToOne(targetEntity="Round", inversedBy="orders")
     * @var Round
     */
    protected $round;

    /**
     * @ManyToOne(targetEntity="User")
     * @var User
     */
    protected $user;

    /**
     * @Column(type="string")
     * @var  string
     */
    protected $type;

    /**
     * @Column(type="integer")
     * @var  int
     */
    protected $sugars;

    /**
     * @Column(type="integer")
     * @var  int
     */
    protected $milk;

    /**
     * @Column(type="string")
     * @var  string
     */
    protected $notes;

    public function getRound()
    {
        return $this->round;
    }

    public function setRound(Round $round)
    {
        $this->round = $round;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getSugars()
    {
        return $this->sugars;
    }

    public function setSugars($sugars)
    {
        $this->sugars = $sugars;
    }

    public function getMilk()
    {
        return $this->milk;
    }

    public function setMilk($milk)
    {
        $this->milk = $milk;
    }

    public function getNotes()
    {
        return $this->notes;
    }

    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    public function toArray()
    {
        return parent::toArray() + array(
            'round' => $this->getRound()->getId(),
            'user' => $this->getUser()->getId(),
            'type' => $this->type,
            'sugars' => $this->sugars,
            'milk' => $this->milk,
            'notes' => $this->notes,
        );
    }
}
