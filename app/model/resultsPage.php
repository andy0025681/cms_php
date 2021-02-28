<!-- 此文件用於當使用者收到可以和伺服器互動的信件時，此文件是中繼站，用來引導至對應功能。 -->
<!-- 類型包含:
        共用套件引用
        功能判斷
-->
<!-- 功能判斷包含:
        答覆請假審核結果執行 if (isset($_GET["leaveLogNum"]))
        忘記密碼執行 elseif (isset($_GET["staffCode"]) and isset($_GET["ac"]) and isset($_GET["pw"]))
        答覆特別價審核結果執行 elseif (isset($_GET["staffCode"]) and isset($_GET["ac"]) and isset($_GET["pw"]))
-->
<!-- 共用套件引用 -->
<?php
require_once('../controllers/systemCtrl.php');
require_once('../model/staff.php');
require_once('../model/mail.php');
require_once('../model/time.php');
?>

<!-- 功能判斷 -->
<?php
if (isset($_GET["leaveLogNum"])) {
    require_once('../model/leaveLog.php');
    $state = true;
    $leaveLog = getLeaveLog($_GET["leaveLogNum"]);
    $leaveLogNum = explode('-', $_GET["leaveLogNum"]);
    $staffCode = $leaveLogNum[0];
    if(!$leaveLog) echo "這筆請假紀錄已不存在"; // 刪除以後的保護
    elseif ($leaveLog['status'] != $_GET["status"]) { // 如果狀態更新，必須修改資料庫，以及寄送通知。
        if(explode('-', $leaveLog['startDate'])[0] == date('Y')) {
            // 更新特休假/特別假/生日特別假紀錄
            $state = updateLeaveQuota($staffCode, $_GET["leaveLogNum"], $leaveLog['duration'], $leaveLog['status'], $_GET["status"]);
        }
        // 更新請假紀錄
        if ($state) { // 如果狀態正常
            if (updateLeaveLogStatus($_GET["leaveLogNum"], $_GET["status"]) == 1) { // 修改假單狀態
                echo $_GET["leaveLogNum"] . " | " . $_GET["status"] . "<br>";
                if (leaveStatusLetter("請假審核", $_GET["leaveLogNum"])) echo "已發送通知<br>";
                else echo "發送通知失敗<br>";
                if($leaveLog['startDate'] == $today and !dateIntervalAnyFormat($time, "09:00:00")) { // 如果請假起始日期是今日，且已經超過通報全體員工時間(上午9點)，必須另行通報。
                    dailyLeaveNoticeLetter($today);
                }
            } else echo "資料庫更新失敗<br>";
        } else echo "依上述情況，無法更新請假紀錄<br>";
    } else echo $_GET["leaveLogNum"] . " 狀態未改變";
} elseif (isset($_GET["staffCode"]) and isset($_GET["ac"]) and isset($_GET["pw"])) {
    setcookie("staffCode", $_GET['staffCode'], time() + 600, '/');
    header("Location:../view/modifyAccPage.php");
    exit();
} elseif (isset($_GET["specialLeaveNum"]) and isset($_GET["status"])) {
    require_once('specialLeave.php');
    $specialLeave = getSpecialLeave($_GET["specialLeaveNum"]);
    if($specialLeave['status'] != $_GET["status"]) { 
        if (updateSpecialLeaveStatus($_GET["specialLeaveNum"], $_GET["status"])) {
            echo "已更新特別假狀態<br>";
            if(specialLeaveRequestStatusLetter(getSpecialLeave($_GET["specialLeaveNum"]))) echo "送信成功<br>";
            else echo "送信失敗<br>";
        } else echo "更新特別假狀態失敗<br>";
    } else echo "特別假".$_GET["specialLeaveNum"] . " 狀態未改變";
}
?>