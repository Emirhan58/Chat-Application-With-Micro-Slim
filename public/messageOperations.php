<?php 

	$db = new PDO('sqlite:'. __DIR__ . '/../database/database.sqlite');

	if(isset($_GET['getMessages'])){

		$fromUser = $_GET['fromUser'];
		$toUser = $_GET['toUser'];

		$queryChat = "SELECT * FROM messages WHERE (fromUser=:fromUser AND toUser=:toUser) OR (fromUser=:toUser AND toUser=:fromUser)";
		$stmt = $db->prepare($queryChat);
		$stmt->bindParam(':fromUser', $fromUser, PDO::PARAM_STR);
		$stmt->bindParam(':toUser', $toUser, PDO::PARAM_STR);
		$stmt->execute();

		$toUsers = array();
		while ($message = $stmt->fetch(PDO::FETCH_ASSOC)) {
		    if($message['fromUser'] == $fromUser)
            	echo "<div class=\"card tickets-message me\">".$message['message']."</div>"; 
          	else
            	echo "<div class=\"card tickets-message you\">".$message['message']."</div>";
		}

	}

	if(isset($_POST['sendMessage'])){
		if (substr_count($_POST['message'], " ") == strlen($_POST['message'])) {
		    exit;
		}

		$fromUser = $_POST['fromUser'];
		$toUser = $_POST['toUser'];
		$message = $_POST['message'];

		$queryChatCreate = "INSERT INTO messages (fromUser, toUser, message) VALUES (:fromUser, :toUser, :message)";
		$stmt = $db->prepare($queryChatCreate);
		$stmt->bindParam(':fromUser', $fromUser);
		$stmt->bindParam(':toUser', $toUser);
		$stmt->bindParam(':message', $message);
		$stmt->execute();
	}

?>