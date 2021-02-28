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
			<form method="POST" action="" enctype="multipart/form-data">
				<p>
					<label>獲得特別假人員: </label>
					<select id="getLeaveStaff" name="getLeaveStaff" required>';
						<option value="">請選擇</option>
						<?=$staffListOption; ?>
					</select>
				</p>
				<p>
					<label>時數: </label>
					<input type="number" style="width: 80px;" id="duration" name="duration" min='0.0' step="0.5" required>
				</p>
				<p>
					<label>獲假原因: </label><br>
					<textarea cols="50" rows="5" id="reason" name="reason" required></textarea>
				</p>
				<p>
					<label>附件: </label><br>
					<input type="file" id="annex" name="annex">
				</p>
				<input type="submit" name="add_leave_send" value="送出">
			</form>
		</div>
		<?=$notices; ?>
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