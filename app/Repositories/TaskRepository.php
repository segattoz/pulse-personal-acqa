<?php
declare(strict_types=1);
namespace App\Repositories;

use App\Core\Database;

final class TaskRepository
{
    public function all(int $user): array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM tasks WHERE user_id=? ORDER BY completed,id DESC');
        $stmt->execute([$user]);
        return $stmt->fetchAll();
    }
    public function create(int $user, string $title): void
    {
        Database::connection()->prepare('INSERT INTO tasks (user_id,title) VALUES (?,?)')->execute([$user,$title]);
    }
    public function toggle(int $id, int $user): void
    {
        Database::connection()->prepare('UPDATE tasks SET completed=1-completed WHERE id=? AND user_id=?')->execute([$id,$user]);
    }
    public function delete(int $id, int $user): void
    {
        Database::connection()->prepare('DELETE FROM tasks WHERE id=? AND user_id=?')->execute([$id,$user]);
    }
}
