<?php

namespace controller;

use \model\Time;
use \model\CreateOption;
use \model\LeaveLog;
use \model\OfficialLeave;
use \model\BirthdayLeave;
use \model\SpecialLeave;
use \model\Mail;


class LeaveController extends Controller
{
    function leave()
    {
        $time = new Time();
        $createOption = new CreateOption();
        $leaveLog = new LeaveLog();
        $officialLeave = new OfficialLeave();
        $birthdayLeave = new BirthdayLeave();
        $specialLeave = new SpecialLeave();
        $mail = new Mail();
        $staffName = $_COOKIE['desknum'].' '.$_COOKIE['eName'].' '.$_COOKIE['eLastName'];
        if (isset($_POST["leave_send"])) {
            $res = true;
            $staffCode = $_COOKIE['staffCode'];
            $leaveLogNum = $leaveLog->generateLeaveLogNum($staffCode, $_POST['leaveType']);
            $agentStaffCode = explode('/', $_POST['agent'])[0];
            $leaveType = $_POST['leaveType'];
            $startDate = $_POST['startDay'];
            $startTime = $_POST['startTime'];
            $endDate = $_POST['endDay'];
            $endTime = $_POST['endTime'];
            $duration = $time->workingHours_days($startDate, $startTime, $endDate, $endTime);
            $reason = $_POST['reason'];
            $mailTitle = 'none';
            // 判斷請假日期是否和記錄重疊
            if (!$leaveLog->checkTimeNotRepeated($staffCode, $startDate, $endDate, $startTime, $endTime)) {
                $this->assign('notices', '申請失敗，請假日期重疊');
            } elseif (explode('-', $startDate)[0] != explode('-', $endDate)[0]) { // 判斷每年的假期是否都分開算
                $this->assign('notices', '如果假期跨年，請分開請，謝謝');
            } elseif ( // 判斷事假是否符合請特休條件。 ($leaveDays 設為0，是要求員工必須先將特休假使用完畢，再請事假)
                $leaveType == '事假' and
                $officialLeave->checkOfficialLeave($staffCode, "$startDate $startTime", "$endDate $endTime", 0, false)
            ) {
                $this->assign('notices', '必須先將特休假用完才能申請事假');
            } elseif ( // 工作未滿半年的同仁，如果事假結束日期，特休假已開放，要將假期分成兩段來請。(新進員工在職未滿半年才會遇到)
                $leaveType == '事假' and ($officialLeave->checkOfficialLeave($staffCode, "$endDate $endTime", "$endDate $endTime", 0, false))
            ) {
                $this->assign('notices', '工作未滿半年的同仁，如果請假期間，特休假已開放，請將假期分開來請');
            } elseif ( // 判斷事假的請假期間，是否與可使用的特別假使用期限交疊。如果時間交疊，代表可以使用特別假。
                $leaveType == '事假' and
                $specialLeave->specialLeaveOverlap($staffCode, $startDate, $endDate)
            ) {
                $this->assign('notices', '必須先將特別假用完才能申請事假');
            } elseif ( // 判斷婚假是否有附件
                $leaveType == '婚假' and
                !(isset($_FILES["annex"]) and $_FILES["annex"]["error"] == 0)
            ) {
                $this->assign('notices', '婚假必須有附件');
            } elseif ( // 判斷是否已超出特休假剩餘天數
                $leaveType == '特休假' and
                !$officialLeave->checkOfficialLeave($staffCode, "$startDate $startTime", "$endDate $endTime", ($duration / 7.5), true)
            ) {
                $this->assign('notices', '申請失敗，請假天數已超出特休剩餘天數');
            } elseif ($leaveType == '生日特別假' and $duration != 7.5) { // 檢查生日特別假是否一次請完
                $this->assign('notices', '申請失敗，生日特別假必須一次請完，且不能超過一天');
            } elseif ( // 判斷是生日特別假
                $leaveType == '生日特別假' and
                !$birthdayLeave->dateInBirthdayLeavePeriod($staffCode, $startDate)
            ) { 
                // dateInBirthdayLeavePeriod 已列出錯誤訊息
            } elseif ( // 判斷是否已超出特別假剩餘天數
                $leaveType == '特別假' and
                !$specialLeave->getUsableSpecialLeave($staffCode, $startDate, $endDate, $duration)
            ) {
                $this->assign('notices', '申請失敗，特別假超出額度');
            } elseif ($leaveLog->staffIsLeave($agentStaffCode, $startDate, $endDate)) { // 請假期間，代理人不能請假
                $this->assign('notices', '與代理人的請假期間重疊，請選擇其他代理人，謝謝');
            } else { // 通過審查!
                // 附件
                if (isset($_FILES["annex"]) and $_FILES["annex"]["error"] == 0) {
                    $annex = explode('.', $_FILES["annex"]["name"]);
                    $annex = __DIR__."/../../config/annex/" . $leaveLogNum . "_請假附件." . $annex[count($annex) - 1];
                    $res = uploadFile($_FILES["annex"], $annex);
                    if (!$res) $annex = "";
                } else $annex = "";
                if ($res) {
                    if ($_GET["leave"] == 'add') {
                        $mailTitle = '請假申請';
                        $buf = $leaveLog->addLeaveLog($leaveLogNum, $staffCode, $staffName, $agentStaffCode, $leaveType, $startDate, $startTime, $endDate, $endTime, $duration, $reason, $annex);
                    } elseif ($_GET["leave"] == 'edit') {
                        $mailTitle = '請假修改申請';
                        $oldLeaveLogNum = $_COOKIE["leaveLogNum"];
                        $buf = $leaveLog->editLeaveLog($leaveLogNum, $agentStaffCode, $leaveType, $startDate, $startTime, $endDate, $endTime, $duration, $reason, $annex, $oldLeaveLogNum);
                    }
                    if ($buf) {
                        if ($mail->leaveNoticeDecide($mailTitle, $leaveLogNum, $annex)) {
                            $this->assign('notices', '請假申請審核中...<br>郵件寄送成功');
                        } else {
                            $this->assign('notices', '請假申請審核中...<br>郵件寄送失敗');
                        }
                    } else {
                        $this->assign('notices', '請假申請失敗');
                    }
                }
            }
        }
        $this->assign('today', $time->todayTW());
        $this->assign('staffCode', $_COOKIE["staffCode"]);
        $this->assign('staffName', $staffName);
        $this->assign('leaveType', $createOption->leaveTypeListOption($_COOKIE["staffCode"], $_COOKIE["gender"]));
        $this->assign('agent', $createOption->agentListOption($_COOKIE["staffCode"], $_COOKIE["department"]));
        $this->display();
    }
}
