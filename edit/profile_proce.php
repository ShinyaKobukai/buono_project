<?php 
	include_once("../common/db_connect.php");
	session_start();
	if (isset($_FILES["user_icon"])) {
		$user_icon = file_get_contents($_FILES["user_icon"]["tmp_name"]);
	    $user_icon = str_replace("data:image/jpeg;base64,","",$user_icon);
	    $user_icon = base64_encode($user_icon);
	    // var_dump($user_icon);
	    // exit();
	}
		foreach($_POST as $key => $val){
			$$key=trim(htmlspecialchars($val)); 
		}
	$user_id = $_SESSION['user_id'];
	if (empty($user_id)) {
		header('Location: ../login/login.php');
	}
	try {
			$pdo = db_connect();
			if (empty($user_icon)) {
				$sql = "UPDATE user SET user_name = ? WHERE user_id = ?";
				$sql = $pdo->prepare($sql);
	    		$sql->execute(array($user_name,$user_id));
				header('Location: ../post/post_list.php');
				var_dump("名前だけ変更しました");
				exit;
			}
			if (isset($_FILES["user_icon"])) {
			    //var_dump($user_icon);
				$sql = "UPDATE user SET user_name = ?, icon = ? WHERE user_id = ?";
				$sql = $pdo->prepare($sql);
	    		$sql->execute(array($user_name,$user_icon,$user_id));
				header('Location: ../post/post_list.php');
				var_dump("画像と名前を変更しました");
    			exit;
			}
		} catch (PDOException $e) {
			exit('データベース接続失敗。'.$e->getMessage());
		}
?>
