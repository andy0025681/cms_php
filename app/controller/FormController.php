<?php

namespace controller;

use \model\Mail;
use \model\Staff;
use \model\LeaveLog;
use \model\Annex;
use \model\PdfOutput;
use \model\CreateForm;
use \model\DisableStaff;

class FormController extends Controller
{
    function form()
    {
        $this->checkPOST();
        $this->assign('result', $this->checkGET());
        $this->display();
    }

    function setURL()
    {
        $buf = 'showFormPage.php?form=' . $_GET['form'];
        if (isset($_GET['type'])) $buf .= '&type=' . $_GET['type'];
        if (isset($_GET['startDay'])) $buf .= '&startDay=' . $_GET['startDay'];
        if (isset($_GET['leaveLogStart'])) $buf .= '&leaveLogStart=' . $_GET['leaveLogStart'];
        if (isset($_GET['leaveLogEnd'])) $buf .= '&leaveLogEnd=' . $_GET['leaveLogEnd'];
        $this->assign('url', $buf);
    }

    function checkPOST()
    {
        $mail = new Mail();
        $_staff = new Staff();
        $_leaveLog = new LeaveLog();
        $_annex = new Annex();
        $pdfOutput = new PdfOutput();
        foreach (array_keys($_POST) as $key) {
            // 下載文件
            if ($key == "download") {
                if ($_GET["form"] == 'staffList') {
                    $pdfOutput->staffListPDF('D', '在職');
                } elseif ($_GET["form"] == 'resignedStaffList') {
                    $pdfOutput->staffListPDF('D', '離職');
                } elseif ($_GET["form"] == 'LeaveWithoutPayStaffList') {
                    $pdfOutput->staffListPDF('D', '留職停薪');
                } elseif ($_GET["form"] == 'leaveReport') {
                    if ($_GET["type"] == 'month')
                        $pdfOutput->leaveReportPDF($_GET["type"], explode("-", $_GET["startDay"])[0], explode("-", $_GET["startDay"])[1], 'D');
                    else
                        $pdfOutput->leaveReportPDF($_GET["type"], explode("-", $_GET["startDay"])[0], false, 'D');
                } elseif ($_GET["form"] == 'leaveLog') {
                    $pdfOutput->leaveLogPDF($_COOKIE["staffCode"], $_GET["leaveLogStart"], $_GET["leaveLogEnd"], 'D');
                }
            }
            // 請假紀錄
            elseif (explode("_", $key)[0] == "editLeaveBtn") {
                setcookie("leaveLogNum", explode("_", $key)[1], time() + 3600 * 24, '/');
                setcookie("leaveType", explode("_", $key)[2], time() + 3600 * 24, '/');
                header("Location:index.php?m=leave&a=leave&leave=edit");
                exit();
            } elseif (explode("_", $key)[0] == "delLeaveBtn") {
                $leaveLogNum = explode("_", $key)[1];
                if ($_leaveLog->deleteLeave($leaveLogNum)) $this->assign('notices', '刪除成功<br>');
                else $this->assign('notices', '刪除失敗<br>');
            } elseif (explode("_", $key)[0] == "sentRequestBtn") {
                $leaveLogNum = explode("_", $key)[1];
                if ($mail->leaveNoticeDecide("請假申請", $leaveLogNum, false)) {
                    $this->assign('notices', '郵件寄送成功<br>');
                } else $this->assign('notices', '郵件寄送失敗<br>');
            } elseif (explode("_", $key)[0] == "annexBtn") {
                $leaveLogNum = explode("_", $key)[1];
                $leaveType = explode("_", $key)[2];
                $buf = "annex_" . $leaveLogNum . "_" . $leaveType . "附件";
                // 附件
                if (isset($_FILES[$buf]) and $_FILES[$buf]["error"] == 0) {
                    $annex = explode('.', $_FILES[$buf]["name"]);
                    $annex = __DIR__."/../../config/annex/" . $leaveLogNum . "_請假附件." . $annex[count($annex) - 1];
                    $res = $_annex->updateLeaveLogAnnex($leaveLogNum, $_FILES[$buf], $annex);
                    if ($res) {
                        if (
                            $leaveType == "病假" or $leaveType == "喪假" or $leaveType == "婚假" or
                            $leaveType == "產假" or $leaveType == "公傷病假"
                        ) {
                            if ($mail->annexRepostLetter($leaveLogNum, $annex))
                                $this->assign('notices', '送信成功');
                            else $this->assign('notices', '送信失敗');
                        }
                    } else {
                        $this->assign('notices', '資料庫更新失敗');
                        $annex = "";
                    }
                } else {
                    $annex = "";
                    $this->assign('notices', '附件路徑是空的');
                };
            }
            // 員工編輯
            elseif (explode("_", $key)[0] == "editStaffBtn") {
                $staffCode = explode("_", $key)[1];
                header("Location:index.php?m=staffEditor&a=staffEditor&state=edit&staffCode=$staffCode");
                exit();
            } elseif (explode("_", $key)[0] == "reinstatementStaffBtn") {
                $staffCode = explode("_", $key)[1];
                header("Location:index.php?m=staffEditor&a=staffEditor&state=reinstatement&staffCode=$staffCode");
                exit();
            } elseif (explode("_", $key)[0] == "deleteStaffBtn") {
                $staffCode = explode("_", $key)[1];
                $staffData = $_staff->getstaffData($staffCode);
                $this->assign('disableStaffCheck', "<script>disableStaffCheck(" . $staffCode . ", '" . $staffData['cName'] . "')</script>");
            } elseif (explode("_", $key)[0] == "disableStaffBtn") {
                $staffCode = explode("_", $key)[1];
                header("Location:index.php?m=leaveWithoutPay&a=leaveWithoutPay&staffCode=$staffCode");
                exit();
            }
        }
    }

    function checkGET()
    {
        $result = '';
        $createForm = new CreateForm();
        if($_GET["disableStaff"] == 'true') {
            new DisableStaff();
        } else {
            if ($_GET["form"] == 'staffList') {
                $result .= $createForm->showStaffListForm('在職');
                $result .= '<input type="submit" name="download" value="下載">';
            } elseif ($_GET["form"] == 'resignedStaffList') {
                $result .= $createForm->showStaffListForm('離職');
                $result .= '<input type="submit" name="download" value="下載">';
            } elseif ($_GET["form"] == 'LeaveWithoutPayStaffList') {
                $result .= $createForm->showStaffListForm('留職停薪');
                $result .= '<input type="submit" name="download" value="下載">';
            } elseif ($_GET["form"] == 'leaveReport') {
                $result .= $createForm->showLeaveReportForm($_GET["type"], $_GET["startDay"]);
                $result .= '<input type="submit" name="download" value="下載">';
            } elseif ($_GET["form"] == 'leaveLog') {
                $result .= $createForm->showLeaveLogForm($_COOKIE["staffCode"], $_GET["leaveLogStart"], $_GET["leaveLogEnd"]);
                $result .= '<input type="submit" name="download" value="下載">';
            } elseif ($_GET["form"] == 'leaveQuota') {
                $result .= $createForm->showLeaveQuotaForm($_COOKIE["staffCode"]);
            }
        }
        return $result;
    }
}
