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
        $sql = 'SELECT memberships.*, MAX(rounds.created) lastRoundCreated FROM memberships
            LEFT JOIN rounds ON rounds.userId = memberships.userId AND rounds.groupId = memberships.groupId
            WHERE memberships.id = ?
            GROUP BY memberships.id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($id));
        $result = $stmt->fetch();

        $this->addInfo($result, true, true);

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
        $sql = 'SELECT memberships.*, MAX(rounds.created) lastRoundCreated FROM memberships
            LEFT JOIN rounds ON rounds.userId = memberships.userId AND rounds.groupId = memberships.groupId
            WHERE memberships.userId = ?
            GROUP BY memberships.id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($id));
        $results = $stmt->fetchAll();

        foreach ($results as $i => $result) {
            $this->addInfo($result, false, true);
        }

        return $results;
    }

    public function findAllForGroup($id)
    {
        $sql = 'SELECT memberships.*, MAX(rounds.created) lastRoundCreated FROM memberships
            LEFT JOIN rounds ON rounds.userId = memberships.userId AND rounds.groupId = memberships.groupId
            WHERE memberships.groupId = ?
            GROUP BY memberships.id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($id));
        $results = $stmt->fetchAll();

        foreach ($results as $i => $result) {
            $this->addInfo($result, true, false);
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

    protected function addInfo($result, $includeUser = false, $includeGroup = false)
    {
        $result->made = $this->countOrdersMade($result->userId, $result->groupId);
        $result->received = $this->countOrdersReceived($result->userId, $result->groupId);
        $result->balance = $result->made - $result->received;

        $orderService = new Order($this->db);
        $result->lastOrders = $orderService->findLastForUser($result->userId);

        if ($includeUser) {
            $userService = new User($this->db);
            $result->user = $userService->findById($result->userId);
        }

        if ($includeGroup) {
            $groupService = new Group($this->db);
            $result->group = $groupService->findById($result->groupId);
        }
    }
}
