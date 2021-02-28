<!-- 此文件用於完成每半小時固定工作 -->
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
$startTime = date('H:i:s', strtotime($time.'-29minute'));
checkAutomaticReplyLetter($today, $startTime, $time);
?>