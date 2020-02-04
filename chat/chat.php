<?php
	session_start();

	$user_id = $_SESSION['user_id'];
	$room_id = $_SESSION['room_id'];
	$room_name = $_SESSION['room_name'];

	try{
		include_once("../common/db_connect.php");
  	$pdo = db_connect();
		$sql = '
			SELECT 
			chat_post.message_id,
			chat_post.user_id,
			chat_post.message,
			chat_post.post_time,
			user.user_name
			FROM 
			chat_post left outer join user on
			user.user_id=chat_post.user_id 
			WHERE 
			chat_post.room_id=?';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($room_id));
	} catch (PDOException $e) {
		echo $e->getMessage();
		exit;
	}
?>
<?php
	if(isset($_POST['send']) && !empty($_POST['message'])){
	  try{
		$message_id = $_POST['message_id'];
		$message = $_POST['message'];
		$_SESSION['message_id'] = $message_id;
		$_SESSION['message'] = $message;
		$db = new PDO('mysql:host=localhost;dbname=buono;character=utf8','root','');
	    header('Location: chat_register.php');
	    exit;
		} catch (PDOException $e){
    	echo $e->getMessage();
    	exit;
  	}
  	if ( empty($_POST['message']) ) {
  		header('Location: chat.php');
  	}
  }
  header('Location: message_list.php');
?>