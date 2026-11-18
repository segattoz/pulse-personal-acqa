<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\AppointmentRepository;
use App\Repositories\TaskRepository;

final class DashboardController extends Controller
{
    public function index(): void
    {
        $appointments = (new AppointmentRepository())->all($this->userId());
        $tasks = (new TaskRepository())->all($this->userId());
        $today = array_values(array_filter($appointments, fn($a) => substr($a['scheduled_at'],0,10) === date('Y-m-d') && $a['status'] !== 'Cancelado'));
        $students = count(array_unique(array_column($appointments,'student')));
        $completed = count(array_filter($appointments, fn($a) => $a['status'] === 'Concluído'));
        $this->view('dashboard', ['title'=>'Visão geral'] + compact('appointments','tasks','today','students','completed'));
    }

    public function task(): void
    {
        $repo = new TaskRepository();
        $action = $_POST['action'] ?? 'create';
        if ($action === 'toggle') $repo->toggle((int)($_POST['id'] ?? 0), $this->userId());
        elseif ($action === 'delete') $repo->delete((int)($_POST['id'] ?? 0), $this->userId());
        else {
            $title = trim($_POST['title'] ?? '');
            if (mb_strlen($title)<3 || mb_strlen($title)>180) { flash('A tarefa deve ter entre 3 e 180 caracteres.','error'); redirect('dashboard'); }
            $repo->create($this->userId(), $title);
            flash('Tarefa adicionada.');
        }
        redirect('dashboard');
    }
}
