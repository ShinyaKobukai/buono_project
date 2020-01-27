<?php
  include_once("../common/db_connect.php");
  session_start();
  if (isset($_SESSION['user_id'])) {
    $login_user = $_SESSION['user_id'];
  }elseif (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
  }
  $pdo = db_connect();
  $post_id = intval($_GET['post_id']);
  $content = trim(htmlspecialchars($_GET["post_edit"]));
  try {
    $updata_stmt = $pdo->prepare(
      "UPDATE post SET content=:content WHERE post_id=:post_id AND user_id=:login_user"
    );
    $updata_stmt->bindParam(':post_id',$post_id,PDO::PARAM_INT);
    $updata_stmt->bindParam(':content',$content,PDO::PARAM_STR);
    $updata_stmt->bindParam(':login_user',$login_user,PDO::PARAM_STR);
    $updata_stmt->execute();
    $user_stmt = $pdo->prepare(
      "SELECT user_name,post.post_id,food_name,content,data,post_date FROM post LEFT OUTER JOIN user ON user.user_id = post.user_id LEFT OUTER JOIN photo_data ON post.post_id = photo_data.post_id ORDER BY post_date DESC ;"
    );
    $user_stmt->execute();

    header("Location: ../post_list.php");
    exit;
  } catch (PDOException $e) {
    exit('データベース接続失敗。'.$e->getMessage());
  }
?>