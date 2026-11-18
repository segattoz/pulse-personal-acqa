import assert from 'node:assert/strict';
import { randomBytes } from 'node:crypto';
import { execFileSync } from 'node:child_process';
import { existsSync } from 'node:fs';

const base = process.env.TEST_URL || 'http://127.0.0.1:8080';
const prefix = 'acqa-test-' + randomBytes(6).toString('hex');
let checks = 0;
function check(condition, message) { assert.ok(condition, message); console.log('OK ' + (++checks) + ' · ' + message); }
class Client {
  cookie = ''; token = ''; cookieOptions = '';
  async request(route, data) {
    const res = await fetch(`${base}/index.php?route=${route}`, {
      method: data ? 'POST' : 'GET', redirect: 'manual',
      headers: { ...(this.cookie ? {cookie:this.cookie} : {}), ...(data ? {'Content-Type':'application/x-www-form-urlencoded'} : {}) },
      body: data ? new URLSearchParams({_token:this.token,...data}) : undefined,
    });
    const cookie = res.headers.getSetCookie().find(c=>c.startsWith('pulse_session='));
    if (cookie) { this.cookie = cookie.split(';')[0]; this.cookieOptions = cookie; }
    const html = await res.text();
    this.token = html.match(/name="_token" value="([^"]+)"/)?.[1] || this.token;
    return {status:res.status, html, location:res.headers.get('location'),headers:res.headers};
  }
}
const a=new Client(),b=new Client(),guest=new Client();
const pwd='Testing@2026';
let passed=false;
try {
  let r=await guest.request('dashboard'); check(r.status===302 && r.location.includes('login'),'Dashboard exige autenticação');
  r=await guest.request('login'); check(r.status===200 && guest.token.length===64,'Login renderiza e gera CSRF');
  check(guest.cookieOptions.includes('HttpOnly') && guest.cookieOptions.includes('SameSite=Lax'),'Cookie possui HttpOnly e SameSite');
  r=await guest.request('login',{email:'naoexiste@teste.local',password:pwd}); check(r.status===422 && r.html.includes('E-mail ou senha incorretos'),'Login inválido é rejeitado');
  r=await a.request('register');
  r=await a.request('register',{name:'Teste',email:'invalido',password:'123',password_confirmation:'456'}); check(r.status===422,'Cadastro inválido é rejeitado');
  r=await a.request('register',{name:'Personal Teste A',email:`${prefix}-a@teste.local`,password:pwd,password_confirmation:pwd}); check(r.status===302 && r.location.includes('dashboard'),'Cadastro válido autentica o usuário');
  await a.request('dashboard');
  r=await a.request('appointment-save',{_token:'invalido'}); check(r.status===419,'CSRF inválido é rejeitado');
  r=await a.request('tasks',{title:'<script>alert(1)</script>'}); check(r.status===302,'Tarefa pode ser cadastrada');
  r=await a.request('dashboard'); check(r.html.includes('&lt;script&gt;alert(1)&lt;/script&gt;') && !r.html.includes('<script>alert(1)</script>'),'Texto de tarefa é escapado contra XSS');
  const taskId=r.html.match(/name="id" value="(\d+)"/)[1];
  await a.request('tasks',{id:taskId,action:'toggle'});
  r=await a.request('dashboard'); check(r.html.includes('is-completed'),'Tarefa pode ser concluída');
  await a.request('tasks',{id:taskId,action:'toggle'});
  r=await a.request('dashboard'); check(!r.html.includes('task-row is-completed'),'Tarefa pode ser reaberta');
  const data={student:'Aluno Teste',type:'Musculação',date:'2027-04-10',time:'10:00',duration:'60',status:'Agendado',notes:'Treino de teste'};
  r=await a.request('appointment-save',{...data,date:'2027-02-31'}); check(r.status===422,'Datas impossíveis são rejeitadas');
  r=await a.request('appointment-save',{...data,duration:'12'}); check(r.status===422,'Duração não permitida é rejeitada');
  r=await a.request('appointment-save',data); check(r.status===302,'CRUD: inserção de agendamento');
  r=await a.request('appointments'); check(r.html.includes('Aluno Teste'),'CRUD: seleção do agendamento');
  const id=r.html.match(/name="id" value="(\d+)"/)[1];
  r=await a.request('appointment-save',{...data,time:'10:30'}); check(r.status===422 && r.html.includes('intervalo'),'Sobreposição de horários é rejeitada');
  r=await a.request('appointment-save',{...data,time:'10:30',status:'Cancelado'}); check(r.status===302,'Agendamento cancelado não bloqueia horário');
  r=await a.request('appointment-save',{...data,time:'11:00'}); check(r.status===302,'Atendimentos consecutivos são permitidos');
  r=await a.request(`appointment-form&id=${id}`); check(r.status===200 && r.html.includes('Aluno Teste'),'Formulário de edição carrega os dados');
  r=await a.request('appointment-save',{...data,id,student:'Aluno Atualizado',status:'Concluído'}); check(r.status===302,'CRUD: atualização de agendamento');
  r=await a.request('appointments&q=Atualizado&status=Conclu%C3%ADdo'); check(r.html.includes('Aluno Atualizado') && r.html.includes('1 agendamento(s)'),'Busca e filtro de situação funcionam');
  r=await a.request('appointments&q=SemResultados'); check(r.html.includes('Nenhum atendimento encontrado'),'Busca vazia mostra estado apropriado');
  await b.request('register');
  r=await b.request('register',{name:'Personal Teste B',email:`${prefix}-b@teste.local`,password:pwd,password_confirmation:pwd}); check(r.status===302,'Segundo usuário pode se cadastrar');
  r=await b.request('dashboard'); check(!r.html.includes('alert(1)') && !r.html.includes('Aluno Atualizado'),'Dashboard isola dados por usuário');
  r=await b.request('appointments'); check(!r.html.includes('Aluno Atualizado'),'Agenda isola dados por usuário');
  r=await b.request(`appointment-form&id=${id}`); check(r.status===404,'Leitura de registro de outro usuário é bloqueada');
  r=await b.request('appointment-save',{...data,id}); check(r.status===404,'Edição de registro de outro usuário é bloqueada');
  r=await b.request('appointment-delete',{id}); check(r.status===404,'Exclusão de registro de outro usuário é bloqueada');
  await b.request('tasks',{id:taskId,action:'delete'});
  r=await a.request('dashboard'); check(r.html.includes('alert(1)'),'Tarefas também são isoladas por usuário');
  r=await a.request('appointment-delete',{id}); check(r.status===302,'CRUD: exclusão de agendamento');
  r=await a.request(`appointment-form&id=${id}`); check(r.status===404,'Registro excluído não pode ser consultado');
  await a.request('tasks',{id:taskId,action:'delete'});
  r=await a.request('dashboard'); check(!r.html.includes('alert(1)'),'Tarefa pode ser excluída');
  r=await a.request('logout',{}); check(r.status===302,'Logout encerra sessão');
  r=await a.request('dashboard'); check(r.status===302 && r.location.includes('login'),'Sessão encerrada perde acesso ao dashboard');
  await a.request('login');
  r=await a.request('login',{email:`${prefix}-a@teste.local`,password:pwd}); check(r.status===302,'Login autentica usando senha cadastrada');
  await guest.request('register');
  r=await guest.request('register',{name:'Nome Duplicado',email:`${prefix}-a@teste.local`,password:pwd,password_confirmation:pwd}); check(r.status===422 && r.html.includes('já está cadastrado'),'E-mail duplicado é rejeitado');
  r=await guest.request('inexistente'); check(r.status===404,'Rota inexistente retorna 404');
  passed=true;
} finally {
  const php=process.env.PHP_BIN || (existsSync('.runtime/php/php.exe') ? '.runtime/php/php.exe' : 'php');
  console.log(execFileSync(php,['tests/cleanup.php',prefix],{encoding:'utf8'}).trim());
}
if(passed) console.log(`\n${checks} verificações aprovadas com MySQL real.`);
