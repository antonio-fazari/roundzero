<?php
namespace RoundZero\Service;

class Membership
{
    protected $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function findById($id)
    {
        $sql = 'SELECT * FROM memberships WHERE id  = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($id));
        $result = $stmt->fetch();

        $this->addInfo($result);

        return $result;
    }

    public function insert($membership)
    {
        $sql = 'INSERT INTO memberships SET userId = ?, groupId = ?, joined = NOW()';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($membership->userId, $membership->groupId));
        return $this->db->lastInsertId();
    }

    public function delete($id)
    {
        $sql = 'DELETE FROM memberships WHERE id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($id));
        return $stmt->rowCount();
    }

    public function findAllForUser($id)
    {
        $sql = 'SELECT * FROM memberships WHERE userId  = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($id));
        $results = $stmt->fetchAll();

        $groupService = new Group($this->db);

        foreach ($results as $i => $result) {
            $this->addInfo($result);
        }

        return $results;
    }

    public function findAllForGroup($id)
    {
        $sql = 'SELECT * FROM memberships WHERE groupId  = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($id));
        $results = $stmt->fetchAll();

        foreach ($results as $i => $result) {
            $this->addInfo($result);
        }

        return $results;
    }

    public function countOrdersReceived($userId, $groupId)
    {
        $sql = 'SELECT COUNT(*) FROM rounds
            INNER JOIN orders ON orders.roundId = rounds.id
            WHERE orders.userId = ? AND groupId = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($userId, $groupId));
        return (int) $stmt->fetchColumn();
    }

    public function countOrdersMade($userId, $groupId)
    {
        $sql = 'SELECT COUNT(*) FROM rounds
            INNER JOIN orders ON orders.roundId = rounds.id
            WHERE rounds.userId = ? AND groupId = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($userId, $groupId));
        return (int) $stmt->fetchColumn();
    }

    protected function addInfo($result)
    {
        $userService = new User($this->db);
        $orderService = new Order($this->db);

        $result->user = $userService->findById($result->userId);
        $result->made = $this->countOrdersMade($result->userId, $result->groupId);
        $result->received = $this->countOrdersReceived($result->userId, $result->groupId);
        $result->balance = $result->made - $result->received;
        $result->lastOrder = $orderService->findLastForUser($result->userId);
    }
}
