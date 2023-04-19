<?php 
namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Container\ContainerInterface;
use PDO;

abstract class Controller 
{
	protected $ci;
	protected $db;

	public function __construct(ContainerInterface $ci){
		$this->ci = $ci;
		$this->db = new PDO('sqlite:'. __DIR__ . '/../../database/database.sqlite');
		$query1="CREATE TABLE IF NOT EXISTS `messages`(
		    ID INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
		    fromUser TEXT, 
		    toUser TEXT, 
		    message TEXT,
		    messageDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
		)";
		$this->db->exec($query1);
		$query2="CREATE TABLE IF NOT EXISTS `chats`(
		    ID INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
		    fromUser TEXT, 
		    toUser TEXT
		)";
		$this->db->exec($query2);
	}

	protected function render(Response $response, $template, $data = []){
		$html = $this->ci->get('templating')->render($template, $data);
		$response->getBody()->write($html);
		return $response;
	}

	public function getDb() {
        return $this->db;
    }
}

 ?>