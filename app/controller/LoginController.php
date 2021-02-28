<?php

namespace controller;

use \model\Login;
use \model\Mail;

class LoginController extends Controller
{
    function login()
    {
        if (isset($_POST["login_send"])) {
            $login = new Login();
            $account = $_POST["account"];
            $password = $_POST["password"];
            $buf = $login->login($account, $password);
            if ($buf) {
                header("Location:index.php?m=$buf[0]&a=$buf[1]");
                exit();
            } else $this->assign('notices', '帳號密碼錯誤');
        } else if(isset($_GET["modifyAccStatus"])) {
            $this->modifyAccStatus();
        }
        $this->assign('form', '
        <form method="POST" action="index.php?m=login&a=login">
            <div style="margin-bottom: 5px;">
                <label style="float:left">帳號:&nbsp;&nbsp;</label>
                <input style="width: 89.9%;" type="text" id="account" name="account" placeholder="輸入" value="" required>
            </div>
            <div style="margin-bottom: 5px;">
                <label style="float:left">密碼:&nbsp;&nbsp;</label>
                <input style="width: 89.9%;" type="password" id="password" name="password" placeholder="輸入"
                    value="" required>
            </div>
            <div style="margin-bottom: 5px;">
                <a style="float:left" href="index.php?m=login&a=forgetPW">忘記密碼?</a>
                <input style="float:right" type="submit" name="login_send" value="送出">
            </div>
        </form>');
        $this->display();
    }

    function forgetPW()
    {
        if (isset($_POST["forgetPW_send"])) {
            $mail = new Mail();
            $email = $_POST["email"];
            if ($mail->changePasswordRequestLetter($email . '@gmail.com'))
                $this->assign('notices', "已送出重設密碼申請，email: $email@gmail.com");
            else $this->assign('notices', "送信失敗，email: $email@gmail.com");
        } else {
            $this->assign('form', '
            <form method="POST" action="index.php?m=login&a=forgetPW">
                <div style="margin-bottom: 5px;">
                    <label style="float:left">公司信箱:&nbsp;&nbsp;</label>
                    <input style="width: auto; display: inline;" type="text" id="email" name="email" value="" required>&nbsp;@gamil.com
                </div>
                <div style="margin-bottom: 5px;">
                    <a style="float:left" href="index.php?m=login&a=login">上一步</a>
                    <input type="submit" name="forgetPW_send" value="送出" style="float:right">
                </div>
            </form>');
        }
        $this->display('login/login.html');
    }

    function modifyAccStatus()
    {
        if($_GET["modifyAccStatus"]) $this->assign('notices', "修改密碼完成");
        else $this->assign('notices', "修改密碼失敗");
    }
}
