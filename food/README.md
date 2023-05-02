Autor: Gabriel Tarozzo Ferreira Lustosa

Titulo: Backend Challenge 20230105
Descrição: Esse é um projeto desenvolvido em formato de teste para vaga de desenvolvedor PHP/laravel Developer Pleno.
Nele tive vários desafios para desenvolver uma REST API para utilizar os dados do projeto Open Food Facts, que é um banco de dados aberto com informação nutricional de diversos produtos alimentícios.

==========================================

Lista com linguagem, framework e/ou tecnologias usadas:
Sistema Operacional: Ubuntu
Linguagem: PHP
Framework: Laravel
Banco de dados: MySQL
API externa: Open Food Facts
Bibliotecas: Diversas usadas pelo Laravel, tais como:
Biblioteca de requisições HTTP: Guzzle (usada pelo Laravel)
Biblioteca para manipulação de datas: Carbon (usada pelo Laravel)

==========================================

Processo de investigação para o desenvolvimento da atividade:

Criei alguns arquivos a mais no projeto, deixei salvo no mesmo com o intuito de deixar como material para estudo futuramente. Dito isso, vou deixar os arquivos importantes de cada tema marcados ao final de cada, para que seja analisado para o teste.

Abaixo separei por temas cada processo de desenvolvimento, com um breve resumo do que foi feito;

==========================================

CRUD E ROTAS (CONTROLLER):

Já estou acostumado com esse ambiente de controllers e rotas do laravel, inclusive, é um tipo de estrutura que me agrada muito, e que aprendi a gostar muito. E gostar do FrameWork + a linguagem me motiva muito a estudar e aprender coisas novas. Nessa parte fluiu bem com a criação do CRUD com as especificações do desafio, com pouca pesquisa eu consegui montar um sistema CRUD e as rotas.

Pasta: Http/Controllers/ProductController.php
       routes/api.php
       routes/web.php

==========================================

API E INTEGRAÇÃO:

Aqui foi de fato meu maior desafio, mas não tenho certeza se os problemas que enfrentei eram pré-programados pelo desafio.
Talvez, com um pouco mais de tempo e dedicação eu consegui-se chegar a uma melhor solução, porém, encontrei uma solução que solucionou meus problemas. Meus problemas foram: Peso do json, descompactar arquivo .gz.

Embora eu tentasse os meios mais naturais para a conclusão do desafio, eu sempre era barrado por um vilão, o tempo de processamento, e sempre ao tentar ajustar esse tempo de processamento, era um efeito dominó: não dava para deixar o código ler o arquivo JSON inteiro. Pois estourava algo que o laravel impôs que era o limite. Tentei alterar os limites na marra, mas mesmo assim não dava certo. tanto de tempo de processamento, quanto de limite que já estavam ilimitados e o problema persistia. Tentei foreach no código para ler o documento em partes, tentei utilizar outros métodos do proprio laravel para tentar ler o arquivo de uma maneira diferente, para fazer a descompactação e json_decode após, mas sem sucesso. E após algumas tentativas descobri algumas maneiras de, com o arquivo baixado no meu computador, explorar e separar esses dados, manipulando da maneira que eu gostaria. Mas, não estava bom, não é ideal deixar esses dados no computador. Quando finalmente após muita pesquisa, descobri um comando shell que me salvaria: curl -s $fileUrl | gzip -d | jq -n '[inputs | select(. != null)] | .[:10].

Esse comando é uma sequência de pipelines em um shell script que usa três programas diferentes: curl, gzip e jq. 

Aqui está o que cada parte do comando faz:

curl -s $fileUrl: Esse comando usa o curl para fazer uma requisição HTTP para a URL especificada por $fileUrl. O -s significa "silencioso", o que impede que o curl exiba informações desnecessárias na saída. A saída do comando é o conteúdo do arquivo na URL. O curl é uma ferramenta de linha de comando que permite fazer requisições HTTP para URLs remotas e obter os dados diretamente na saída do terminal. Isso significa que ele não precisa armazenar o conteúdo do arquivo no disco rígido local.

gzip -d: A saída do comando curl é piped (redirecionada) para o gzip, que descompacta o conteúdo com o formato gzip. Quando combinado com o curl, ele permite que o conteúdo do arquivo seja baixado em formato compactado e descompactado diretamente na saída do terminal, sem a necessidade de armazenar o arquivo compactado no disco rígido local.

jq -n '[inputs | select(. != null)] | .[:10]': Finalmente, a saída descompactada é piped para o jq, um processador de JSON, que realiza as seguintes ações:

