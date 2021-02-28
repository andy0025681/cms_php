<?php

namespace controller;

use model\Time;
use model\staff;
use model\leaveWithoutPay;
use model\extraAgentRelation;
use model\staffAccountList;

class leaveWithoutPayController extends Controller
{
    function leaveWithoutPay()
    {
        $time = new Time();
        $staff = new staff();
        $leaveWithoutPay = new leaveWithoutPay();
        $extraAgentRelation = new extraAgentRelation();
        $staffAccountList = new staffAccountList();
        $staffData = $staff->getstaffData($_GET["staffCode"]);
        $this->assign('eName', $staffData['eName']);
        $this->assign('eLastName', $staffData['eLastName']);
        $this->assign('desknum', $staffData['desknum']);
        $this->assign('today', $time->todayTW());

        if (isset($_POST["leave_without_pay_send"])) {
            $staffCode = $_GET["staffCode"];
            $resignDay = $_POST['resignDay'];
            $returnDay = $_POST['returnDay'];
            if ($leaveWithoutPay->addNewLeaveWithoutPayLog($staffCode, $resignDay, $returnDay)) echo "留職停薪更新成功" . "<br>";
            else echo "留職停薪更新失敗" . "<br>";
            if($extraAgentRelation->deleteExtraAgentRelationByStaffCode($_GET['staffCode'])) echo "代理關係已清空" . "<br>";
            else echo "代理關係清除失敗" . "<br>";
            if($staff->disableStaffAcc($_GET['staffCode'])) echo "帳號權限已禁用" . "<br>";
            else echo "帳號權限禁用失敗" . "<br>";
            if($staffAccountList->deleteStaffAccount($_GET['staffCode'])) echo "帳號已刪除" . "<br>";
            else echo "帳號刪除失敗" . "<br>";
        }

        $this->display();
    }
}
