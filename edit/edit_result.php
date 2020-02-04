<?php
  include_once("../common/db_connect.php");
  include_once("../common/value.php");
  $pdo = db_connect();
  $post_id = intval($_GET['post_id']);
  $post_edit = trim(htmlspecialchars($_GET["post_edit"]));
  
  // 全角＃を半角#に戻す
  $content = str_replace('＃', '#', $post_edit);

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

    var_dump($content);
    $tag_read = $pdo->prepare("
      DELETE post_tag, tag FROM post_tag left join tag on post_tag.tag_id = tag.tag_id where post_tag.post_id = :post_id
    ");
    $tag_read->bindParam(':post_id',$post_id,PDO::PARAM_STR);
    $tag_read->execute();
    if(strpos($content,'#') !== false){
      if(strpos($content,' ') !== false){
        $tag = explode(" ",$content);


        for ($tag_num=0; $tag_num < count($tag); $tag_num++) {
          $tag_str = trim($tag[$tag_num]);

          if(strpos($tag_str,'#') !== false){
            $stmt = $pdo->prepare("
              INSERT INTO tag (tag_name) VALUES (:tag)
            ");
            $stmt->execute(array(':tag'=>$tag_str));
            $tag_id = $pdo->lastInsertId('tag_id');
            $tag_stmt = $pdo->prepare("
              INSERT INTO post_tag (post_id,tag_id) VALUES (:post_id,:tag_id);
            ");
            $tag_stmt->bindParam(':post_id',$post_id,PDO::PARAM_STR);
            $tag_stmt->bindParam(':tag_id',$tag_id,PDO::PARAM_STR);
            $tag_stmt->execute();
          }
        }
      }//--if
    }//--if


  } catch (PDOException $e) {
    exit('データベース接続失敗。'.$e->getMessage());
  }
  header("Location: ../post/post_list.php");
?>