<?php

namespace controller;

use \model\Time;
use \model\LeaveLog;
use \model\Mail;
use \model\Staff;
use \model\SpecialLeave;

class ResultController extends Controller
{
    function result()
    {
        $_staff = new Staff();
        $mail = new Mail();
        $time = new Time();
        $_leaveLog = new LeaveLog();
        $_specialLeave = new SpecialLeave();
        $result = '';
        if (isset($_GET["leaveLogNum"])) {
            $state = true;
            $leaveLog = $_leaveLog->getLeaveLog($_GET["leaveLogNum"]);
            $leaveLogNum = explode('-', $_GET["leaveLogNum"]);
            $staffCode = $leaveLogNum[0];
            if (!$leaveLog) $result .= '這筆請假紀錄已不存在'; // 刪除以後的保護
            elseif ($leaveLog['status'] != $_GET["status"]) { // 如果狀態更新，必須修改資料庫，以及寄送通知。
                if (explode('-', $leaveLog['startDate'])[0] == date('Y')) {
                    // 更新特休假/特別假/生日特別假紀錄
                    $state = $_staff->updateLeaveQuota($staffCode, $_GET["leaveLogNum"], $leaveLog['duration'], $leaveLog['status'], $_GET["status"]);
                }
                // 更新請假紀錄
                if ($state) { // 如果狀態正常
                    if ($_leaveLog->updateLeaveLogStatus($_GET["leaveLogNum"], $_GET["status"]) == 1) { // 修改假單狀態
                        $result .= $_GET["leaveLogNum"] . ' | ' . $_GET["status"] . '<br>';
                        if ($mail->leaveStatusLetter("請假審核", $_GET["leaveLogNum"])) $result .= '已發送通知<br>';
                        else $result .= '發送通知失敗<br>';
                        if ($leaveLog['startDate'] == $time->todayTW() and !$time->dateIntervalAnyFormat($time->timeTW(), "09:00:00")) { // 如果請假起始日期是今日，且已經超過通報全體員工時間(上午9點)，必須另行通報。
                            $mail->dailyLeaveNoticeLetter($time->todayTW());
                        }
                    } else $result .= '資料庫更新失敗<br>';
                } else $result .= '依上述情況，無法更新請假紀錄<br>';
            } else $result .= $_GET["leaveLogNum"] . ' 狀態未改變';
        } elseif (isset($_GET["staffCode"]) and isset($_GET["ac"]) and isset($_GET["pw"])) {
            setcookie("staffCode", $_GET['staffCode'], time() + 600, '/');
            header("Location:../view/modifyAccPage.php");
            exit();
        } elseif (isset($_GET["specialLeaveNum"]) and isset($_GET["status"])) {
            $specialLeave = $_specialLeave->getSpecialLeave($_GET["specialLeaveNum"]);
            if ($specialLeave['status'] != $_GET["status"]) {
                if ($_specialLeave->updateSpecialLeaveStatus($_GET["specialLeaveNum"], $_GET["status"])) {
                    $result .= '已更新特別假狀態<br>';
                    if ($mail->specialLeaveRequestStatusLetter($_specialLeave->getSpecialLeave($_GET["specialLeaveNum"]))) $result .= '送信成功<br>';
                    else $result .= '送信失敗<br>';
                } else $result .= '更新特別假狀態失敗<br>';
            } else $result .= '特別假'.$_GET["specialLeaveNum"].' 狀態未改變';
        }
        $this->assign('result', $result);
        $this->display();
    }
}
