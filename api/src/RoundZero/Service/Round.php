<?php
namespace RoundZero\Service;

class Round
{
    /**
     * Database connection
     * @var PDO
     */
    protected $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function findById($id)
    {
        $sql = 'SELECT * FROM rounds WHERE id  = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($id));
        $round = $stmt->fetch();

        $userService = new User($this->db);
        $groupService = new Group($this->db);
        $orderService = new Order($this->db);
        $round->user = $userService->findById($round->userId);
        $round->group = $groupService->findById($round->groupId);
        $round->orders = $orderService->findAllForRound($round->id);
        return $round;
    }

    public function insert($round)
    {
        $sql = 'INSERT INTO rounds
            SET created = NOW(), changed = NOW(), groupId = ?, userId = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
            $round->groupId,
            $round->userId,
        ));
        return $this->db->lastInsertId();
    }

    public function delete($id)
    {
        $sql = 'DELETE FROM rounds WHERE id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($id));
        return $stmt->rowCount();
    }
}
