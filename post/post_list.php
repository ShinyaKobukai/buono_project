<?php
  include_once("../common/db_connect.php");
  session_start();
  if (isset($_SESSION['user_id'])) {
    $login_user = $_SESSION['user_id'];
  }
  //1ページに表示させるコメントの数
  $num = 10;
  //ページ数が指定されているとき
  $page = 0;
  if(isset($_GET['page']) && $_GET['page'] > 0){
    $page = intval($_GET['page']) -1;
  }
  try {
    //データベースに接続
    $pdo = db_connect();
    //プリペアドステートメントを作成
    $user_stmt = $pdo->prepare(
      " SELECT
        user.user_id,
        user.user_name,
        user.icon,
        post.post_id,
        post.food_name,
        post.content,
        post.place,
        post.post_date,
        tag.tag_id,
        tag.tag_name
        FROM 
        user,
        post
        left outer join
        (post_tag join tag on tag.tag_id = post_tag.tag_id)
        on post.post_id = post_tag.post_id
        WHERE 
        post.user_id = user.user_id
        ORDER BY 
        post_date 
        DESC
        LIMIT 
        :page, :num"  
    );
    //パラメータの割り当て
    $page = $page * $num;
    $user_stmt->bindParam(':page', $page, PDO::PARAM_INT);
    $user_stmt->bindParam(':num', $num, PDO::PARAM_INT);
    //クエリの実行
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
    <script src="../js/flickity.pkgd.min.js"></script>
    <link rel="icon" href="img/favicon.ico">
    <link rel="stylesheet" href="../css/flickity.css">
    <link rel="stylesheet" href="../css/flickity.min.css">
    <link rel="stylesheet" href="../css/common.css" type="text/css">
    <link rel="stylesheet" href="../css/search_form.css" type="text/css">
    <link rel="stylesheet" href="../css/post_form.css" type="text/css">
    <link rel="stylesheet" href="../css/post_list.css" type="text/css">
    <link rel="stylesheet" href="../css/animation.css" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=M+PLUS+Rounded+1c&display=swap" rel="stylesheet">
    <title>Buono -投稿一覧-</title>
</head>
<body>
  <header>
    <nav id="menu">
      <ul>
        <li><a href="../index.php"><i class="fas fa-home"></i>Home</a></li>
        <li><a id="search_button" href="#"><i class="fas fa-search"></i>Search</a></li>
        <li><a href="../edit/profile_edit.php"><i class="fas fa-user-cog"></i>Profile</a></li>
        <li><a href="../login/logout.php"><i class="fas fa-sign-in-alt"></i><span>Logout</span></a></li>
        <li><a href="../chat/message_list.php"><i class="fas fa-envelope"></i><span>Message</span></a></li>
      </ul>
    </nav>
  </header>
<main>
<?php
  while ($row = $user_stmt->fetch()):
    $food_name = $row['food_name'] ? $row['food_name'] : '(無題)';
?>
    <div id="TimeLine" class="element js-animation">     
      <div id="Post_content">
      <div class="name" >
        <?php 
          if(empty($row['content']) == null){
            echo '<div class="icon">
                    <div class="icon_img">
                      <img src="data:image/jpg;base64,' . $row['icon'] . ' ">
                    </div>
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
          if (isset($row['tag_name'])) :?>
          <div class="hash"><i class="fas fa-tag"></i>
            <?php echo $row['tag_name'];?>
          </div>
        <?php endif;?>
        <?php
          //ログインの中ユーザーのみ削除と編集のボタンを出す
          if ($login_user == $row['user_id']) {
            $del_url = '../edit/check_delete.php?post_id='.$row['post_id'];
            $edit_url = '../edit/content_edit.php?post_id='.$row['post_id'];
            echo 
              '<div class="button">
                <div class="edit">
                  <a href="'.$edit_url.'">編集</a>
                  </div>
                <div class="delete"><a href="'.$del_url.'" class="del" id="del">削除</a></div>
              </div>';
          }else{
            echo '<div class="message"><a href="../chat/chat_create.php?user_id='.$row['user_id'].'">DM</a></div>';
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
  echo '<div id = "page_num">';
  for ($i=1; $i <= $max_page; $i++) { 
    echo '<a href="../post_list.php?page='. $i .'">'.$i. '</a>&nbsp;';
  }
  echo '</div>';

?>
</main>
<div id="post_button">
  <button>
    <i class="fas fa-pen"></i>
  </button>
</div>
<div id="black-layer"></div>
<div id="flex-area">
  <div id="post_form" class="element js-animation">
    <div id ="menu_name">
      <p id="top_form">投稿</p>
      <form action="post_write.php" enctype="multipart/form-data" method="post">
          <p><input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id'];?>"></p></br>
          <div id="food_space">
            <div id="food_icon"><i class="fas fa-utensils"></i></div>
            <div id="food_text">
              <input type="text" name="food_name" placeholder="メニュー名（必須）50文字以内" maxlength="50" size="21">
            </div>
          </div>
          <div id ="content_space">  
            <div id="content_icon"><i class="fas fa-comment"></i></div>
            <div id="content_text">
              <textarea name="content" placeholder="感想（必須）500文字以内" rows="4" cols="17" maxlength="500"></textarea>
            </div>
          </div>
          <div id="place_space">
            <div id="place_icon"><i class="fas fa-location-arrow"></i></div>
            <div id="place_text">
              <input type="text" name="place" placeholder="場所（任意）50文字以内" maxlength="50" size="21"> 
            </div>
          </div>
          <div id ="write">
            <input type="file" name="photo[]" id="photo" multiple="multiple" accept="image/jpeg,*.jpg">
            <input type="hidden" id="base64" name="date" value="">
            <input type="submit" value="投稿する"></br>
          </div> 
      </form> 
    </div>
  </div>
</div>
<!-- ↓追加 -->
<div id="flex-area-s">
  <div id="search_form">
    <div class="element js-animation">
      <p id="top_form_ms">検索</p>
      <form action="../search/search_result.php" name="form1" method="post">
        <input type="text" name="food_name" placeholder="料理名かタグを入力してください" size="24" maxlength="20" id="food_name">
        <input type="submit" value="検索" id="btn">
      </form>
    </div>
  </div>
</div>
  <footer>
    <address>&copy;2019 buono All Rights Reserved.</address>
  </footer>
  <script src="../js/jquery-2.1.4.min.js"></script>
  <script src="../js/sc_ani.js"></script>
  <script src="../js/search_alert.js"></script>
  <script src="../js/post-form.js"></script>
  <script src="../js/all.js"></script>
  <script src="../js/search-form.js"></script>
  <script src="../js/photo_rule.js"></script>
  <script src="../js/post_slide.js"></script>
  <script src="../js/jquery-2.1.4.min.js"></script>
</body>
</html>
