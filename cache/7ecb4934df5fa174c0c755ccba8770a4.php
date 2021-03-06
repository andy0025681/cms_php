<!DOCTYPE HTML>
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
	<section id="">
		<div class="inner center" style="width: 30%;">
			<form method="POST" action="">
				<p>
					<label>職員: </label> <?=$desknum; ?> <?=$eName; ?> <?=$eLastName; ?>
				</p>
				<p>
					<label>起始日: </label>
					<input type="date" id="resignDay" name="resignDay" value="<?=$today; ?>" min="<?=$today; ?>">
					<label>結束日: </label>
					<input type="date" id="returnDay" name="returnDay" value="<?=$today; ?>" min="<?=$today; ?>">
				</p>
				<input type="submit" name="leave_without_pay_send" value="送出">
			</form>
		</div>
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