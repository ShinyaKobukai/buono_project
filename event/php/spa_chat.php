<?php
session_start();
// include("user_check.php");
$_SESSION['correct'] = base64_encode($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1.0" />
	<title>チャットルーム</title>
	<link rel="stylesheet" href="../css/user_chat.css" />
	<link rel="stylesheet" href="../css/chat1.css" />
	<link rel="stylesheet" href="../../css/animation.css" />
	<link rel="stylesheet" href="../../css/common.css" type="text/css">
</head>
<body data-owner="<?php echo $_SESSION["correct"];?>">
<header>
  <nav id="menu">
    <ul>
      <li><a href="../../index.php"><i class="fas fa-home"></i><span>Home</span></a></li>
      <li><a href="../../edit/profile_edit.php"><i class="fas fa-user-cog"></i><span>Profile</span></a></li>
      <li><a href="../../login/logout.php"><i class="fas fa-sign-in-alt"></i><span>Logout</span></a></li>
      <li><a href="../../chat/message_list.php"><i class="fas fa-envelope"></i><span>Message</span></a></li>
    </ul>
  </nav>
</header>
<div id="wrapper">
	<a href="../../chat/message_list.php">戻る</a>
	<h1 class="theme"></h1>
	<ul id="sample"></ul>
	<footer>

		<input type="hidden" name="cmade" id="cmade" value="<?php echo $_SESSION["correct"];?>" />
		<input type="hidden" name="rid" id="rid" value="<?php echo $_SESSION["room_id"];?>" />

		<div class="uinput">
			<!-- <label for="uinput" class="icon"><i class="far fa-comment-dots"></i></label> -->
			<input type="text" name="uinput" id="uinput" value="" placeholder="チャット発言" />
			<button type="submit" id="submit" name="write_input">発言する</button>
		</div>
		<!-- <button id="reset"><i class="fas fa-undo-alt"></i></button> -->

	</footer>
</div><!-- /#wrapper -->
<script src="../js/all.js"></script>
<script src="../js/spa_chat.js"></script>
<script src="../../js/sc_ani.js"></script>
</body>
</html>
