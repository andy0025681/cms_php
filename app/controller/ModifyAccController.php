<?php

namespace controller;

use model\StaffAccountList;

class ModifyAccController extends Controller
{
    function modifyAcc()
    {
        if (isset($_POST["modifyAcc_send"])) {
            $staffAccountList = new StaffAccountList();
            $password = $_POST["password"];
            $passwordRe = $_POST["password_re"];
            if ($password == $passwordRe) {
                if ($_COOKIE['staffCode'] != null)
                    $modifyAccStatus = $staffAccountList->updatePW($_COOKIE['staffCode'], $password);
                else $modifyAccStatus = false;
                header("Location:index.php?m=login&a=login&modifyAccStatus=$modifyAccStatus");
                exit();
            } else {
                $this->assign('notices', '兩次密碼輸入必須一致');
            }
        }
        $this->display();
    }
}
