<?php 

require_once("vendor/autoload.php");

use Slim\Factory\AppFactory;
use Hcode\Page;
use Hcode\PageAdmin;
use Hcode\Model\User;

// Definindo a função get_magic_quotes_gpc caso não exista
if (!function_exists('get_magic_quotes_gpc')) {
    function get_magic_quotes_gpc() {
        return false;
    }
}

// Criando a aplicação
$app = AppFactory::create();

// Ativando o debug
$app->addErrorMiddleware(true, true, true);

$app->get('/', function($request, $response, $args) {
    $page = new Page();
    $page->setTpl('index');
    return $response;
});

$app->get('/admin', function($request, $response, $args) {
    $page = new PageAdmin();
    $page->setTpl('index');
    return $response;
});

$app->get('/admin/login', function($request, $response, $args) {
    $page = new PageAdmin([
        'header' => false,
        'footer' => false
    ]);
    $page->setTpl('login');
    return $response;
});

$app->post('/admin/login', function($request, $response, $args) {
    User::login($request->getParsedBody()['login'], $request->getParsedBody()['password']);
    return $response->withHeader('Location', '/admin')->withStatus(302);
});

// Executando a aplicação
$app->run();

?>
