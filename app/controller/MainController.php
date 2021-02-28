<?php

namespace controller;

class MainController extends Controller
{
    private $buf = '
    <thead>
        <tr>
            <th scope="col"></th>
            <th scope="col">個人項目</th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th scope="row">1</th>
            <td>請假申請</td>
            <td><input type="submit" name="askToLeave" value="確認"></td>
        </tr>
        <tr>
            <th scope="row">2</th>
            <td>
                查看請假紀錄
                <input type="month" id="leaveLogStart" name="leaveLogStart" value="">
                ~
                <input type="month" id="leaveLogEnd" name="leaveLogEnd" value="">
            </td>
            <td><input type="submit" name="checkLeaveLog" value="確認"></td>
        </tr>
        <tr>
            <th scope="row">3</th>
            <td>查看剩餘特休/特別假</td>
            <td><input type="submit" name="leaveQuota" value="確認"></td>
        </tr>
        <tr>
            <th scope="row">4</th>
            <td>修改密碼</td>
            <td><input type="submit" name="editPW" value="確認"></td>
        </tr>
    </tbody>';
    function employee()
    {
        if(!$this->headerMgr()) {
            $this->assign('form', "
            <form method='post' action=''>
                <table>
                $this->buf
                </table>
            </form>");
            $this->display('main/main.html');
        }
    }

    function manager()
    {
        if(!$this->headerMgr()) {
            $this->buf .= '
            <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col">管理項目</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">1</th>
                    <td>人資編輯頁面</td>
                    <td><input type="submit" name="staffChanges" value="確認"></td>
                </tr>
                <tr>
                    <th scope="row">2</th>
                    <td>請假報表
                        <select id="fromType" name="fromType" onload="startDayType()" onchange="startDayType()">
                            <option value="month">月報</option>
                            <option value="year">年報</option>
                        </select>
                        <input type="month" id="startDay" name="startDay">
                    </td>
                    <td><input type="submit" name="leaveReport" value="確認"></td>
                </tr>
                <tr>
                    <th scope="row">3</th>
                    <td>賦予特別假</td>
                    <td><input type="submit" name="giveSpecialLeave" value="確認"></td>
                </tr>
                <tr>
                    <th scope="row">3</th>
                    <td>新增補班/放假紀錄</td>
                    <td><input type="submit" name="workdayOrWeekend" value="確認"></td>
                </tr>
            </tbody>';
            $this->assign('form', "
            <form method='post' action=''>
                <table>
                $this->buf
                </table>
            </form>");
            $this->display('main/main.html');
        }
    }

    function headerMgr()
    {
        // personal
        if (isset($_POST["askToLeave"])) {
            header("Location:index.php?m=leave&a=leave&leave=add");
        } else if (isset($_POST["checkLeaveLog"])) {
            $leaveLogStart = $_POST["leaveLogStart"];
            $leaveLogEnd = $_POST["leaveLogEnd"];
            if ($leaveLogEnd == null || $leaveLogStart == null) {
                $this->assign('error', '期間是空的');
                return false;
            } else header("Location:index.php?m=form&a=form&form=leaveLog&leaveLogStart=$leaveLogStart&leaveLogEnd=$leaveLogEnd");
        } else if (isset($_POST["leaveQuota"])) {
            header("Location:index.php?m=form&a=form&form=leaveQuota");
        } else if (isset($_POST["editPW"])) {
            header("Location:index.php?m=modifyAcc&a=modifyAcc");
        // management
        } else if (isset($_POST["staffChanges"])) {
            header("Location:index.php?m=hr&a=hr");
        } else if (isset($_POST["leaveReport"])) {
            $type = $_POST["fromType"];
            $startDay = $_POST["startDay"];
            if ($startDay == null) {
                $this->assign('error', '期間是空的');
                return false;
            } else header("Location:index.php?m=form&a=form&form=leaveReport&type=$type&startDay=$startDay");
        } else if (isset($_POST["giveSpecialLeave"])) {
            header("Location:index.php?m=addLeave&a=addLeave");
        } else if (isset($_POST["workdayOrWeekend"])) {
            header("Location:index.php?m=workdayOrWeekend&a=workdayOrWeekend");
        } else return false;
        exit();
        return true;
    }
}
