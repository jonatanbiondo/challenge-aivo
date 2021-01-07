<?php
require '../vendor/autoload.php';
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Dotenv\Dotenv as Dotenv;
use \App\Service\SpotifyAPI as SpotifyAPI;

$dotenv = Dotenv::createImmutable(dirname(dirname(__FILE__)));
$dotenv->load();
 

$container = new \Slim\Container(); 
 //* Piso el manejador de recurso inexistente
$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $response->withStatus(404)
                        ->withJson(array("error" => "Not found"));
    };
};


/**
 * Controlador para la ruta api/v1/albums
 * Parametros q=[algo]
 * ejemplo: http://localhost:8080/api/v1/albums?q=patricio%20rey
 */
$app = new \Slim\App($container);
$app->get('/api/v1/albums', function (Request $request, Response $response, array $args) {
        $spotify_credentials = array(
            'client_id' => getenv('SPOTIFY_CLIENT_ID'),
            'client_secret' => getenv('SPOTIFY_CLIENT_SECRET'),
        );
       
       $params = $request->getQueryParams();
       
       if(isset($params['q']) && $params['q'] != ''){
           try{
                $api = SpotifyAPI::getInstance($spotify_credentials);
                $data =  $api->search($params['q']);
                $newResponse = $response->withJson($data);
                return $newResponse;
            }catch(\Exception $e){
                $newResponse = $response->withJson(array("error" => "Server Error"));
                $newResponse = $response->withStatus(500);
            }
    }else{
        //Bad Request
        $newResponse = $response->withJson(array("error" => "Bad Request: Parameter 'q' missing "));
        $newResponse = $response->withStatus(400);
    }
    return $newResponse;
});


$app->run();
