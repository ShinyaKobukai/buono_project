<?php

session_start();
if(isset( $_GET['msg'])){
  $er_msg = $_GET['msg'];
}else{
  $er_msg = '';
}

if(isset($_POST['register'])){

  //$room_id = $_POST['room_id'];
  $room_name = $_POST['room_name'];
  $person_id = $_POST['person_id'];
  $user_id = $_SESSION['user_id'];

  try{
    include_once("../common/db_connect.php");
    $pdo = db_connect();
    //$stmt = $db->prepare($pre_sql);
    //$stmt->execute(array($user_id));
    $pre_sql = 'SELECT user_id FROM user WHERE user_id=?';
    $stmt = $pdo->prepare($pre_sql);
    $stmt->execute(array($person_id));
    $result = $stmt->fetch();
    if(($result == true) && ($person_id != $user_id)){

      //データベースに登録
      $sql = 'insert into chat(
        room_id,person_id,user_id,room_name,create_time) 
        values(?,?,?,?,now())';
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array('',$person_id,$user_id,$room_name));
      $room_id = $pdo->lastInsertId('room_id');
      $stmt = null;
      $db = null;

      $_SESSION['room_id'] = $room_id;
      $_SESSION['room_name'] = $room_name;
      $_SESSION['person_id'] = $person_id;

      header('Location: chat.php');
      exit;

    } else {
      $er_msg = '不正な入力です';
    }

  }  catch (PDOException $e){
    echo $e->getMessage();
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="icon" href="img/favicon.ico">
  <link rel="stylesheet" href="../css/common.css" type="text/css">
  <link rel="stylesheet" type="text/css" href="../css/register.css">
  <link rel="stylesheet" type="text/css" href="../css/animation.css">
  <link rel="stylesheet" href="../css/footer_patch.css" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=M+PLUS+Rounded+1c&display=swap" rel="stylesheet">
  <title>チャットルーム作成</title>
</head>
<body>
  <header>
    <nav id="menu">
      <ul>
        <li><a href="../index.php"><i class="fas fa-home"></i><span>Home</span></a></li>
        <li><a href="../edit/profile_edit.php"><i class="fas fa-user-cog"></i><span>Profile</span></a></li>
        <li><a href="../login/logout.php"><i class="fas fa-sign-in-alt"></i><span>Logout</span></a></li>
        <li><a href="../chat/message_list.php"><i class="fas fa-envelope"></i><span>Message</span></a></li>
      </ul>
    </nav>
  </header>
<main>
  <div id="regi_info" class="element js-animation">
    <h1>新規チャット</h1>
    <form action="" method="POST" id = "form">
      <label for="box">ルーム名</label>
      <input type="text" name="room_name" value="" required/>
      <label for="password">相手のID</label>
      <input type="text" name="person_id" value="" required/>
      <br /><br />
      <input type="submit" name="register"  value="作成"  />
      <br /><br />
      <a href="message_list.php" >戻る</a>
    </form>
  </div>
</main>
  <footer>
    <address>&copy;2019 buono All Rights Reserved.</address>
  </footer>
  <script src="../js/all.js"></script>
  <script src="../js/sc_ani.js"></script>
</body>
</html>
