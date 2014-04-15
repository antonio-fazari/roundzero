<?php
namespace RoundZero\Service;

class Order
{
    protected $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function findAllForRound($roundId)
    {
        $sql = 'SELECT * FROM orders WHERE roundId = ?';
        $stmt = $this->db->query($sql);
        $stmt->execute(array($roundId));
        $results = $stmt->fetchAll();

        $userService = new User($this->db);
        foreach ($results as $i => $result) {
            $results[$i]->user = $userService->findById($result->userId);
        }
        return $results;
    }

    public function findById($id)
    {
        $sql = 'SELECT * FROM orders WHERE id  = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($id));
        $order = $stmt->fetch();

        $userService = new User($this->db);
        $order->user = $userService->findById($order->userId);
        return $order;
    }

    public function findLastForUser($userId)
    {
        $sql = 'SELECT * FROM
            (SELECT * FROM orders WHERE userId  = ? ORDER BY created DESC)
            orders_sorted GROUP BY type';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($userId));
        return $stmt->fetchAll();
    }

    public function insert($order)
    {
        $sql = 'INSERT INTO orders 
            SET created = NOW(), changed = NOW(), roundId = ?, userId = ?, type = ?, sugars = ?, milk = ?, notes = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
            $order->roundId,
            $order->userId,
            $order->type,
            $order->sugars,
            $order->milk,
            $order->notes,
        ));
        return $this->db->lastInsertId();
    }

    public function update($order)
    {
        $sql = 'UPDATE orders
            SET changed = NOW(), roundId = ?, userId = ?, type = ?, sugars = ?, milk = ?, notes = ?
            WHERE id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
            $order->roundId,
            $order->userId,
            $order->type,
            $order->sugars,
            $order->milk,
            $order->notes,
        ));
        return $stmt->rowCount();
    }

    public function delete($id)
    {
        $sql = 'DELETE FROM orders WHERE id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($id));
        return $stmt->rowCount();
    }
}
