# Arquitetura e roteiro de apresentação

**Aluno:** Matheus Souza Segatto · **Projeto:** Pulse Personal.

## Exemplo de requisição

Ao clicar em **Criar agendamento**, o navegador envia um POST para `index.php?route=appointment-save`. O Front Controller verifica a sessão e o token CSRF, depois chama `AppointmentController::save()`. O controller obtém o usuário da sessão, confirma a propriedade do registro em uma edição e aplica `Appointment::validate()`. Se os dados forem válidos, chama `AppointmentRepository::save()`.

O repository abre uma transação, bloqueia a linha do personal (`FOR UPDATE`) e verifica se o intervalo se sobrepõe a outro atendimento não cancelado. O teste de sobreposição usa início menor que o fim proposto e fim maior que o início proposto. Isso permite atendimentos consecutivos e impede sobreposição mesmo em gravações concorrentes da mesma conta. A gravação e a conferência ocorrem na mesma transação. Em erro, o repository faz rollback. Em sucesso, o controller redireciona para a lista, seguindo Post/Redirect/Get e exibindo uma mensagem de confirmação.

## Responsabilidades

| Camada | Arquivos | Responsabilidade |
| --- | --- | --- |
| Entrada | `public/index.php` | Rotas por método HTTP, sessão, CSRF, cabeçalhos, erros |
| Controller | `AuthController.php` | Cadastro, autenticação e logout |
| Controller | `DashboardController.php` | Indicadores e operações de tarefas |
| Controller | `AppointmentController.php` | Lista, formulário, gravação e exclusão |
| Modelo | `Models/Appointment.php` | Modalidades, situações e validação |
| Persistência | `Repositories/*.php` | SQL preparado e escopo por usuário |
| Infraestrutura | `Core/Database.php` | Conexão PDO Singleton |
| Apresentação | `Views/**/*.php` | HTML, formulários e dados escapados |

## Segurança aplicada

- `password_hash(..., PASSWORD_BCRYPT)` cria o hash; `password_verify()` confere a senha. A senha original não é armazenada no banco.
- Cadastro limita a senha a 72 bytes, respeitando o limite de entrada do bcrypt.
- PDO usa prepared statements reais (`ATTR_EMULATE_PREPARES=false`).
- O ID do usuário vem da sessão e acompanha consultas, alterações e exclusões.
- Sessão regenerada após login, modo estrito, cookie HttpOnly e SameSite=Lax; Secure é habilitado quando a aplicação roda em HTTPS.
- Token aleatório de 32 bytes validado com `hash_equals()` em todo POST, inclusive logout.
- `htmlspecialchars()` protege dados apresentados contra interpretação como HTML.
- CSP restringe scripts e estilos à própria origem, sem JavaScript inline ou CDN; `X-Frame-Options` impede enquadramento.
- Logs técnicos ficam fora de `public/`; erros do banco não são enviados ao navegador.
- Exclusão de agendamento acontece por POST autenticado, com CSRF e confirmação visual.

## Limites do escopo acadêmico

Não há recuperação de senha por e-mail, confirmação de e-mail, integração com WhatsApp, pagamentos ou prontuários de alunos. Os indicadores representam os registros da agenda, não uma base externa de alunos. O sistema não possui limitação persistente de tentativas de login; antes de exposição pública, adicionar rate limiting e operar com HTTPS, backups e monitoramento. O deploy deve usar um servidor com PHP e MySQL; hospedar apenas HTML estático não executa este projeto.

## Como demonstrar em aula

1. Criar uma conta própria e explicar o hash no banco.
2. Entrar no dashboard e incluir uma tarefa; concluir e reabrir a tarefa.
3. Criar um agendamento com aluno, data e horário.
4. Encontrar o agendamento na listagem e usar os filtros.
5. Editar modalidade ou situação; verificar a mudança na lista.
6. Tentar um horário sobreposto e observar a validação.
7. Excluir o agendamento e encerrar a sessão.
8. Mostrar as pastas MVC e localizar Singleton, Repository e Front Controller.
