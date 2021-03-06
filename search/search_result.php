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
       <script src="../js/flickity.pkgd.min.js"></script>
    <link rel="stylesheet" href="../css/flickity.css">
    <link rel="stylesheet" href="../css/flickity.min.css">
    <link rel="stylesheet" href="../css/common.css" type="text/css">
    <link rel="stylesheet" href="../css/post_list.css" type="text/css">
    <link rel="stylesheet" href="../css/post_form.css" type="text/css">
    <link rel="stylesheet" href="../css/search_form.css" type="text/css">
    <link rel="stylesheet" href="../css/animation.css" type="text/css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=M+PLUS+Rounded+1c&display=swap">
	<title>Buono:検索結果</title>
</head>
<body>
  <header>
    <nav id="menu">
      <ul>
        <li><a href="../index.php"><i class="fas fa-home"></i><span>Home</span></a></li>
      <?php 
        if (isset($_SESSION['user_id'])) {
          echo '
          <li><a href="../edit/profile_edit.php"><i class="fas fa-user-cog"></i><span>Edit</span></a></li>
          <li><a href="../login/logout.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
          ';
        }elseif (!isset($_SESSION['user_id'])) {
          echo '
          <li><a href="../register/register.php"><i class="fas fa-user"></i><span>Register</span></a></li>
          <li><a href="../login/login.php"><i class="fas fa-sign-in-alt"></i><span>Login</span></a></li>
          ';
        }
      ?>
        <li><a href="../post/post_list.php"><i class="far fa-comments"></i><span>Post</span></a></li>
        <li><a id="search_button" href="#"><i class="fas fa-search"></i><span>Search</span></a></li></li>
      </ul>
    </nav>
  </header>
<main>
	<div id="result" class="element js-animation"><i class="fas fa-search"></i>　検索ワード<?php echo "：".$storage; ?></div>
	</br>
<?php
	while ($row = $stmt->fetch()):
		$food_name = $row['food_name'] ? $row['food_name'] : '(無題)';
?>
		<div id="TimeLine" class="element js-animation">			
			<div id="Post_content">
			<div class="name">
				<?php 
					if(empty($row['content']) == null){
						echo '<div class="icon">
			                    <img src="data:image/jpg;base64,' . $row['icon'] . ' ">
			                    <div class="icon_name"> '.$row['user_name'].'</div>
			                  </div>';
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

		        $result_data = $sql->fetchAll(PDO::FETCH_ASSOC);
		        //var_dump($result_data);
		        ?>
		        <?php if(count($result_data)):?>
		        <?php if ($post == $result_data[0]['post_id']):?>
		          <div class="photo_space">
		            <div class="content_photo flickity-syncer">
		              <?php

		              $output_html = '';
		              for($slide_num=0; $slide_num<count($result_data); $slide_num++){
		                $output_html .= '<img src="data:image/jpeg;base64,' . $result_data[$slide_num]['data'] . '" height = 250px;/>';
		              }
		              echo $output_html;
		              $output_html = '';
		              ?>
		            </div>
		          </div>
		        <?php endif;?>
		        <?php endif;?>

				<div class="info">
		          <div class="btn_space"></div>
		          <div class ="food_name"><i class="fas fa-utensils"></i>　<?php echo $row['food_name'] ?></div>
		          <div class="content"><i class="fas fa-comment"></i>　<?php echo nl2br($row['content'],false) ?></div>
		          <div class="place"><i class="fas fa-location-arrow"></i>　<?php echo $row['place'] ?></div>
		          <div class="date"><i class="fas fa-clock"></i>　<?php echo $row['post_date'] ?></div>
		        </div>
		 <?php 
          //タグが存在した場合の処理
          if (isset($row['tag_name'])) {
            echo '<div class="hash"><i class="fas fa-tag"></i>　' .$row['tag_name']. '</div>';
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
		echo '<div id="page_num">';
		for ($i=1; $i <= $max_page; $i++) { 
			if(empty($_POST['word']) == null){
				echo '<form action="search_result.php?page='. $i .'" name="form'.$i.'" method="post"><input type="hidden" name="word" value="'.$_POST['word'].'" /><a href="javascript:form'.$i.'.submit()">'.$i. '</a></form>&nbsp;';
			}else if (empty($_POST['food_name']) == null) {
				echo '<form action="search_result.php?page='. $i .'" name="form'.$i.'" method="post"><input type="hidden" name="word" value="'.$_POST['food_name'].'" /><a href="javascript:form'.$i.'.submit()">'.$i. '</a></form>&nbsp;';
			}
		}
		echo '</div>';
	?>
	</div>
</main>
<!-- ↓追加 -->
<div id="flex-area-s">
  <div id="search_form">
    <div class="element js-animation">
      <p id="top_form_ms"><i class="fas fa-search"></i>検索　</p>
      <form action="../search/search_result.php" name="form1" method="post">
        <input type="text" name="food_name" placeholder="料理名かタグを入力してください" size="24" maxlength="20" id="food_name">
        <br />
        <!-- <input type="submit" value="検索" id="btn"> -->
        <button type="submit" class="btn">
            検索
        </button>
      </form>
    </div>
  </div>
</div
  <script>
	  //記述の問題のアラート
	  (function(){
	    document.querySelector('#btn').onclick = function(){
	      var food_name = document.form1.food_name.value;
	      document.querySelector('#food_name').textContent = food_name;
	      if (!food_name.match(/\S/g)){
	        alert('記述に問題があります。訂正してください。');
	        return false;
	      } 
	    }
	  })();

	  //アニメーション
	  (function(){
	    function showElementAnimation() {
	      var element = document.getElementsByClassName('js-animation');
	      if(!element) return; // 要素がなかったら処理をキャンセル      
	      var showTiming = window.innerHeight > 768 ? 200 : 40; // 要素が出てくるタイミングはここで調整
	      var scrollY = window.pageYOffset;
	      var windowH = window.innerHeight;
	    for(var i=0;i<element.length;i++) { 
	      var elemClientRect = element[i].getBoundingClientRect(); var elemY = scrollY + elemClientRect.top; if(scrollY + windowH - showTiming > elemY) {
	        element[i].classList.add('is-show');
	      } else if(scrollY + windowH < elemY) {
	        // 上にスクロールして再度非表示にする場合はこちらを記述
	        element[i].classList.remove('is-show');
	      }
	    }
	  }
	  showElementAnimation();
	  window.addEventListener('scroll', showElementAnimation);
	})();
  </script>
</div>
	<footer>
		<address>&copy;2019 buono All Rights Reserved.</address>
	</footer>
  <script src="../js/jquery-2.1.4.min.js"></script>
  <script src="../js/post-form.js"></script>
  <script src="../js/sc_ani.js"></script>
  <script src="../js/all.js"></script>
  <script src="../js/search-form.js"></script>
  <script src="../js/photo_rule.js"></script>
  <script src="../js/post_slide.js"></script>
  <script src="../js/jquery-2.1.4.min.js"></script>
</body>
</html>
