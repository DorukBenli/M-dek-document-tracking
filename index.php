<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';


$app = AppFactory::create();
$app->setBasePath('/bitirme');

$app->get('/', function (Request $request, Response $response) {
    //TODO: LOGIN EKLE --> içeri girince canberkin açtığı sayfa
    //TODO: Döküman yükleme için blob koymak lazım(pdf data)
    //TODO: soft ve inmternete yüklenenleri ayrı tut(soft submit)
    //TODO: PDF yükleme
    ob_start();
    include 'views/temp_main_page.php';
    $html = ob_get_clean();
    $response->getBody()->write($html);
    return $response;
});


$app->get('/add_course', function (Request $request, Response $response) {
    //alternatively we can use controller to generate view.
    ob_start();
    include 'views/course_form.php';
    $html = ob_get_clean();

    $response->getBody()->write($html);
    return $response;
});

$app->get('/add_user',function (Request $request, Response $response){
    //same as node.js
    $response->getBody()->write("this is add user page");
    return $response;
});


$app->run();
