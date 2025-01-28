<?php 

session_start();

require 'vendor/autoload.php';

use Slim\Factory\AppFactory;
use Hcode\Page;
use Hcode\PageAdmin;
use Hcode\Model\User;

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
    
    User::verifyLogin();
    
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

$app->post('/admin/login',function($request,$response,$args)
{
    
    User::login($_POST["login"],$_POST["password"]);

    header("Location: /admin");

    return $response->withHeader('Location', '/admin')->withStatus(302);
});


$app->get("/admin/logout",function($request,$response,$args)
{
    User::logout();

    header("Location: /admin/login");

    return $response->withHeader('Location', '/admin/login')->withStatus(302);

});


// Executando a aplicação
$app->run();

?>
