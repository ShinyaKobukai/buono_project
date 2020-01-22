<?php
  include_once("../common/db_connect.php");
  $pdo = db_connect();
  session_start();
  $user_id = $_SESSION['user_id'];
  if(isset($_POST['edit'])){
      if ( empty($_POST['user_name']) ) {
        $_SESSION["msg"] = '入力してください';
        print("入力してください");
        header('Location: profile_edit.html');
        exit;
    }
    foreach($_POST as $key => $val){
      $$key=trim(htmlspecialchars($val)); 
    }
          $pdo = db_connect();
          $sql = "UPDATE user SET user_name = ?, icon = ? WHERE user_id = ?";
          $stmt = $pdo->prepare($sql);
            $stmt->execute(array($user_name,$user_icon,$user_id));

          if (empty($user_icon)) {
            $_SESSION["msg"] = 'アイコンが設定されませんでした。';
            header('Location: profile_edit.php');
              exit;
          }

        } catch (PDOException $e) {
          exit('データベース接続失敗。'.$e->getMessage());
        }
        header('Location: ../post_list.php');
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
        <li><a href="../login/logout.php"><i class="fas fa-sign-in-alt"></i><span>Logout</span></a></li>
      </ul>
    </nav>
  </header>
<main>
  <div class="regi_info">
    <h1>プロフィール設定</h1>
    <form action="profile_edit.php" name="form1" method="POST" id = "form" enctype="multipart/form-data">
      <label for="box">新しいユーザー名</label>
      <input type="text" name="user_name" value="" id="user_name"/>
      <label for="box">新しいユーザーアイコン</label>
      <input name="user_icon" type="file" id="user_icon">
      <input type="button" name="edit" value="変更" onclick="clickBtn();" />
    </form>
  </div>
</main>
  <footer>
    <address>&copy;2019 buono All Rights Reserved.</address>
  </footer>
  <script src="../js/all.js">
    var clickBtn = function(){
      var user_name = document.form1.user_name.value;
      var user_icon = document.form1.user_icon.value;
      document.querySelector('#user_name').textContent = user_name;
      if (!user_name.match(/\S/g)){
        alert('記述に問題があります。訂正してください。');
      } else if(document.querySelector('#user_icon').value!=null){
        <?php
          $user_icon = file_get_contents($_FILES["user_icon"]["tmp_name"]);
          $user_icon = str_replace("data:image/jpeg;base64,","",$user_icon);
          $user_icon = base64_encode($user_icon);
          try {
              $pdo = db_connect();
              $sql = "UPDATE user SET user_name = ?, icon = ? WHERE user_id = ?";
              $stmt = $pdo->prepare($sql);
                $stmt->execute(array($user_name,$user_icon,$user_id));

              if (empty($user_icon)) {
                $_SESSION["msg"] = 'アイコンが設定されませんでした。';
                header('Location: profile_edit.php');
                  exit;
              }

            } catch (PDOException $e) {
              exit('データベース接続失敗。'.$e->getMessage());
            }
            header('Location: ../post_list.php');
          ?>
        }
      }
      post_edit.trim();
      alert('編集が完了しました。'+post_edit);
      if (window.confirm("ポストリストに戻りますか？")) { 
        var pram = "/buono/edit/edit_result.php?post_id="+post_id+"&amp;post_edit="+post_edit;
        location.href = pram;
      }
      console.log(post_edit);
    }
  }
  </script>
</body>
</html>
