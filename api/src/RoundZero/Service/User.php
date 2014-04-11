<?php
namespace RoundZero\Service;

class User
{
    protected $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        $sql = 'SELECT id, created, changed, name, email FROM users';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function findSuggestions($partial, $groupId = null)
    {
        $sql = "SELECT id, created, changed, name, email FROM users
                WHERE name LIKE CONCAT('%', ?, '%')";
        $params = array($partial);

        if ($groupId) {
            $sql .= ' AND id NOT IN (SELECT userId from memberships WHERE groupId = ?)';
            $params[] = $groupId;
        }

        $stmt = $this->db->query($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findByLogin($email, $password)
    {
        $sql = 'SELECT id, password, salt FROM users WHERE email  = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($email));
        $user = $stmt->fetch();

        if ($user && crypt($password, $user->salt) == $user->password) {
            return $this->findById($user->id);
        }
    }

    public function findByEmail($email)
    {
        $sql = 'SELECT id, created, changed, name, email FROM users WHERE email  = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($email));
        $user = $stmt->fetch();

        return $user;
    }

    public function findById($id, $detailed = false)
    {
        $sql = 'SELECT id, created, changed, name, email FROM users WHERE id  = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($id));
        $user = $stmt->fetch();

        if ($detailed) {
            $membershipService = new Membership($this->db);
            $user->memberships = $membershipService->findAllForUser($user->id);
        }

        return $user;
    }

    public function insert($user)
    {
        $this->hashPassword($user);

        $sql = 'INSERT INTO users
            SET created = NOW(), changed = NOW(), name = ?, email = ?, salt = ?, password = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
            $user->name,
            $user->email,
            $user->salt,
            $user->password,
        ));
        return $this->db->lastInsertId();
    }

    public function update($user)
    {
        if (!empty($user->password)) {
            $this->updatePassword($user);
        }
        $sql = 'UPDATE users SET changed = NOW(), name = ?, email = ? WHERE id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
            $user->name,
            $user->email,
            $user->id,
        ));
        return $stmt->rowCount();
    }


    public function updatePassword($user)
    {
        $this->hashPassword($user);
        $sql = 'UPDATE users SET salt = ?, password = ? WHERE id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
            $user->salt,
            $user->password,
            $user->id,
        ));
    }

    public function delete($id)
    {
        $sql = 'DELETE FROM users WHERE id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($id));
        return $stmt->rowCount();
    }

    public function hashPassword($user)
    {
        $user->salt = uniqid(mt_rand(), true);
        $user->password = crypt($user->password, $user->salt);
    }
}
