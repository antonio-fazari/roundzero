<?php
namespace RoundZero\Service;

class Group
{
    protected $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        $sql = 'SELECT * FROM groups';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function findById($id, $detailed = false)
    {
        $sql = 'SELECT * FROM groups WHERE id  = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($id));
        $group = $stmt->fetch();

        if ($detailed) {
            $membershipService = new Membership($this->db);
            $group->memberships = $membershipService->findAllForGroup($group->id);
        }
        return $group;
    }

    public function insert($group)
    {
        $sql = 'INSERT INTO groups SET created = NOW(), changed = NOW(), name = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($group->name));
        return $this->db->lastInsertId();
    }

    public function update($group)
    {
        $sql = 'UPDATE groups SET changed = NOW(), name = ? WHERE id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($group->name, $group->id));
        return $stmt->rowCount();
    }

    public function delete($id)
    {
        $sql = 'DELETE FROM groups WHERE id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($id));
        return $stmt->rowCount();
    }
}
