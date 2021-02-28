<!DOCTYPE HTML>
<!--
	Binary by TEMPLATED
	templated.co @templatedco
	Released for free under the Creative Commons Attribution 3.0 license (templated.co/license)
-->
<html>

<head>
	<title>Binary by TEMPLATED</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet" href="public/css/main.css" />
</head>

<body>
	<!-- Header -->
	<header id="header">
		<a href="" class="logo">請假系統</a>
		<nav>
			<a href="#menu">Menu</a>
		</nav>
		<style>
			.center {
				margin: auto;
				width: 60%;
				padding: 10px;
			}
		</style>
	</header>

	<!-- Nav -->
	<nav id="menu">
		<ul class="links">
			<li><a href="">Home</a></li>
			<li><a href="">Generic</a></li>
			<li><a href="">Elements</a></li>
		</ul>
	</nav>

	<!-- Banner -->
	<section id="banner">
		<form method="POST" action="index.php?m=modifyAcc&a=modifyAcc">
			<label style="float:left">新密碼: </label>
			<input type="password" id="password" name="password" placeholder="輸入" required>
			<label style="float:left">請再次輸入密碼: </label>
			<input type="password" id="password_re" name="password_re" placeholder="輸入" required>
			<input type="submit" name="modifyAcc_send" value="送出" style="float:right">
		</form>
		<strong style="float:left"><?=$notices; ?></strong>
	</section>

	<!-- Footer -->
	<footer id="footer">
		<ul class="icons">
			<li><a href="#" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
			<li><a href="#" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
			<li><a href="#" class="icon fa-instagram"><span class="label">Instagram</span></a></li>
		</ul>
		<div class="copyright">
			&copy; Untitled. Design: <a href="https://templated.co">TEMPLATED</a>. Images: <a
				href="https://unsplash.com">Unsplash</a>.
		</div>
	</footer>

	<!-- Scripts -->
	<script src="public/js/jquery.min.js"></script>
	<script src="public/js/jquery.scrolly.min.js"></script>
	<script src="public/js/skel.min.js"></script>
	<script src="public/js/util.js"></script>
	<script src="public/js/main.js"></script>

</body>

</html>