# Resposta do aluno

**Aluno:** Matheus Souza Segatto

**Repositório GitHub:** https://github.com/segattoz/pulse-personal-acqa

## Conclusão

O Pulse Personal é um sistema de agendamentos para personal trainers, desenvolvido em PHP e MySQL. Possui cadastro e login com senhas protegidas por bcrypt, dashboard com tarefas do usuário e CRUD completo de agendamentos, permitindo inserir, consultar, atualizar e excluir atendimentos.

A arquitetura MVC separa as regras e os dados, a apresentação das telas e o controle das requisições. Foram utilizados os padrões Singleton, para reutilizar a conexão PDO; Repository, para organizar o acesso ao banco; e Front Controller, para centralizar as rotas e verificações de segurança. O sistema também utiliza consultas preparadas, proteção CSRF, isolamento dos dados por usuário e validação de conflitos de horário.

O desenvolvimento e a elaboração desta resposta contaram com auxílio de inteligência artificial. O projeto demonstra a aplicação integrada de PHP, MySQL, MVC e padrões de projeto na organização da rotina de um personal trainer.

## Arquivos para anexar

- `docs/prints/01-login.jpg` — login.
- `docs/prints/02-cadastro.jpg` — cadastro.
- `docs/prints/03-dashboard.jpg` — dashboard com tarefas.
- `docs/prints/04-novo-agendamento.jpg` — formulário do CRUD.
- `docs/prints/05-agendamentos.jpg` — listagem do CRUD.
- `docs/prints/06-adicionar-tarefa.jpg` — adição de tarefa no dashboard.
