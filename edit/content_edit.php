<?php
    include_once("../common/db_connect.php");
    $pdo = db_connect();
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
        <li><a href="../index.php"><i class="fas fa-home"></i><span>Home</span></a></li>
        <li><a href="../register/register.php"><i class="fas fa-user"></i><span>Register</span></a></li>
        <li><a href="../login/login.php"><i class="fas fa-sign-in-alt"></i><span>Login</span></a></li>
        <li><a href="profile_edit.php"><i class="fas fa-user-cog"></i><span>Profile</span></a></li>
        <li><a href="../post/post_list.php"><i class="far fa-comments"></i><span>Post</span></a></li>
      </ul>
    </nav>
  </header>
<main>
  <div id="regi_info" class="element js-animation">
    <h1>レビュー編集</h1>
    <form name="form1" method="post">
      <label for="box">review</label>
      <textarea name="post_edit" cols="15" rows="4" id="post_edit" placeholder=""><?php echo $content;?></textarea>
      <p><input type="hidden" name="post_id" value="<?php echo $post_id;?>"></p>
      <input type="submit" value="編集する" id="btn">           
    </form>
  </div>
    <script type="text/javascript">
      (function(){
        var post_id = "<?php echo $post_id; ?>";
        var post_edit = "<?php echo $content; ?>";
        console.log(post_id);
        document.querySelector('#btn').onclick = function(){
          var post_edit = document.form1.post_edit.value;
          document.getElementById('post_edit').textContent = post_edit;
          if (!post_edit.match(/\S/g)){
            alert('記述に問題があります。訂正してください。');
          } else {
            post_edit.trim();
            // 半角#を全角＃に変換
            var sharp = post_edit.replace( /#/g , "＃" ) ;
            if (window.confirm("編集を完了しますか？\n"+post_edit)) { 
              var pram = "/buono/edit/edit_result.php?post_id="+post_id+"&post_edit="+sharp;
              location.href = pram;
            }
            console.log(post_edit);
            return false;
          }
        }
      })();
    </script>
</main>
  <footer>
    <address>&copy;2019 buono All Rights Reserved.</address>
  </footer>
  <script src="../js/all.js"></script>
  <script src="../js/sc_ani.js"></script>
</body>
</html>

