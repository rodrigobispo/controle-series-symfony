# controle-series-symfony
Projeto de estudo contendo um controle básico de cadastro de séries.

### Funcionalidades

- Login e registro de usuário;
- Listagem de séries;
- Cadastro, alteração e exclusão de série;
- Consulta de temporadas e episódios;
- Visualização de quantidade de episódios assistidos.

### Utilização

1. Na pasta do projeto, rodar o comando `composer install` para baixar e instalar as dependências do projeto;
2. Na pasta do projeto, executar o comando "`npm install`" para instalação das dependências de front-end;
3. Executar também: `npm install bootstrap --save-dev`;
4. Executar também: `npm install bootstrap@5.2.0 –save-dev`;
5. Na pasta do projeto, executar a migration ou rodar `php bin/console doctrine:schema:update --force --complete --dump-sql`;
6. Executar: `php -S localhost:8080 -t public`; e assim navegar na aplicação com localhost:8080/login;

### Tecnologias usadas

- Php com Symfony 6.1 (usando o composer com `composer create-project symfony/skeleton:"6.1.*"`);
- Recursos com Symfony: Doctrine, Autenticação, Permissão, Cache, Form, Twig, banco de dados em SQlite;

