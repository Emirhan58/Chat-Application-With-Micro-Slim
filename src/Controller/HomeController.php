<?php 
namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use PDO;
class HomeController extends Controller
{

	public function default(Request $request, Response $response, $data)
	{
		$user = $this->ci->get('session')->get('user');

		$queryChat = "SELECT * FROM chats WHERE fromUser=:fromUser OR toUser=:toUser";
		$stmt = $this->db->prepare($queryChat);
		$stmt->bindParam(':fromUser', $user, PDO::PARAM_STR);
		$stmt->bindParam(':toUser', $user, PDO::PARAM_STR);
		$stmt->execute();

		$toUsers = array();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		    if($user === $row['fromUser']){
		        array_push($toUsers,$row['toUser']);
		    }
		    else{
		        array_push($toUsers,$row['fromUser']);
		    }
		}

		$toUser = "";
		if (!empty($toUsers)) {
		    $toUser = $toUsers[0];
		}


		return $this->render($response, 'homepage.html', [
			'user' => $user,
			'toUsers' => $toUsers,
			'toUser' => $toUser
		]);
	}

	public function createChat(Request $request, Response $response)
	{
		$fromUser = $this->ci->get('session')->get('user');
		$toUser = $request->getParam('toUsername');
		$queryChatCreate = "INSERT INTO chats (fromUser, toUser) VALUES (:fromUser, :toUser)";
		$stmt = $this->db->prepare($queryChatCreate);
		$stmt->bindParam(':fromUser', $fromUser);
		$stmt->bindParam(':toUser', $toUser);
		$stmt->execute();


		return $response->withRedirect('/');
	}
}

 ?>