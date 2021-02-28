<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>員工資料編輯</title>
</head>

<body>
	<form method="POST" action="">
		<p>
			<label>中文姓名: </label> <input type="text" id="cName" name="cName" placeholder="輸入" value="<?=$cName; ?>" required>
		</p>
		<p>
			<label for="">英文姓氏: </label> <input type="text" id="eLastName" name="eLastName" value="<?=$eLastName; ?>"
				style="width: 100px;" placeholder="字首大寫" required>
			<label for="">英文名字: </label> <input type="text" id="eName" name="eName" placeholder="字首大寫" value="<?=$eName; ?>"
				style="width: 100px;" required>
		</p>
		<p>
			<label>性別: </label> <?=$genderOption; ?>
		</p>
		<p>
			<label>生日: </label> <input type="date" id="birthday" name="birthday" value="<?=$birthday; ?>">
		</p>
		<p>
			<label>Email: </label> <input type="text" id="email" name="email" style="width: 100px;" placeholder='請輸入'
				value='<?=$email; ?>' required>
			@gmail.com
		</p>
		<p>
			<!-- <label>電話: </label> <input type="tel" id="telephone" name="telephone" placeholder='XX-XXXXXXXX'> -->
		</p>
		<p>
			<label>手機: </label> <input type="number" id="cellPhone" name="cellPhone" placeholder='09XXXXXXXX'
				value="<?=$cellPhone; ?>">
		</p>
		<p>
			<!-- <label>戶籍地址: </label> <input type="text" id="address1" name="address1" value="台北市松山區南京東路四段165號">
				<br><label>居住地址: </label>
				<label>同上</label><input type="radio" id="check1" oninput="sameAddress()">
				<input type="text" id="address2" name="address2" value="台北市松山區南京東路四段165號"> -->
		</p>
		<p>
			<!-- <label>學校: </label> <input type="text" id="school" name="school">
			<label>科系: </label> <input type="text" id="major" name="major"> -->
		</p>
		<p>
			<label>到職日: </label>
			<input type="date" id="firstDay" name="firstDay" value="<?=$firstDay; ?>" required readonly>
		</p>
		<p>
			<label>部門: </label>
			<select id="department" name="department" required>
				<option value="">請選擇</option>
				<?=$departmentListOption; ?>
			</select>
		</p>
		<p>
			<!-- <label>職稱: </label> <input type="text" id="jobTitle" name="jobTitle" value="" required> -->
		</p>
		<p>
			<label>員工編號: </label><?=$staffCode; ?><br>
		</p>
		<p>
			<label>座位分機: </label> <input type="number" id="desknum" name="desknum" value="<?=$desknum; ?>" required>
		</p>
		<p>
			<label>權限: </label>
			<?=$accExplain; ?>
		</p>
		<p>
			<input type="submit" name="emp_edit_send" value="送出">
		</p>
	</form>
	<strong><?=$notices; ?></strong>
</body>

</html>