<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/common.css" type="text/css">
    <link rel="stylesheet" href="../css/register.css" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=M+PLUS+Rounded+1c&display=swap" rel="stylesheet">
    <title>Buono -プロフィール編集画面-</title>
</head>
<body>
  <header>
    <nav id="menu">
      <ul>
        <li><a href="../index.php"><i class="fas fa-home"></i>Home</a></li>
        <li><a href="login/register.html"><i class="fas fa-user"></i>Register</a></li>
        <li><a href="post_list.php"><i class="far fa-comments"></i>Post</a>
        <li><a href=""><i class="fas fa-search"></i>Search</a></li>
        <li><a href="profile_edit.html"><i class="fas fa-user-cog"></i>Profile</a></li>
        <li><a href="../login/logout.php"><i class="fas fa-sign-in-alt"></i> <span>Logout</span></a></li>
      </ul>
    </nav>
  </header>
<main>
  <div class="regi_info">
    <h1>プロフィール設定</h1>
    <form action="profile_edit.php" method="POST" id = "form" enctype="multipart/form-data">
      <label for="box">新しいユーザー名</label>
      <input type="text" name="user_name" value="" id="box"/>
      <label for="box">新しいユーザーアイコン</label>
      <input name="user_icon" type="file">
      <input type="submit" name="edit"  value="変更"  />
    </form>
  </div>
</main>
  <footer>
    <address>&copy;2019 buono All Rights Reserved.</address>
  </footer>
  <script src="../js/all.js"></script>
</body>
</html>
