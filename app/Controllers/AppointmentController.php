<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Appointment;
use App\Repositories\AppointmentRepository;

final class AppointmentController extends Controller
{
    public function index(): void
    {
        $search = mb_substr(trim($_GET['q'] ?? ''),0,100);
        $status = $_GET['status'] ?? '';
        if (!in_array($status, Appointment::STATUSES,true)) $status = '';
        $appointments = (new AppointmentRepository())->all($this->userId(),$search,$status);
        $this->view('appointments/index', ['title'=>'Agendamentos'] + compact('appointments','search','status'));
    }

    public function form(): void
    {
        $appointment = ['student'=>'','type'=>'Musculação','date'=>date('Y-m-d'),'time'=>'08:00','duration'=>60,'status'=>'Agendado','notes'=>''];
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $row = (new AppointmentRepository())->find($id,$this->userId());
            if (!$row) $this->notFound();
            $appointment = $row + ['date'=>substr($row['scheduled_at'],0,10),'time'=>substr($row['scheduled_at'],11,5)];
        }
        $this->view('appointments/form', ['title'=>$id?'Editar agendamento':'Novo agendamento'] + compact('appointment','id'));
    }

    public function save(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $repo = new AppointmentRepository();
        if ($id && !$repo->find($id,$this->userId())) $this->notFound();
        $appointment = array_intersect_key($_POST,array_flip(['student','type','date','time','duration','status','notes']));
        $errors = Appointment::validate($appointment);
        if (!$errors) {
            try { $repo->save($appointment,$this->userId(),$id ?: null); }
            catch (\DomainException $error) { $errors[] = $error->getMessage(); }
        }
        if ($errors) { http_response_code(422); $this->view('appointments/form',['title'=>$id?'Editar agendamento':'Novo agendamento'] + compact('appointment','id','errors')); return; }
        flash($id?'Agendamento atualizado com sucesso.':'Agendamento criado com sucesso.');
        redirect('appointments');
    }

    public function delete(): void
    {
        $repo = new AppointmentRepository();
        $id = (int)($_POST['id'] ?? 0);
        if (!$repo->find($id,$this->userId())) $this->notFound();
        $repo->delete($id,$this->userId());
        flash('Agendamento excluído.');
        redirect('appointments');
    }
}
