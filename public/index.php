<?php 
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use DI\Container;

require __DIR__ . '/../vendor/autoload.php';

require '../src/Controller/HomeController.php';

$container = new Container();

$container->set('templating', function() {
	return new Mustache_Engine([
		'loader' => new Mustache_Loader_FilesystemLoader(
			__DIR__ . '/../templates',
			['extension' => '']
		),
	]);
});

$container->set('session', function(){
	return new \SlimSession\Helper();
});

AppFactory::setContainer($container);
$app = AppFactory::create();

$app->add(new \Slim\Middleware\Session);
$app->addErrorMiddleware(true, true, true);

$app->get('/', '\App\Controller\HomeController:default')->add(new \App\Middleware\Authenticate($app->getContainer()->get('session')));
$app->post('/createChat', '\App\Controller\HomeController:createChat')->add(new \App\Middleware\Authenticate($app->getContainer()->get('session')));
$app->any('/login', '\App\Controller\AuthController:login');
$app->any('/logout', '\App\Controller\AuthController:logout');

$app->run();