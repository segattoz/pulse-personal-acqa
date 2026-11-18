<?php
declare(strict_types=1);
require dirname(__DIR__) . '/app/bootstrap.php';

use App\Core\Database;
use App\Repositories\UserRepository;
use App\Repositories\AppointmentRepository;
use App\Repositories\TaskRepository;

$users = new UserRepository();
$email = 'demo@pulse.local';
if ($users->byEmail($email)) { echo "A conta de demonstração já existe. Nenhum dado foi alterado.\n"; exit; }
$id = $users->create('Matheus Segatto', $email, 'Pulse@2026');
$repo = new AppointmentRepository();
foreach ([
    ['Ana Oliveira','Musculação','07:00',60,'Concluído','Treino de membros inferiores.'],
    ['Lucas Ferreira','Funcional','09:00',45,'Agendado','Circuito de resistência.'],
    ['Beatriz Santos','Avaliação física','11:00',60,'Agendado','Reavaliação mensal e definição de metas.'],
    ['Rafael Costa','Mobilidade','15:00',30,'Agendado','Mobilidade de quadril e tornozelo.'],
] as [$student,$type,$time,$duration,$status,$notes]) {
    $repo->save(compact('student','type','time','duration','status','notes') + ['date'=>date('Y-m-d')],$id);
}
$repo->save(['student'=>'Camila Mendes','type'=>'Condicionamento','time'=>'08:00','duration'=>60,'status'=>'Agendado','notes'=>'Foco em resistência e consistência.','date'=>date('Y-m-d',strtotime('+1 day'))],$id);
$tasks = new TaskRepository();
foreach (['Revisar o treino da Ana','Preparar avaliação da Beatriz','Confirmar os horários de amanhã','Organizar os materiais do funcional'] as $title) $tasks->create($id,$title);
$all = $tasks->all($id);
$tasks->toggle((int)$all[0]['id'],$id);
echo "Demonstração criada. Login: demo@pulse.local | Senha: Pulse@2026\n";
