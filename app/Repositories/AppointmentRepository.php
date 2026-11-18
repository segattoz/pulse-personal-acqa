<?php
declare(strict_types=1);
namespace App\Repositories;

use App\Core\Database;

/** Repository: todo acesso SQL aos agendamentos fica concentrado aqui. */
final class AppointmentRepository
{
    public function all(int $user, string $search = '', string $status = ''): array
    {
        $sql = 'SELECT * FROM appointments WHERE user_id = ?';
        $params = [$user];
        if ($search !== '') { $sql .= ' AND (student LIKE ? OR type LIKE ?)'; $params[] = "%$search%"; $params[] = "%$search%"; }
        if ($status !== '') { $sql .= ' AND status = ?'; $params[] = $status; }
        $stmt = Database::connection()->prepare($sql . ' ORDER BY scheduled_at, id');
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function find(int $id, int $user): array|false
    {
        $stmt = Database::connection()->prepare('SELECT * FROM appointments WHERE id = ? AND user_id = ?');
        $stmt->execute([$id, $user]);
        return $stmt->fetch();
    }

    public function save(array $data, int $user, ?int $id = null): void
    {
        $db = Database::connection();
        // Serializa gravações do mesmo personal para impedir conflitos concorrentes.
        $db->beginTransaction();
        try {
            $lock = $db->prepare('SELECT id FROM users WHERE id = ? FOR UPDATE');
            $lock->execute([$user]);
            $scheduled = $data['date'] . ' ' . $data['time'] . ':00';
            if ($data['status'] !== 'Cancelado') {
                $end = date('Y-m-d H:i:s', strtotime($scheduled) + (int)$data['duration'] * 60);
                $conflict = $db->prepare("SELECT id FROM appointments WHERE user_id = ? AND status <> 'Cancelado' AND id <> ? AND scheduled_at < ? AND DATE_ADD(scheduled_at, INTERVAL duration MINUTE) > ? LIMIT 1");
                $conflict->execute([$user, $id ?? 0, $end, $scheduled]);
                if ($conflict->fetch()) throw new \DomainException('Você já tem um atendimento neste intervalo. Escolha outro horário.');
            }
            $values = [trim($data['student']), $data['type'], $scheduled, (int)$data['duration'], $data['status'], trim($data['notes'] ?? '')];
            if ($id) {
                $stmt = $db->prepare('UPDATE appointments SET student=?,type=?,scheduled_at=?,duration=?,status=?,notes=? WHERE id=? AND user_id=?');
                $stmt->execute([...$values, $id, $user]);
            } else {
                $stmt = $db->prepare('INSERT INTO appointments (student,type,scheduled_at,duration,status,notes,user_id) VALUES (?,?,?,?,?,?,?)');
                $stmt->execute([...$values, $user]);
            }
            $db->commit();
        } catch (\Throwable $error) { if ($db->inTransaction()) $db->rollBack(); throw $error; }
    }

    public function delete(int $id, int $user): void
    {
        $stmt = Database::connection()->prepare('DELETE FROM appointments WHERE id = ? AND user_id = ?');
        $stmt->execute([$id, $user]);
    }
}
