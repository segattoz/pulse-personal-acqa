# Resposta do aluno

**Aluno:** Matheus Souza Segatto

**Repositório GitHub:** https://github.com/segattoz/pulse-personal-acqa

## Conclusão

O Pulse Personal é um sistema de agendamentos para personal trainers, desenvolvido em PHP e MySQL. Possui cadastro e login com senhas protegidas por bcrypt, dashboard com tarefas do usuário e CRUD completo de agendamentos, permitindo inserir, consultar, atualizar e excluir atendimentos.

A arquitetura MVC separa as regras e os dados, a apresentação das telas e o controle das requisições. Foram utilizados os padrões Singleton, para reutilizar a conexão PDO; Repository, para organizar o acesso ao banco; e Front Controller, para centralizar as rotas e verificações de segurança. O sistema também utiliza consultas preparadas, proteção CSRF, isolamento dos dados por usuário e validação de conflitos de horário.



## Arquivos 

- `<img width="1536" height="884" alt="image" src="https://github.com/user-attachments/assets/5cc1ab26-a61c-4020-9a2d-a4166fa76756" />
` — login.
- `<img width="1520" height="1156" alt="image" src="https://github.com/user-attachments/assets/7d8fe7c0-eef6-4e8e-9cb0-fe21fc7702b3" />
` — cadastro.
- `<img width="1536" height="639" alt="image" src="https://github.com/user-attachments/assets/215ec53e-383e-430a-ab8f-8b86d75e3d1b" />
` — dashboard com tarefas.
- `<img width="1520" height="1053" alt="image" src="https://github.com/user-attachments/assets/63143dd2-b55b-4d94-93dc-c5b1c8a2d7a8" />
` — formulário do CRUD.
- `<img width="1520" height="884" alt="image" src="https://github.com/user-attachments/assets/2c429ce9-be71-4764-878e-ef9c734683e5" />
` — listagem do CRUD.
- `<img width="1536" height="639" alt="image" src="https://github.com/user-attachments/assets/4fec86ef-f97e-4bc2-a4fe-b5c17f0227ab" />
` — adição de tarefa no dashboard.
