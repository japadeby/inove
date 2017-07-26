# Inove Dados - Teste

Sistema Financeiro de teste, referente à um cadastro de contas à pagar. Desenvolvido em PHP7, utilizando arquitetura MVC, PSR-4, sem uso de *frameworks backend*, com as seguintes características básicas. 

  - CRUD de cada conta
  - Desconto(s) para cada conta
  - Recorrência de conta
   
  Você também pode:
- Pesquisar por contas
- Consultar vencimentos nos próximos 30 dias (inclusive graficamente)

### Dependências
- Apache com mod_rewrite
- PHP 7
- Composer
- NPM (js)
- Mysql/MariaDB >=5.6

### Bibliotecas/*Packages* utilizados

* [jQuery](http://api.jquery.com) - Biblioteca JS
* [Bootstrap](http://getbootstrap.com/) - Framework frontend 
* [Eloquent](https://github.com/illuminate/database) - Toolkit para trabalhar com banco de dados em PHP 
* [Phroute](https://github.com/mrjgreen/phroute) - Biblioteca para rotas
* [Gentelella](https://github.com/puikinsh/gentelella) - template baseado em BootstrapJS para Painel Administrativo

### Instalação

Baixe ou clone o repositório dentro da pasta que o seu servidor local utiliza (www, public_html, etc). Entre na pasta e atualize os pacotes PHP e JS:

```sh
$ npm i
$ composer update
```

Crie uma base de dados e edite o arquivo *app/config.php*.

Rode a Query contida no arquivo */database/tables.sql*. Isto irá criar as tabelas necessárias. 

Crie um virtual host no servidor e aponte o *DocumentRoot* para a pasta */public*. Normalmente basta  editar o arquivo .conf correto, localizado na pasta de instalação do servidor.
Documentação: https://httpd.apache.org/docs/2.4/vhosts/examples.html

Altere o arquivo */hosts* do seu sistema operacional para que o mesmo entenda o virtual host criado acima.
Tutorial: https://www.tecmundo.com.br/sistema-operacional/5214-como-editar-os-arquivos-hosts-do-computador-.htm

**Pronto! Seu sistema está funcionando!**

### Estrutura

##### Rotas
Configuração ocorre no arquivo *system/routes*. É a biblioteca responsável por gerenciar cada requisição http e encaminhar para o *controller* correto. Mais informações: https://github.com/mrjgreen/phroute

##### Controllers
Localizados na pasta *app/Controllers*, recebem as requisições das rotas e controlam o que consultar no banco e o que devolver ao usuário.

##### Models
Localizados na pasta *app/Models*, significam a representação de cada tabela do banco de dados. No projeto é utilizado o Eloquent, que conta com inúmeras funcionalidades. Mais informações: https://github.com/illuminate/database

##### Views
Localizadas na pasta *app/Views*, exibem o que o *controller* informou ao usuário. Cada módulo pode ter um, nenhum ou mais de uma *view*

Assim, para criar um módulo novo, contendo telas para o usuário e persistência de dados no banco, quatro passos são necessários, no mínimo:
- criar uma rota 
- criar um controller
- criar um model
- criar uma view


### Função Extra!!!!
Já que existe a recorrência de uma conta, foi pensada uma função que insere recorrências no banco de dados automaticamente, através de CronJob.

##### Dependência

- Ferramenta de CronJob no servidor
 
##### Exemplo

Para um comando que rode todo dia às 10h da manhã, o comando cron deve ser:
```sh
0 10 * * * php /[caminho completo do projeto]/app/Cron/Recurrent.php >> /dev/null 2>&1
```

##### Funcionamento 

Diariamente, o servidor irá rodar o comando acima. O arquivo em questão executa os seguintes passos:
- instancia a classe **Recurrent**, executa o método *createRecurrent()*
- busca todas as contas recorrentes
- 10 dias antes do vencimento da próxima recorrência ela é inserida no banco, respeitando o intervalo nas informações da conta

**Espero que gostem, obrigado!**
