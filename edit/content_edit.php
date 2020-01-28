<?php
    include_once("../common/function.php");
    $pdo = db_connect();
    //データの受け取り
    $post_id = intval($_GET['post_id']);
    $content = strval($_GET['content']);
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
        <li><a href="../register/register.php"><i class="fas fa-user"></i>Register</a></li>
        <li><a href="../login/login.html"><i class="fas fa-sign-in-alt"></i>Login</a></li>
        <li><a href="profile_edit.php"><i class="fas fa-user-cog"></i>Profile</a></li>
        <li><a href="../php/post_list.php"><i class="far fa-comments"></i>Post</a></li>
      </ul>
    </nav>
  </header>
<main>
  <div class="regi_info">
    <h1>レビュー編集</h1>
    <form name="form1" method="post">
      <label for="box">review</label>
      <textarea name="post_edit" cols="15" rows="4" id="post_edit" placeholder=""><?php echo $content;?></textarea>
      <p><input type="hidden" name="post_id" value="<?php echo $post_id;?>"></p>
      <input type="button" value="編集する" onclick="clickBtn();" />           
    </form>
  </div>
      <script type="text/javascript">
        var post_id = "<?php echo $post_id; ?>";
        var post_edit = "<?php echo $content; ?>";
        console.log(post_id);
        var clickBtn = function(){
          var post_edit = document.form1.post_edit.value;
          document.getElementById('post_edit').textContent = post_edit;
          if (!post_edit.match(/\S/g)){
            alert('記述に問題があります。訂正してください。');
          } else {
            post_edit.trim();
            if (window.confirm("編集を完了しますか？\n"+post_edit)) { 
              var pram = "/buono/edit/edit_result.php?post_id="+post_id+"&post_edit="+post_edit;
              location.href = pram;
            }
            console.log(post_edit);
          }
        }
      </script>
</main>
  <footer>
    <address>&copy;2019 buono All Rights Reserved.</address>
  </footer>
</body>
</html>

