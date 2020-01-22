<?php
  include_once("../common/function.php");
  $pdo = db_connect();
  $post_id = intval($_GET['post_id']);
  $content = trim(htmlspecialchars($_GET["post_edit"]));
  try {
    $updata_stmt = $pdo->prepare(
      "UPDATE post SET content=:content WHERE post_id=:post_id "
    );
    $updata_stmt->bindParam(':post_id',$post_id,PDO::PARAM_INT);
    $updata_stmt->bindParam(':content',$content,PDO::PARAM_STR);
    $updata_stmt->execute();
    $user_stmt = $pdo->prepare(
      "SELECT user_name,post.post_id,food_name,content,data,post_date FROM post LEFT OUTER JOIN user ON user.user_id = post.user_id LEFT OUTER JOIN photo_data ON post.post_id = photo_data.post_id ORDER BY post_date DESC ;"
    );
    $user_stmt->execute();
  } catch (PDOException $e) {
    exit('データベース接続失敗。'.$e->getMessage());
  }

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/common.css" type="text/css">
    <link rel="stylesheet" href="../css/register.css" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=M+PLUS+Rounded+1c&display=swap" rel="stylesheet">
    <title>Buono -投稿編集-</title>
</head>
<body>
  <header>
    <nav id="menu">
      <ul>
        <li><a href="../index.php"><i class="fas fa-home"></i>Home</a></li>
        <li><a href="../login/register.html"><i class="fas fa-user"></i>Register</a></li>
        <li><a href="profile_edit.html"><i class="fas fa-user-cog"></i>Profile</a></li>
        <li><a href="../post_list.php"><i class="far fa-comments"></i>Post</a></li>
      </ul>
    </nav>
  </header>
<main>
  <div class="regi_info">
    <h1>レビュー編集</h1>
    <form action="edit_result.php" method="post">
      <label for="box">編集が完了しました</label></br>
      <p>レビュー：<?php echo $content ?></p>
    </form>
  </div>
</main>
  <footer>
    <address>&copy;2019 buono All Rights Reserved.</address>
  </footer>
  <script src="../js/all.js"></script>
</body>
</html>
