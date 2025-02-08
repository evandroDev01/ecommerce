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

$app->get("/admin/users",function($request,$response,$args)
{
    User::verifyLogin();

    $users = User::listAll();

    $page = new PageAdmin();
    $page->setTpl("users",array(
        "users" => $users
    ));

    return $response;

});

$app->get("/admin/users/create",function($request,$response,$args)
{
    User::verifyLogin();

    $page = new PageAdmin();
    $page->setTpl("users-create");


    return $response;

});

$app->get("/admin/users/{iduser}/delete",function($request, $response,$args)
{
    User::verifyLogin();

    $iduser = $args['iduser'];

    $user = new User();
    $user->get((int)$iduser);
    $user->delete();

    header("Location: /admin/users");

    return $response->withHeader('Location', '/admin/users')->withStatus(302);
});

$app->get("/admin/users/{iduser}",function($request,$response,$args)
{

    User::verifyLogin();

    $iduser = $args['iduser'];

    $user = new User();
    $user->get((int)$iduser);

    $page = new PageAdmin();
    $page->setTpl("users-update",array("user" => $user->getValues()));

    return $response;

});

$app->post("/admin/users/create",function($request,$response,$args)
{
    User::verifyLogin();

    $user = new User();

    $_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

    $user->setData($_POST);
    $user->save();

    header("Location: /admin/users");
    
    return $response->withHeader('Location', '/admin/users')->withStatus(302);

});

$app->post("/admin/users/{iduser}",function($request,$response,$args)
{
    User::verifyLogin();

    $iduser = $args['iduser'];

    $_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

    $user = new User();
    $user->get((int)$iduser);
    $user->setData($_POST);
    $user->update();

    header("Location: /admin/users");

    return $response->withHeader('Location', '/admin/users')->withStatus(302);

});



// Executando a aplicação
$app->run();

?>
