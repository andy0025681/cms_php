<?php

namespace controller;

use model\Time;
use model\Staff;
use model\OfficialLeave;
use model\Department;
use model\CreateOption;
use model\LeaveWithoutPay;
use model\StaffAccountList;
use model\AccAuthority;

class StaffEditorController extends Controller
{
    function staffEditor()
    {
        $time = new Time();
        $staff = new Staff();
        $officialLeave = new OfficialLeave();
        $department = new Department();
        $createOption = new CreateOption();
        $leaveWithoutPay = new LeaveWithoutPay();
        $staffAccountList = new StaffAccountList();
        $accAuthority = new AccAuthority();
        if ($_GET["state"] == 'add') {
            $this->assign('staffCode', $staffCode = $staff->getNewStaffCode());
            $this->assign('cName', $cName = "");
            $this->assign('eName', $eName = "");
            $this->assign('eLastName', $eLastName = "");
            $this->assign('gender', $gender = false);
            $this->assign('birthday', $birthday = $time->todayTW());
            $this->assign('email', $email = "");
            $this->assign('cellPhone', $cellPhone = "");
            $this->assign('firstDay', $firstDay = $time->todayTW());
            $this->assign('authority', $authority = $accAuthority->getAccAuthority("基本")); // 權限預設基本
            $this->assign('department', $department = false);
            $this->assign('desknum', $desknum = "");
        } elseif ($_GET["state"] == 'edit' or $_GET["state"] == 'reinstatement') {
            $this->assign('staffCode', $staffCode = $_GET["staffCode"]);
            $this->assign('staffData', $staffData = $staff->getstaffData($staffCode));
            $this->assign('cName', $cName = $staffData['cName']);
            $this->assign('eName', $eName = $staffData['eName']);
            $this->assign('eLastName', $eLastName = $staffData['eLastName']);
            $this->assign('gender', $gender = $staffData['gender']);
            $this->assign('birthday', $birthday = $staffData['birthday']);
            $this->assign('email', $email = explode('@', $staffData['email'])[0]);
            $this->assign('cellPhone', $cellPhone = $staffData['cellPhone']);
            if ($_GET["state"] == 'reinstatement' and !$leaveWithoutPay->haveLeaveWithoutPay($staffCode)) // 如果員工復職，且沒有留職停薪紀錄，到職日改成今天。
                $this->assign('firstDay', $firstDay = $time->todayTW());
            else // 其他情況，到職日是資料庫紀錄
                $this->assign('firstDay', $firstDay = $staffData['firstDay']);
            if ($_GET["state"] == 'reinstatement')
                $this->assign('authority', $authority = $accAuthority->getAccAuthority("基本")); // 如果員工復職，權限預設基本
            else $this->assign('authority', $authority = $staffData['authority']);
            $this->assign('department', $department = $staffData['department']);
            $this->assign('desknum', $desknum = $staffData['desknum']);
        }
        $this->assign('genderOption', $createOption->genderOption($gender));
        $this->assign('departmentListOption', $createOption->getDepartmentListOption($department));
        $this->assign('accExplain', $accAuthority->getAccExplain($authority));
        if (isset($_POST["emp_edit_send"])) {
            $cName = $_POST['cName'];
            $eName = $_POST['eName'];
            $eLastName = $_POST['eLastName'];
            $gender = $_POST['gender'];
            $email = $_POST['email'];
            $cellPhone = $_POST['cellPhone'];
            $firstDay = $_POST['firstDay'];
            $desknum = $_POST['desknum'];
            $department = $_POST['department'];
            $birthday = $_POST['birthday'];
            // $jobTitle = $_POST['jobTitle'];
            // 台灣手機號碼是十碼
            if (strlen($cellPhone) != 10) {
                $this->assign('notices', '手機號碼長度有誤');
                // 台灣手機號碼是09開頭
            } elseif (!substr_count($cellPhone, '09')) {
                $this->assign('notices', '手機號碼是09開頭');
                // 通過審核
            } else {
                if ($_GET["state"] == 'add') {
                    $staff->addNewStaff($staffCode, $cName, $eName, $eLastName, $gender, $email . "@gmail.com", $cellPhone, $firstDay, $desknum, $authority, $department, $birthday);
                } elseif ($_GET["state"] == 'edit') {
                    if ($staff->updateStaffData($staffCode, $cName, $eName, $eLastName, $gender, $email . "@gmail.com", $cellPhone, $cellPhone, false, $desknum, false, false, false, false, false, $department, $birthday, false))
                        $this->assign('notices', '職員更新成功<br>');
                    else $this->assign('notices', '職員更新失敗<br>');
                } elseif ($_GET["state"] == 'reinstatement') {
                    if (explode('-', $firstDay)[0] != date('Y')) $offLeave = $officialLeave->newEmpOrNewYear($staffCode, date('Y'), false);
                    else $offLeave = $officialLeave->newEmpOrNewYear($staffCode, $firstDay, true);
                    if ($staff->updateStaffData($staffCode, $cName, $eName, $eLastName, $gender, $email . "@gmail.com", $cellPhone, $firstDay, $desknum, $offLeave[2], $offLeave[0], $offLeave[1], $authority, false, $department, $birthday, "0000-00-00"))
                        $this->assign('notices', '職員更新成功<br>');
                    else $this->assign('notices', '職員更新失敗<br>');
                    if ($staffAccountList->addNewAcc($staffCode, $email . "@gmail.com"))
                        $this->assign('notices', '帳號更新成功<br>');
                    else $this->assign('notices', '帳號更新失敗<br>');
                }
            }
        }

        $this->display();
    }

    function setVariable()
    {
    }
}
