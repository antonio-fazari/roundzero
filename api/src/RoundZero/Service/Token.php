<?php
namespace RoundZero\Service;

class Token
{
    protected $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function create($userId)
    {
        $this->db->query('DELETE FROM tokens WHERE userId = ?');

        $id = bin2hex(openssl_random_pseudo_bytes(16));
        $sql = 'INSERT INTO tokens SET id = ?, userId = ?, created = NOW()';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($id, $userId));

        return $id;
    }

    public function findById($id)
    {
        $sql = 'SELECT * FROM tokens WHERE id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($id));
        $token = $stmt->fetch();

        $userService = new User($this->db);
        $token->user = $userService->findById($token->userId, true);
        return $token;
    }

    public function delete($id)
    {
        $sql = 'DELETE FROM tokens WHERE id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($id));
        return $stmt->rowCount();
    }
}
