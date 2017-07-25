<?php

/***********************************
 * Configuração das Rotas do sistema
 ***********************************
 *
 * Na linha indicada mais abaixo, configure todas as rotas que o sistema usa
 * 
 * Exemplo rota única:
 * 
 * - rota método GET, com a URI /pedidos, direcionada para PedidosController, método mostrarTodos
 * 
 * $router->get('/pedidos', ['\\App\\Controllers\\PedidosController', 'mostrarTodos']);
 * 
 * Exemplo REST:
 * 
 * - rota para diversos métodos http, com a uri /clientes, direcionada para ClientesController
 * - neste caso, os métodos http disponíveis são definidos no controller
 * 
 * $router->controller('/clientes', '\\App\\Controllers\\ClientesController'); 
 * 
 * Documentação da biblioteca: https://github.com/mrjgreen/phroute
 * 
 */

/*****************************
 * Uso do sistema, não mexa
 */
namespace System;

use Phroute\Phroute\RouteCollector;
use Phroute\Phroute\Dispatcher;
use Phroute\Phroute\Exception\HttpRouteNotFoundException;
use Phroute\Phroute\Exception\HttpMethodNotAllowedException;

$router = new RouteCollector();

/*****************************
 * Configuração das Rotas, configure aqui
 */

// rota home
$router->get('/', ['\\App\\Controllers\\HomeController', 'index']);
// rotas contas
$router->controller('/invoices', '\\App\\Controllers\\InvoiceController');
// rotas desconto
$router->controller('/discounts', '\\App\\Controllers\\DiscountController');

/*****************************
 * Uso do sistema, não mexa
 */


$dispatcher =  new Dispatcher($router->getData());

// tento disparar a rota de acordo com a requisição, a instância vai tentar encontrar e direcionar, caso positivo
try {
    echo $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
    
// não foi encontrada rota para a requisição
} catch (HttpRouteNotFoundException $e) {
    
    http_response_code(404);
    include __DIR__ . '/../app/Views/errors/404.html';

// rota foi encontrada, mas o método http não é permitido
} catch (HttpMethodNotAllowedException $e) {
    
    http_response_code(405);
    include __DIR__ . '/../app/Views/errors/405.html';
    
}