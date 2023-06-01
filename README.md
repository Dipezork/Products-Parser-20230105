# Backend Challenge 20230105

# Desafio de Desenvolvimento de API e Integração

Neste projeto, foi realizado um desafio para desenvolver uma REST API utilizando o framework Laravel, com o objetivo de utilizar os dados do projeto Open Food Facts, que é um banco de dados aberto com informações nutricionais de diversos produtos alimentícios.

## Tecnologias Utilizadas

- Sistema Operacional: Ubuntu
- Linguagem: PHP
- Framework: Laravel
- Banco de Dados: MySQL
- API Externa: Open Food Facts
- Bibliotecas: Diversas bibliotecas utilizadas pelo Laravel, como:
  - Biblioteca de requisições HTTP: Guzzle (utilizada pelo Laravel)
  - Biblioteca para manipulação de datas: Carbon (utilizada pelo Laravel)

## Processo de Desenvolvimento

Durante o desenvolvimento deste projeto, foram seguidas boas práticas de engenharia de software. Abaixo, descrevo as etapas envolvidas em cada processo de desenvolvimento, destacando as atividades relevantes realizadas:

### CRUD e Rotas (Controller)

A criação do CRUD e das rotas foi uma etapa que fluíu bem, uma vez que já estou familiarizado com o ambiente de controllers e rotas do Laravel. Essa estrutura é uma das minhas preferidas e, por isso, foi possível criar facilmente o sistema CRUD e as rotas especificadas no desafio.

Principais arquivos envolvidos:

- `Http/Controllers/ProductController.php`
- `routes/api.php`
- `routes/web.php`

### API e Integração

A etapa de API e integração foi o maior desafio encontrado durante o projeto. Foram enfrentados problemas relacionados ao tamanho do JSON e a descompactação de arquivos no formato `.gz`.

Apesar de ter tentado abordagens mais convencionais para lidar com esses problemas, como ajustar o tempo de processamento e aumentar os limites impostos pelo Laravel, não obtive sucesso. As tentativas de ler o arquivo JSON completo ou em partes utilizando `foreach` também não foram efetivas.

Após uma extensa pesquisa, descobri um comando shell que resolveu os problemas enfrentados: `curl -s $fileUrl | gzip -d | jq -n '[inputs | select(. != null)] | .[:10]'`.

Esse comando utiliza o `curl` para fazer uma requisição HTTP para a URL especificada (`$fileUrl`), o `gzip` para descompactar o conteúdo e o `jq` para processar o JSON resultante. Com isso, pude explorar e separar os dados conforme necessário.

Apesar de não ser uma solução ideal, pois requer o download do arquivo no computador local, foi a alternativa encontrada para contornar as limitações impostas pelo Laravel.

Principais arquivo envolvido:

- `app/Console/Commands/ImportProductsCommand.php`

### Schedule e Cron

Durante o desafio, foi necessário utilizar o recurso de agendamento de tarefas do Laravel, conhecido como Schedule e Cron. Esses recursos são extremamente úteis, mas podem ser negligenciados dependendo do ambiente de desenvolvimento.

Foi possível configurar o agendamento das tarefas utilizando o método `schedule` no arquivo `app/Console/Kernel.php`. Por meio dessa configuração, foi possível definir o dia e a hora de execução das tarefas.

Outras configurações relacionadas a variáveis de ambiente e horários podem ser ajustadas nos arquivos `config/app` e `.env`.

### Banco de Dados

O teste sugeria a utilização do banco de dados MongoDB, porém, devido à minha familiaridade com o MySQL, optei por utilizá-lo neste projeto. O processo de manipulação de dados foi realizado por meio do terminal do Ubuntu e das migrações do Laravel.

Foram criados bancos de dados, usuários, tabelas e consultas utilizando o MySQL. Essa prática permitiu explorar a utilização do terminal para gerenciamento de bancos de dados, o que proporcionou maior produtividade. Vale destacar a facilidade proporcionada pelas migrações do Laravel, que simplificaram bastante o processo de criação e manutenção do banco de dados.

Principais arquivos envolvidos:

- Arquivos de migração: `database/migrations`

### Testes Unitários

Foi realizada a criação de testes unitários utilizando a estrutura de testes do Laravel. No arquivo `tests/unit/ProductControllerTest.php`, foram implementados os testes para os métodos `testShow()` e `testIndex()` da controller `ProductController`.

Durante o desenvolvimento dos testes unitários, foram identificados desafios, como a necessidade de criar um banco de dados alternativo exclusivo para os testes, a fim de evitar conflitos com os dados reais. Apesar de não ter configurado o arquivo `.env.testing`, foram criados dados fictícios de usuário e produto manualmente para concluir os testes unitários.
Arquivo dos testes unitários:

- `tests/unit/ProductControllerTest.php`

## Considerações Finais

Durante todo o projeto, busquei aderir às melhores práticas de engenharia de software, aplicando conceitos de design, controle de versão, documentação de código e testes automatizados. Essas práticas garantem a manutenibilidade, escalabilidade e colaboração no desenvolvimento de software, resultando em soluções de qualidade.

Este projeto foi realizado como parte do desafio proposto pela Coodesh.