jq -n: Inicia um novo objeto JSON vazio.
[inputs | select(. != null)]: Lê a entrada do pipeline (saída do gzip) e filtra as entradas que não são nulas. Em seguida, é criado um array JSON com essas entradas.
| .[:10]: O operador | é usado para passar a saída do filtro anterior para um novo filtro que pega apenas os 10 primeiros elementos do array.
O resultado final é uma lista com os 10 primeiros elementos do conteúdo descompactado do arquivo na URL especificada. Quando combinado com o curl e o gzip, ele permite que o conteúdo do arquivo JSON seja processado diretamente na saída do terminal, sem a necessidade de armazenar o arquivo JSON no disco rígido local.

Em resumo, a combinação dessas três ferramentas permite que o comando baixe, descompacte e processe um arquivo JSON sem a necessidade de armazenar o arquivo ou o conteúdo dele no disco rígido local. Perfeito! Era o que eu buscava. é possivel, com uma boa configuração de servidor, ajustar o tamanho da leitura para 20, 30, ou até mesmo 100 (que inclusive era o solicitado). Porém, de fato, talvez por falta de um conhecimento na área de servidor, consegui realizar a configuração limitando a 10 objetos de cada arquivo por meio deste comando.

O restante seguiu de maneira mais natural, a inserção no banco pela API eu fiz de uma maneira até mais brusca. Mas estou ciente da ORM do laravel (consultas em um nivel mais alto de abstração) que também é tranquila de se aplicar.

Arquivo principal da API: app/Console/Commands/ImportProductsCommand.php


==========================================

SCHEDULE E CRON:
Uma coisa bacana deste desafio foi aprender a utilizar Schedule e Cron, que são recursos extremamente úteis mas que podem ficar um pouco esquecidos dependendo do ambiente em que se esteja. Não tinha muita prática mas também aprendi bastante estudando como aplicar e suas utilizações
Também tive um aprendizado de como utilizar o crontab -e para setar as tasks.

Arquivos: app/Console/Kernel.php (na function schedule onde você consegue setar dia/hora da execução)
          config/app (podemos setar variaveis e horários)
          .env (podemos setar uma variavel e horário)

==========================================

BANCO DE DADOS:

![produtos e cadastro banco](https://github.com/Dipezork/Products-Parser-20230105/blob/master/img2.png)

Na descrição do teste tinha a sugestão de utilizar o banco de dados MongoDB. Nunca havia utilizado, mas experimentei, testei e tive um pouco de dificuldade por falta de prática, como na mesma sugestão possibilitou a escolha de outro banco, peguei um que estava mais habituado: MySQL.
Como vão ver no video, toda manipulação de dados foi por meio do CMD do ubuntu e das migrations do laravel, criação de banco, usuário, tabela, querys em geral... Aproveitei a oportunidade para me inteirar da prática via CMD, sempre fui bem acostumado com a interface visual para gerenciar banco de dados MySQL, como por exemplo: MySQL Workbench, phpMyAdmin(web). Mas gostei de ter praticado este desafio pelo CMD, me deu mais opções de produtividade. É importante ressaltar também que utilizar o meio das migrations do Laravel é muito prático, ponto positivo para o framework! 

Arquivos das migrations: database/migrations

==========================================

TESTES UNITARIOS:

![produtos e cadastro banco](https://github.com/Dipezork/Products-Parser-20230105/blob/master/img1.png)

Devo deixar claro, nunca tive o hábito de criar testes unitários, mas depois deste teste criei gosto pela prática, e fiquei feliz de ter aprendido.

Na estrutura tests/unit/
Criei um arquivo chamado ProductControllerTest.php e nele desenvolvi um codigo para testShow() e testIndex(), com o intuito de testar 2 métodos criados na controller ProductController. Tive alguns problemas neste desenvolvimento e alguns aprendizados importantes, tais como:
É interessante criar um banco de dados alternativo apenas para testes, para que não haja conflitos entre os dados testados e os originais.
Para tal, entendi que seria interessante criar um banco de dados a parte e um .env.testing também a parte para identificar os testes. (Não cheguei a configurar o .env.testing, mas achei interessante a ideia e foi muito bom aprender sobre).

Como optei por não criar um banco de dados alternativos, criei manualmente dados ficticios de usuario e de produto para poder concluir os testes unitarios e ver na prática como que funciona. Fiquei bem feliz quando, após um amontoado de erros durante os testes unitarios, eu finalmente consegui ajustar o código e pude ver o verdinho do PASS.

Arquivo dos testes unitarios: tests/unit/ProductControllerTest.php

===========================================

Video: https://www.loom.com/embed/a718d939156d42aa90e3588282afde5d

This is a challenge by Coodesh

