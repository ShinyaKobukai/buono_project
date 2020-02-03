<?php 
  include("../common/function.php");

 ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="img/favicon.ico">
    <link rel="stylesheet" href="../css/common.css" type="text/css">
    <link rel="stylesheet" href="../css/register.css" type="text/css">
    <link rel="stylesheet" type="text/css" href="../css/animation.css">
    <link href="https://fonts.googleapis.com/css?family=M+PLUS+Rounded+1c&display=swap" rel="stylesheet">
    <title>Buono -新規登録-</title>
</head>
<body>
  <header>
    <nav id="menu">
      <ul>
        <li><a href="../index.php"><i class="fas fa-home"></i><span>Home</span></a></li>
      <?php
        //menu();
        if(!empty($_SESSION['user_id'])){
          echo '<li><a href="../post/post_list.php"><i class="far fa-comments"></i><span>Post</span></a></li>
                <li><a href="../login/logout.php"><i class="fas fa-sign-in-alt"></i><span>Logout</span></a></li>
                ';
        }else{
          echo '<li><a href="../login/login.php"><i class="fas fa-sign-in-alt"></i><span>Login</span></a></li>';
        }
      ?>
      </ul>
    </nav>
  </header>
<main>
  <div id="regi_info" class="element js-animation">
    <h1>新規登録</h1>
    <form action="register_proce.php" method="POST" id = "form">
      <label for="box">ユーザーID</label>
      <input type="text" name="user_id" value="" id="box"/>
      <label for="user_name">ユーザー名</label>
      <input type="text" name="user_name" value="" id="user_name" />
      <label for="password">パスワード</label>
      <input type="password" name="password" value="" id="password" />
      <label for="re_pass">パスワード(再入力)</label>
      <input type="password" name="re_pass" value="" id="re_pass"/>
      <input type="submit" name="register"  value="新規登録"  />
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
