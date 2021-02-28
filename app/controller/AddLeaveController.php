<?php

namespace controller;

use model\CreateOption;
use model\SpecialLeave;
use model\Annex;
use model\Mail;

class AddLeaveController extends Controller
{
    function addLeave()
    {
        $createOption = new CreateOption();
        $specialLeave = new SpecialLeave();
        $annex = new Annex();
        $mail = new Mail();
        $this->assign('staffListOption', $createOption->staffListOption());
        if (isset($_POST["add_leave_send"])) {
            $res = true;
            $staffCode = $_POST['getLeaveStaff'];
            $startDate = date('Y') . '-01-01';
            $endDate = date('Y') . '-12-31';
            $duration = $_POST['duration'];
            $reason = $_POST['reason'];
            $specialLeaveNum = $specialLeave->getNewSpecialLeaveNum();
            $path = false;
            // 附件
            if (isset($_FILES["annex"]) and $_FILES["annex"]["error"] == 0) {
                $path = explode('.', $_FILES["annex"]["name"]);
                $path = __DIR__."/../../config/annex/" . $specialLeaveNum . "_特別假附件." . $path[count($path) - 1];
                $annex->uploadFile($_FILES, $path);
            }
            // 特別假
            if ($res) {
                $mailTitle = '特別假申請';
                if ($specialLeave->addSpecialLeave($specialLeaveNum, $staffCode, $startDate, $endDate, $duration, $reason)) {
                    if ($mail->specialLeaveRequestLetter($specialLeaveNum, $path)) $this->assign('notices', '資料庫更新成功<br>特別假申請信發送成功<br>');
                    else $this->assign('notices', '資料庫更新成功<br>特別假申請信發送失敗<br>');
                } else $this->assign('notices', '資料庫更新失敗<br>');
            }
        }

        $this->display();
    }
}
