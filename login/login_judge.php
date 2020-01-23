<?php
  session_start();
  include_once("../common/db_connect.php");
  $pdo = db_connect();
  if(isset( $_GET['msg'])){
    //$er_msg = $_GET['msg'];
  }else{
    //$er_msg = '';
  }


  if(isset($_POST['login'])){
    $password = $_POST['password'];
    $user_id = $_POST['user_id'];
    //print("loginに関するパラメータを受け取りました");
    $_SESSION['user_id'] = $user_id;

    try{
      $sql = 'select count(*) from user where user_id=? and password=?';
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array($user_id,$password));
      $result = $stmt->fetch();
      $stmt = null;
      $pdo = null;

      if($result[0] != 0){
        header('Location: ../post_list.php');
        exit;
      } else {
        // 読み込んだときに自動的にポップアップの内容が書いたformを送信する
        echo '<body onload="document.call.submit()" >
              <form action="login.php" name="call" method="POST">
              <input type="hidden" name="error_message" value="ユーザー名またはパスワードに誤りがあります。" />';
      }
    }  catch (PDOException $e){
      echo $e->getMessage();
      exit;
    }
  }
?>
