# Resposta do aluno — sugestão para revisão

**Aluno:** Matheus Souza Segatto

**Repositório GitHub:** https://github.com/segattoz/pulse-personal-acqa

## Conclusão sugerida

O Pulse Personal é um sistema de agendamentos para personal trainers, desenvolvido em PHP e MySQL com arquitetura MVC. Ele permite cadastrar usuários, autenticar o acesso e organizar atendimentos com aluno, modalidade, data, horário, duração, situação e observações. O dashboard apresenta os atendimentos do dia, indicadores da agenda e uma lista de tarefas do usuário autenticado. O CRUD de agendamentos permite inserir, consultar, atualizar e excluir registros, além de pesquisar atendimentos e filtrar sua situação.

Na organização MVC, os controllers recebem as requisições e coordenam as ações; a camada de modelo reúne as regras de validação e os repositories responsáveis pela persistência; e as views apresentam as telas e os formulários. O banco MySQL contém as tabelas users, appointments e tasks, relacionadas por chaves estrangeiras. Cada consulta considera o usuário da sessão para manter os dados separados entre contas.

Foram aplicados os padrões Singleton, Repository e Front Controller. O Singleton reutiliza uma conexão PDO; os repositories encapsulam o SQL e separam a persistência das outras responsabilidades; e o Front Controller centraliza o roteamento e as verificações comuns. As senhas são armazenadas com bcrypt e verificadas com password_verify. A aplicação também utiliza consultas preparadas, tokens CSRF, escape de HTML e regeneração da sessão após o login.

O sistema verifica conflitos de horário antes de gravar um agendamento e foi validado com testes de autenticação, CRUD, tarefas e isolamento entre usuários, utilizando MySQL real. O projeto demonstra como a separação de responsabilidades, os padrões de projeto e as medidas de segurança facilitam a manutenção de uma aplicação web e a organização da rotina de um profissional.

## Arquivos para anexar

- `docs/prints/01-login.jpg` — login.
- `docs/prints/02-cadastro.jpg` — cadastro.
- `docs/prints/03-dashboard.jpg` — dashboard com tarefas.
- `docs/prints/04-novo-agendamento.jpg` — formulário do CRUD.
- `docs/prints/05-agendamentos.jpg` — listagem do CRUD.
- `docs/prints/06-adicionar-tarefa.jpg` — adição de tarefa no dashboard.

**Antes de enviar:** revise o código e adapte esta conclusão ao seu entendimento. O projeto e este texto foram preparados com assistência de IA; a entrega deve respeitar as regras de autoria e de uso de ferramentas da disciplina.
