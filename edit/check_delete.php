<?php 
  include_once("../common/db_connect.php");
  //データの受け取り
  $post_id = intval($_GET['post_id']);

  //contentをpost_idを使ってDBから受け取る
  try {
      $pdo = db_connect();
      $sql = "SELECT post.content FROM post WHERE post_id=? ";
      $sql = $pdo->prepare($sql);
      $sql->execute(array($post_id));
      $result_data = $sql->fetchAll(PDO::FETCH_ASSOC);
      $content = $result_data[0]['content'];
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
    <link rel="stylesheet" href="../css/animation.css" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=M+PLUS+Rounded+1c&display=swap" rel="stylesheet">
    <title>Buono -投稿編集-</title>
</head>
<body>
  <header>
    <nav id="menu">
      <ul>
        <li><a href="../index.php"><i class="fas fa-home"></i>Home</a></li>
        <li><a href="../post/post_list.php"><i class="far fa-comments"></i>Post</a></li>
      </ul>
    </nav>
  </header>
<main>
  <div id="regi_info" class="element js-animation">
    <h1>削除しますか？</h1>
    <form name="form1" method="post">
      <label for="box"><?php echo $content ?></label>
      <input type="hidden" name="post_id" value="<?php echo $post_id;?>">
      <div id="ans">
        <?php
          echo '<div id="yesref"><a href="content_delete.php?post_id='.$post_id.'">はい</a></div>';
          echo '<div id="noref"><a href="../post/post_list.php">いいえ</a></div>';
        ?>
      </div>
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