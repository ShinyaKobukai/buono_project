<?php

	include("common/function.php");
	//login_check();

?>

<!DOCTYPE html>
<html lang="ja">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<title>Buono -Home-</title>
	<link rel="icon" href="img/favicon.ico">
	<link rel="icon" href="img/favicon.ico">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=M+PLUS+Rounded+1c&display=swap" />
	<link rel="stylesheet" href="css/common.css" />
	<link rel="stylesheet" href="css/home.css" />
	<link rel="stylesheet" type="text/css" href="css/animation.css">
</head>
<body>
<header>
	<nav id="menu">
		<ul>
			<li><a href="index.php"><i class="fas fa-home"></i><span>Home</span></a></li>
			<li><a href="register/register.php"><i class="fas fa-user-plus"></i></i> <span>Register</span></a></li>
		<?php
			//menu();
			if(!empty($_SESSION['user_id'])){
				echo '<li><a href="/buono/post/post_list.php"><i class="far fa-comments"></i><span>Post</span></a></li>
							<li><a href="edit/profile_edit.php"><i class="fas fa-user-cog"></i><span>Profile</span></a></li>
							<li><a href="login/logout.php"><i class="fas fa-sign-in-alt"></i> <span>Logout</span></a></li>
							';
			}else{
				echo '<li><a href="login/login.php"><i class="fas fa-sign-in-alt"></i> <span>Login</span></a></li>';
			}
		?>
		</ul>
	</nav>
</header>


	<div class="introduce element js-animation">
		<ul>
			<li><img src="img/ddd.jpg" alt="Food picture showed up here." /></li>
			<li><img src="img/eee.jpg" alt="Food picture showed up here." /></li>
			<li><img src="img/ccc.jpg" alt="Food picture showed up here." /></li>
		</ul>
		<div class="shadow"><span class="catch"><span>Focus</span> <span>on</span> <span>food!!</span></span></div>
	</div><!-- /.introduce -->


<div id="button" class="element js-animation">
	<ul>
		<li><a href="" class="target">1</a></li>
		<li><a href="">2</a></li>
		<li><a href="">3</a></li>
	</ul>
</div><!-- /#button -->

<div class="description element js-animation">
	<div id="des_img"><img src="img/title_b.png" alt="sample"></div>
	<div id="des_txt" class="">
		<h1>Buonoとは</h1><br>
		<p>食べ物に着目した新感覚のSNS</p>
		<p>従来のサービスとは異なり、ユーザー自ら発信するスタイルを実現しました。
			ユーザー自ら発信することによって、自由度が向上します。
			また、ユーザー間でのチャット機能により、自分と食の好みが合うユーザーと
			コミュニケーションをとることができます。
		<p>
	</div>
</div><!-- /.description -->

<script src="js/all.js"></script>
<script src="js/jquery-2.1.4.min.js"></script>
<script src="js/slide.js"></script>
<script src="js/sc_ani.js"></script>
</body>
<footer>
	<address>&copy; 2020 team:Null All Rights Reserved.</address>
</footer>
</div>
</html>
