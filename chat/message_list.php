<?php
	session_start();
	$user_id = $_SESSION['user_id'];


	try{
		include_once("../common/db_connect.php");
  	$pdo = db_connect();
		$sql = 'select room_id,person_id,user_id,room_name,status from chat where not(status=3) and ((user_id=? and status!=2) or (person_id=? and status!=1)) order by update_time desc';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($user_id,$user_id));
	} catch (PDOException $e) {
		echo $e->getMessage();
		exit;
	}

	if(isset($_POST['login'])){
		$room_id = $_POST['room_id'];
		$_SESSION['room_id'] = $room_id;
		$room_name = $_POST['room_name'];
		$_SESSION['room_name'] = $room_name;
		header('Location: ../event/php/spa_chat.php');
		// header('Location: chat.php');
    exit;
	}

	if(isset($_POST['exit'])){
		$room_id = $_POST['room_id'];
		$_SESSION['room_id'] = $room_id;
		$person_id = $_POST['person_id'];
		$_SESSION['person_id'] = $person_id;
		header('Location: chat_exit.php');
    exit;
	}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1.0" />
	<title>メッセージ一覧</title>
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<link rel="icon" href="img/favicon.ico" />
	<link rel="stylesheet" href="../css/common.css" type="text/css" />
	<link rel="stylesheet" href="../css/search_form.css" type="text/css" />
	<link rel="stylesheet" href="../css/post_form.css" type="text/css" />
	<link rel="stylesheet" href="../css/post_list.css" type="text/css" />
	<link href="https://fonts.googleapis.com/css?family=M+PLUS+Rounded+1c&display=swap" rel="stylesheet" />
	<style>
	.room_talk{
		position:relative;
		padding:20px;
		border-radius:5px;
		border:1px dotted #ccc;
	}
	.room_talk p{
		margin:10px auto;
		padding:10px;
		border:1px solid #eee;
		border-radius:10px;
		background-color:#ffc;
	}
	.colon::after{
		content:"　：　";
	}
	.flex{
		display:flex;
		justify-content:center;
		align-items:center;
	}
	.flex input[type="submit"]{
		display:block;
		margin:10px;
		padding:10px;
		border:1px solid #eee;
		border-radius:10px;
		width:160px;
		color:#333;
		background-color:#ccc;
		text-align:center;
		cursor:pointer;
	}
	.flex input[type="submit"]:hover{
		border:1px solid #333;
		color:#fff;
		background-color:#333;
	}
	.room_back{
		display:block;
		margin:20px auto;
		padding:10px;
		border:1px solid #eee;
		border-radius:10px;
		width:200px;
		text-align:center;
		text-decoration:none;
	}
	</style>
</head>
<body>
	<h1><?php echo /*$room_name*/'メッセージ' ?></h1>
	<a class="room_back" href="create.php">トークルームを作成</a>
<?php while ($row = $stmt->fetch()): ?>
	<form action="" method="POST" class="room_talk">
		<p><span class="colon">ルームネーム</span><span class="room_name"><?php echo $row['room_name'] ?></span></p>
		<p><span class="colon">メンバー情報</span><span class="room_member"><?php echo $row['person_id'] ?> ／ <?php echo $row['user_id'] ?></span></p>
		<input type="hidden" name="room_id" value="<?php echo $row['room_id'] ?>" />
		<input type="hidden" name="person_id" value="<?php echo $row['person_id'] ?>" />
		<input type="hidden" name="room_name" value="<?php echo $row['room_name'] ?>" />
		<div class="flex">
			<input type="submit" name="login" class="room_login" value="入室	" />
			<input type="submit" name="exit" class="room_exit" value="退室	" />
		</div><!-- /.flex -->
	</form>
<?php	endwhile; ?>
	<a class="room_back" href="../post/post_list.php">Postに戻る</a>
</body>
</html>
