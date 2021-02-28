<!-- 此文件用於完成每天固定工作 -->
<!-- 類型包含:
        通用套件
        發送通知
-->
<!-- 通用套件 -->
<?php
require_once('../time.php');
?>

<!-- 發送通知 -->
<?php
require_once('../mail.php');
$status = isWorkday($today); // 今日有特別規定嗎?
if($status == 'null') { // 如果該天沒有特別規定。
        if (date_create($today)->format('N') < 6) // 如果該天不是假日，就算請假。
                dailyLeaveNoticeLetter($today);
} elseif($status) { // 如果該天特別規定是工作天。
        dailyLeaveNoticeLetter($today);
}
?>