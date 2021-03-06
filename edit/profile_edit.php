<?php
  include_once("../common/db_connect.php");
  session_start();
  $user_id = $_SESSION['user_id'];
  foreach($_POST as $key => $val){
    $$key=trim(htmlspecialchars($val)); 
  }
  try {
      $pdo = db_connect();
      $sql = $pdo->prepare(
        " SELECT
          user_name
          FROM
          user
          WHERE
          user_id = :user_id "
        );
      $sql->bindParam(':user_id', $user_id, PDO::PARAM_STR);
      $sql->execute();
    } catch (PDOException $e) {
      exit('データベース接続失敗。'.$e->getMessage());
    }
    $row = $sql->fetch();
    $user_name = $row['user_name'];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/common.css" type="text/css">
    <link rel="stylesheet" href="../css/register.css" type="text/css">
    <link rel="stylesheet" href="../css/footer_patch.css" type="text/css">
    <link rel="stylesheet" type="text/css" href="../css/animation.css">
    <link href="https://fonts.googleapis.com/css?family=M+PLUS+Rounded+1c&display=swap" rel="stylesheet">
    <title>Buono -プロフィール編集画面-</title>
</head>
<body>
  <header>
    <nav id="menu">
      <ul>
        <li><a href="../index.php"><i class="fas fa-home"></i><span>Home</span></a></li>
        <li><a href="../post/post_list.php"><i class="far fa-comments"></i><span>Post</span></a>
        <li><a href="../login/logout.php"><i class="fas fa-sign-in-alt"></i><span>Logout</span></a></li>
      </ul>
    </nav>
  </header>
<main>
  <div id="regi_info" class="element js-animation">
    <h1>プロフィール設定</h1>
    <form action="profile_proce.php" name="form1" method="post" enctype=multipart/form-data>
      <label for="box">新しいユーザー名</label>
      <input type="text" name="user_name" value="<?php echo $user_name ?>" id="user_name"/>
      <label for="box">新しいユーザーアイコン</label>
      </br>
      <input name="user_icon" type="file" id="user_icon">
      </br>
      <input type="submit" value="変更" id="btn" />    
    </form>
  </div>
  <script src="../js/all.js"></script>
  <script>
  (function(){
    document.querySelector('#btn').onclick = function(){
      var user_name = document.form1.user_name.value;
      var user_icon = document.form1.user_icon.value;
      document.querySelector('#user_name').textContent = user_name;
      if(document.querySelector('#user_icon').value == null && document.querySelector('#user_name').value == null){
        alert('変更内容が存在しません');
        return false;
      }
      if (!user_name.match(/\S/g)){
        alert('記述に問題があります。訂正してください。');
        return false;
      } 
    }
  })();
  </script>
</main>
  <footer>
    <address>&copy;2019 buono All Rights Reserved.</address>
  </footer>
  <script src="../js/sc_ani.js"></script>
</body>
</html>
