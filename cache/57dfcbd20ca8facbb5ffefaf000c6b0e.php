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
	<script>
		function disableStaffCheck(staffCode, name) {
			var msg = "您確定要將: " + name + " 設定為離職員工嗎?";
			if (confirm(msg)) location.replace(window.document.location.href+'&disableStaff=true&staffCode='+staffCode);
		}
		function showAnnex(name) {
            var input = document.getElementById(name);
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                title = "附件預覽";
                fileType = input.files[0].type.split('/')[0];
                if (fileType == 'image') {
                    nwin = window.open('', "", config = 'height=auto,width=auto'); //新開空白標籤頁
                    nwin.document.write('<img id="blah" src="#" alt="your image" />'); //將內容寫入新標籤頁
                    nwin.document.title = title;
                    reader.onload = function(e) {
                        var img = nwin.document.getElementById('blah');
                        img.setAttribute("src", e.target.result)
                    }
                    reader.readAsDataURL(input.files[0]);
                } else {
                    alert("只能預覽圖片");
                }
            } else alert("附件是空的");
        }
	</script>
	<?=$disableStaffCheck; ?>
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
		<?=$notices; ?>
		<form method="post" action="<?=$url; ?>" enctype="multipart/form-data">
			<?=$result; ?>
		</form>
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