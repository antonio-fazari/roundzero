<?php
namespace RoundZero\Entity;

/**
 * @HasLifecycleCallbacks
 */
abstract class Base
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
     * @Column(type="datetime")
     * @var \DateTime
     */
    protected $changed;

    public function getId()
    {
        return $this->id;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getChanged()
    {
        return $this->changed;
    }

    public function toArray()
    {
        return array(
            'id' => $this->id,
            'created' => $this->created->format(\DateTime::ISO8601),
            'changed' => $this->changed->format(\DateTime::ISO8601),
        );
    }

    /**
     * @PrePersist @PreUpdate
     */
    public function validate()
    {
    }

    /**
     * @PrePersist @PreUpdate
     */
    public function updateTime()
    {
        $time = new \DateTime(date('Y-m-d H:i:s'));
        if (!$this->created) {
            $this->created = $time;
        }
        $this->changed = $time;
    }
}
