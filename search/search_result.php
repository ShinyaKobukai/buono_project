<?php
  include_once("../common/db_connect.php");
  session_start();
  if (isset($_SESSION['user_id'])) {
    $login_user = $_SESSION['user_id'];
  }
	$pdo = db_connect(); 

	if(empty($_POST['word']) == null){
		$input = $_POST['word'];
	}else if (empty($_POST['food_name']) == null) {
		$input = $_POST['food_name'];
	}
	$num = 10;
	$page = 0;
	if(isset($_GET['page']) && $_GET['page'] > 0){
		$page = intval($_GET['page']) -1;
	}

	$storage = $input;
	try{
		if(strpos($input,'#') !== false){
			$input = '%'.$input.'%';
			$stmt = $pdo->prepare("
				SELECT * FROM post,user,tag WHERE post.user_id = user.user_id AND tag.tag_name LIKE :tag ORDER BY post_date DESC LIMIT :page, :num
			");
			$page = $page * $num;
			$stmt->bindParam(':tag', $input,PDO::PARAM_STR);
			$stmt->bindParam(':page', $page, PDO::PARAM_INT);
			$stmt->bindParam(':num', $num, PDO::PARAM_INT);
			$stmt->execute();
		}else{
			$input = '%'.$input.'%';
			$stmt = $pdo->prepare("SELECT * FROM post,user WHERE post.user_id = user.user_id AND post.food_name LIKE :food_name ORDER BY post_date DESC LIMIT :page, :num");
			$page = $page * $num;
			$stmt->bindParam(':food_name', $input,PDO::PARAM_STR);
			$stmt->bindParam(':page', $page, PDO::PARAM_INT);
			$stmt->bindParam(':num', $num, PDO::PARAM_INT);
			$stmt->execute();
		}
	}catch(PDOException $e){
		echo "エラー：" . $e->getMessage();
	}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/common.css" type="text/css">
    <link rel="stylesheet" href="../css/search_result.css" type="text/css">
    <link rel="stylesheet" href="../css/post_form.css" type="text/css">
    <link rel="stylesheet" href="../css/search_form.css" type="text/css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=M+PLUS+Rounded+1c&display=swap">
	<title>Buono:検索結果</title>
</head>
<body>
  <header>
    <nav id="menu">
      <ul>
        <li><a href="../index.php"><i class="fas fa-home"></i>ホーム</a></li>
      <?php 
        if (isset($_SESSION['user_id'])) {
          echo '
          <li><a href="../edit/profile_edit.html"><i class="fas fa-user-cog"></i>プロフィール編集</a></li>
          <li><a href="../login/logout.php"><i class="fas fa-sign-out-alt"></i>ログアウト</a></li>
          ';
        }elseif (!isset($_SESSION['user_id'])) {
          echo '
          <li><a href="../login/register.php"><i class="fas fa-user"></i>アカウント作成</a></li>
          <li><a href="../login/login.php"><i class="fas fa-sign-in-alt"></i>ログイン</a></li>
          ';
        }
      ?>
        <li><a href="../post_list.php"><i class="far fa-comments"></i>ポスト</a></li>
        <li><a id="search_button" type="button"><i class="fas fa-search"></i>検索</a></li>
      </ul>
    </nav>
  </header>
<main>
	<div id="result"><img src="../img/loope.png" alt="">　検索ワード　<?php echo "：".$storage; ?></div>
	</br>
<?php
	while ($row = $stmt->fetch()):
		$food_name = $row['food_name'] ? $row['food_name'] : '(無題)';
?>
		<div id="TimeLine">			
			<div id="Post_content">
			<div class="name">
				<?php 
					if(empty($row['content']) == null){
						echo '<div class="icon"><img src="data:image/jpg;base64,' . $row['icon'] . '">'.$row['user_name'].'</div>'; 
					}
				?>
			</div>
					<?php 
							$post = $row['post_id'];
							try{
								$sql = $pdo->prepare("SELECT p.post_id,p.data FROM photo_data AS p,post WHERE :post = p.post_id AND p.post_id = post.post_id ORDER BY post_id DESC");
								$sql->bindParam(':post',$post,PDO::PARAM_INT);
								$sql->execute();
							}catch(PDOException $e){
								echo "エラー：" . $e->getMessage();
							}
					while ($line = $sql->fetch()) {
						$photo = $line['post_id'];
						if ($post == $photo) {
							if(empty($line['data'])==null){
														echo '<div class="content_photo">
																		<img src="data:image/jpeg;base64,' . $line['data'] . '" height="auto" width="45%">
																	</div>';
							}
						}
					}
					?>
				<div class="info">
					<div class ="food_name"><img src="../img/food_menu.png" alt="menu:" width="16" height="16"><?php echo $row['food_name'] ?></div>
					<div class="place"><img src="../img/balloon.png" alt="場所" width="16" height="16"><?php echo $row['place'] ?></div>
					<div class="date"><img src="../img/clock.png" alt="date:" width="16" height="16"><?php echo $row['post_date'] ?></div>
				</div>
				<div class="content"><img src="../img/content.png" alt="review:" width="16" height="16">　<?php echo nl2br($row['content'],false) ?></div>
				<?php 
          //タグが存在した場合の処理
          if (isset($row['tag_name'])) {
            echo '<div class="hash">' .$row['tag_name']. '</div>';
          } 
          //ログインの中ユーザーのみ削除と編集のボタンを出す
          if ($login_user == $row['user_id']) {
            echo 
              '<div class="button">
                <div class="edit"><a href="edit/content_edit.php?content='.$row['content'].'&amp;post_id='.$row['post_id'].'" class="btn-flat-border">編集</a></div>
                <div class="delete"><a href="edit/content_delete.php?post_id='.$row['post_id'].'">削除</a></div>
              </div>';
          }else{
            echo '<div class="message"><a href="chat/chat_create.php?user_id='.$row['user_id'].'">DM</a></div>';
          }
        ?>
			</div>
		</div>
<?php
	endwhile;

	//ページ数の表示
	try{
		//プリペアドステートメント作成
		$stmt = $pdo->prepare("SELECT COUNT(*) FROM post");
		//クエリの実行
		$stmt->execute();
	}catch(PDOException $e){
		echo "えらー：" . $e->getMessage();
	}

	//コメントの件数を取得
	$comments = $stmt->fetchColumn();
	//ページ数を計算
	$max_page = ceil($comments / $num);
		echo '<div id="page">';
		for ($i=1; $i <= $max_page; $i++) { 
			if(empty($_POST['word']) == null){
				echo '<form action="search_result.php?page='. $i .'" name="form'.$i.'" method="post"><input type="hidden" name="word" value="'.$_POST['word'].'" /><p><a href="javascript:form'.$i.'.submit()">'.$i. '</a></p></form>&nbsp;';
			}else if (empty($_POST['food_name']) == null) {
				echo '<form action="search_result.php?page='. $i .'" name="form'.$i.'" method="post"><input type="hidden" name="word" value="'.$_POST['food_name'].'" /><p><a href="javascript:form'.$i.'.submit()">'.$i. '</a></p></form>&nbsp;';
			}
		}
		echo '</div>';
	?>
	</div>
</main>
<div id="flex-area-s">
  <div id="search_form">
    <p id="top_form_ms">検索</p>
    <form action="search_result.php" method="post">
      <p><input type="text" name="food_name" placeholder="料理名かタグを入力してください" size="24" maxlength="20"></p>
      <p><input type="submit" value="検索"></p>
    </form>
  </div>
</div>
	<footer>
		<address>&copy;2019 buono All Rights Reserved.</address>
	</footer>

  <script src="../js/jquery-2.1.4.min.js"></script>
  <script src="../js/post-form.js"></script>
  <script src="../js/search-form.js"></script>
  <script src="../js/all.js"></script>
</body>
</html>