<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/common.css" type="text/css">
    <link rel="stylesheet" href="../css/register.css" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=M+PLUS+Rounded+1c&display=swap" rel="stylesheet">
    <title>Buono -ログイン-</title>
</head>
<body>
  <header>
    <nav id="menu">
      <ul>
        <li><a href="../index.php"><i class="fas fa-home"></i>Home</a></li>
        <li><a href="../register/register.php"><i class="fas fa-user"></i>Register</a></li>
      </ul>
    </nav>
  </header>
<main>
  <div class="regi_info">
    <h1>ログイン</h1>
    <form action="login_judge.php" method="POST" id = "form">
      <label for="box">ユーザーID</label>
      <input type="text" name="user_id" value="" id="box" required/>
      <label for="password">パスワード</label>
      <input type="password" name="password" value="" id="password" required />
      <input type="submit" name="login"  value="ログイン"  required />
    </form>
  </div>
</main>
  <footer>
    <address>&copy;2019 buono All Rights Reserved.</address>
  </footer>
  <script src="../js/all.js"></script>
</body>
</html>

<?php
  if (isset($_POST['error_message'])) {
    echo "<script type='text/javascript'>alert('". $_POST['error_message']. "');</script>";
  }
 ?>
