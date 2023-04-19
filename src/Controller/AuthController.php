<?php 
namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController extends Controller
{
	public function login(Request $request, Response $response)
	{	
		if($request->isPost()){
			$this->ci->get('session')->set('user', $request->getParam('username'));
			return $response->withRedirect('/');
		}


		return $this->render($response, 'login.html');
	}

	public function logout(Request $request, Response $response)
	{
		if($request->isPost()){
			$this->ci->get('session')->delete('user');
			return $response->withRedirect('/');
		}


		return $this->render($response, 'login.html');
	}
}

?>