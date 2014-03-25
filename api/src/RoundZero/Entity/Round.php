<?php
namespace RoundZero\Entity;

/**
 * @Entity @Table(name="rounds")
 */
class Round extends Base
{
    /**
     * @ManyToOne(targetEntity="Group", inversedBy="rounds")
     * @var Group
     */
    protected $group;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="rounds")
     * @var User
     */
    protected $creator;

    /**
     * @OneToMany(targetEntity="Order", mappedBy="round")
     * @var Order[]
     */
    protected $orders;

    public function __construct()
    {
        $this->orders = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function getOrders()
    {
        return $this->orders;
    }

    public function addOrder(Order $order)
    {
        $this->orders[] = $order;
    }

    public function removeOrder(Order $order)
    {
        $this->orders->removeElement($order);
    }

    public function toArray()
    {
        return parent::toArray() + array(
            'creator' => $this->getCreator()->getId(),
            'group' => $this->getGroup()->getId(),
        );
    }
}
