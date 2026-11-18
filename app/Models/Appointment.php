<?php
declare(strict_types=1);
namespace App\Models;

final class Appointment
{
    public const TYPES = ['Musculação', 'Funcional', 'Avaliação física', 'Mobilidade', 'Condicionamento'];
    public const STATUSES = ['Agendado', 'Concluído', 'Cancelado'];

    public static function validate(array $data): array
    {
        $errors = [];
        if (mb_strlen(trim($data['student'] ?? '')) < 3 || mb_strlen($data['student'] ?? '') > 100) $errors[] = 'Informe o nome do aluno entre 3 e 100 caracteres.';
        if (!in_array($data['type'] ?? '', self::TYPES, true)) $errors[] = 'Selecione uma modalidade válida.';
        if (!in_array($data['status'] ?? '', self::STATUSES, true)) $errors[] = 'Selecione uma situação válida.';
        $date = \DateTimeImmutable::createFromFormat('!Y-m-d', $data['date'] ?? '');
        if (!$date || $date->format('Y-m-d') !== ($data['date'] ?? '') || $date->format('Y') < '2000' || $date->format('Y') > '2100') $errors[] = 'Informe uma data válida entre 2000 e 2100.';
        if (!preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/D', $data['time'] ?? '')) $errors[] = 'Informe um horário válido.';
        if (!in_array((int)($data['duration'] ?? 0), [30, 45, 60, 90], true)) $errors[] = 'Selecione uma duração válida.';
        if (mb_strlen($data['notes'] ?? '') > 1000) $errors[] = 'As observações devem ter até 1.000 caracteres.';
        return $errors;
    }
}
