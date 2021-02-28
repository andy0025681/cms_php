<!DOCTYPE HTML>
<!--
	Binary by TEMPLATED
	templated.co @templatedco
	Released for free under the Creative Commons Attribution 3.0 license (templated.co/license)
-->
<html>

<head>
	<title>Generic - Binary by TEMPLATED</title>
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
	</header>

	<!-- Nav -->
	<nav id="menu">
		<ul class="links">
			<li><a href="">Home</a></li>
			<li><a href="">Generic</a></li>
			<li><a href="">Elements</a></li>
		</ul>
	</nav>

	<!-- Main -->
	<form method="POST" action="" enctype="multipart/form-data">
		<p>
			職員編號: <?=$staffCode; ?><br>
			申請人員: <?=$staffName; ?><br>
		</p>
		<p>
			<label>假別: </label>
			<select id="leaveType" name="leaveType" required>';
				<option value="">請選擇</option>
				<?=$leaveType; ?>
			</select>
		</p>
		<p>
			<label>職務代理人:</label>
			<select id="agent" name="agent" required>';
				<option value="">請選擇</option>
				<?=$agent; ?>
			</select>
		</p>
		<p>
			<label>起始日: </label>
			<input type="date" id="startDay" name="startDay" value=<?=$today; ?> min=<?=$today; ?>>
			<input type="time" id="startTime" name="startTime" value="09:00" min="09:00" max="18:00" step="1800">
		</p>
		<p>
			<label>結束日: </label>
			<input type="date" id="endDay" name="endDay" value=<?=$today; ?> min=<?=$today; ?>>
			<input type="time" id="endTime" name="endTime" value="18:00" min="09:00" max="18:00" step="1800">
		</p>
		<p>
			<label>請假原因: </label><br>
			<textarea cols="50" rows="5" id="reason" name="reason" required></textarea>
		</p>
		<p>
			<label>附件: </label><br>
			<input type="file" name="annex">
		</p>
		<input type="submit" name="leave_send" value="送出">
	</form>
	<strong><?=$notices; ?></strong>
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