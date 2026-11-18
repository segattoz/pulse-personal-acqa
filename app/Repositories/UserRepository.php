<?php
declare(strict_types=1);
namespace App\Repositories;

use App\Core\Database;

final class UserRepository
{
    public function byEmail(string $email): array|false
    {
        $stmt = Database::connection()->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function create(string $name, string $email, string $password): int
    {
        $stmt = Database::connection()->prepare('INSERT INTO users (name,email,password) VALUES (?,?,?)');
        $stmt->execute([$name, $email, password_hash($password, PASSWORD_BCRYPT)]);
        return (int) Database::connection()->lastInsertId();
    }
}
