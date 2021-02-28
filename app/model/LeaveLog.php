<?php

namespace model;

class LeaveLog
{
    function __construct()
    {
        $this->sql = new SendSQL();
    }

    /**
     * get leave log.
     *
     * @param       string  $leaveLogNum    leave log number.
     * @return      array                   leave log.
     * by Andy (2020-01-21)
     */
    function getLeaveLog($leaveLogNum)
    {
        $buf = $this->sql->sqlSelectLeaveLog($leaveLogNum);
        foreach ($buf as $row) {
            return $row;
        }
        return false;
    }

    /**
     * get leave log by staff code and start date and end date and status.
     *
     * @param       string  $staffCode  staff code.
     * @param       string  $startDate  start date.
     * @param       string  $endDate    end date.
     * @param       string  $isWebPage  if is web page don't need to filter status.
     * @return      mysqli_result|bool  For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function getLeaveLogByDateStatus($staffCode, $startDate, $endDate, $isWebPage)
    {
        return $this->sql->sqlSelectLeaveLogDateStatus($staffCode, $startDate, $endDate, $isWebPage);
    }

    /**
     * get leave log by date and leave type.
     *
     * @param       string  $staffCode  staff code.
     * @param       array   $date       date.
     * @param       string  $leaveType  leave type.
     * @param       string  $status     leave log status.
     * @return      mysqli_result|bool  For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function getLeaveLogByDateLeaveType($staffCode, $date, $leaveType, $status)
    {
        return $this->sql->sqlSelectLeaveLogByDateLeaveType($staffCode, $date, $leaveType, $status);
    }

    /**
     * get leave log, stats by staff code and leave type.
     *
     * @param       array   $date       date.
     * @return      mysqli_result|bool  For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function getLeaveLogStats($date)
    {
        return $this->sql->sqlSelectLeaveLogStats($date);
    }

    /**
     * get newest leave log number.
     *
     * @param       string  $staffCode      staffCode.
     * @param       string  $leaveTypeNum   leave type number.
     * @return      mysqli_result|bool      For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function getNewestLeaveLogNum($staffCode, $leaveTypeNum)
    {
        return $this->sql->sqlSelectNewestLeaveLogNum($staffCode, $leaveTypeNum);
    }

    /**
     * get whether the date, time and leave log overlap.
     *
     * @param       string  $staffCode  staff code.
     * @param       string  $startDate  start date.
     * @param       string  $endDate    end date.
     * @param       string  $startTime  start time.
     * @param       string  $endTime    end time.
     * @return      mysqli_result|bool  For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function getOverlapLeaveLog($staffCode, $startDate, $endDate, $startTime, $endTime)
    {
        return $this->sql->sqlSelectOverlapLeaveLog($staffCode, $startDate, $endDate, $startTime, $endTime);
    }

    /**
     * get leave log by date.
     *
     * @param       string  $date       date.
     * @return      mysqli_result|bool  For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function getLeaveLogByDate($date)
    {
        return $this->sql->sqlSelectLeaveLogByDate($date);
    }

    /**
     * get leave log of period.
     *
     * @param       string  $startDate  start date.
     * @param       string  $endDate    end date.
     * @return      mysqli_result|bool  For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-27)
     */
    function getLeaveLogOfPeriod($startDate, $endDate)
    {
        return $this->sql->sqlSelectLeaveLogOfPeriod($startDate, $endDate);
    }

    /**
     * get leave log in date, time.
     *
     * @param       string  $date       date.
     * @param       string  $startTime  start time.
     * @param       string  $endTime    end time.
     * @return      mysqli_result|bool  For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function getLeaveLogInTime($date, $startTime, $endTime)
    {
        return $this->sql->sqlSelectLeaveLogInTime($date, $startTime, $endTime);
    }

    /**
     * add new leave log.
     *
     * @param       string  $leaveLogNum    leavelog number.
     * @param       string  $staffCode      staff code.
     * @param       string  $staffName      staff name.
     * @param       string  $agentStaffCode agent staff code.
     * @param       string  $leaveType      leave type.
     * @param       string  $startDate      start date.
     * @param       string  $startTime      start time.
     * @param       string  $endDate        end date.
     * @param       string  $endTime        end time.
     * @param       string  $duration       duration.
     * @param       string  $reason         reason.
     * @param       string  $annexPath      annex path.
     * @return      mysqli_result|bool      For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function addLeaveLog($leaveLogNum, $staffCode, $staffName, $agentStaffCode, $leaveType, $startDate, $startTime, $endDate, $endTime, $duration, $reason, $annexPath)
    {
        return $this->sql->sqlInsertLeaveLog($leaveLogNum, $staffCode, $staffName, $agentStaffCode, $leaveType, $startDate, $startTime, $endDate, $endTime, $duration, $reason, $annexPath);
    }

    /**
     * update leave log.
     *
     * @param       string  $leaveLogNum    leavelog number.
     * @param       string  $agentStaffCode agent staff code.
     * @param       string  $leaveType      leave type.
     * @param       string  $startDate      start date.
     * @param       string  $startTime      start time.
     * @param       string  $endDate        end date.
     * @param       string  $endTime        end time.
     * @param       string  $duration       duration.
     * @param       string  $reason         reason.
     * @param       string  $annexPath      annex path.
     * @param       string  $oldLeaveLogNum old leave log number.
     * @return      mysqli_result|bool      For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function updateLeaveLog($leaveLogNum, $agentStaffCode, $leaveType, $startDate, $startTime, $endDate, $endTime, $duration, $reason, $annexPath, $oldLeaveLogNum)
    {
        return $this->sql->sqlUpdateLeaveLog($leaveLogNum, $agentStaffCode, $leaveType, $startDate, $startTime, $endDate, $endTime, $duration, $reason, $annexPath, $oldLeaveLogNum);
    }
    
    /**
     * update leave log status.
     *
     * @param       string  $leaveLogNum    leavelog number.
     * @param       string  $status         status.
     * @return      mysqli_result|bool      For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function updateLeaveLogStatus($leaveLogNum, $status)
    {
        return $this->sql->sqlUpdateLeaveLogStatus($leaveLogNum, $status);
    }
    
    /**
     * update leave log annex path.
     *
     * @param       string  $leaveLogNum    leavelog number.
     * @param       string  $annexPath      annex path.
     * @return      mysqli_result|bool      For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function updateLeaveLogAnnexPath($leaveLogNum, $annexPath)
    {
        return $this->sql->sqlUpdateLeaveLogAnnexPath($leaveLogNum, $annexPath);
    }

    /**
     * delete leave log.
     *
     * @param       string  $leaveLogNum    leavelog number.
     * @return      mysqli_result|bool      For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function deleteLeaveLog($leaveLogNum)
    {
        return $this->sql->sqlDeleteLeaveLog($leaveLogNum);
    }

    /**
     * check leave time not repeated.
     *
     * @param       string  $staffCode  staff code.
     * @param       string  $startDate  start date.
     * @param       string  $endDate    end date.
     * @param       string  $startTime  start time.
     * @param       string  $endTime    end time.
     * @return      bool                not repeated [true] / repeated [false].
     * by Andy (2020-01-21)
     */
    function checkTimeNotRepeated($staffCode, $startDate, $endDate, $startTime, $endTime)
    {
        $buf = $this->getOverlapLeaveLog($staffCode, $startDate, $endDate, $startTime, $endTime);
        foreach ($buf as $row) {
            if (isset($row["status"]) and $row["status"] != '已駁回') return false;
        }
        return true;
    }
    
    /**
     * generate new leave log number.
     *
     * @param       string  $staffCode  staffCode.
     * @param       string  $leaveType  leave type.
     * @return      string              new leave log number.
     * by Andy (2020-01-21)
     */
    function generateLeaveLogNum($staffCode, $leaveType)
    {
        $_leaveType = new LeaveType();
        $leaveTypeNum = $_leaveType->getLeaveTypeNum($leaveType);
        $buf = $this->getNewestLeaveLogNum($staffCode, $leaveTypeNum);
        foreach ($buf as $row) {
            if (isset($row["MAX(leaveLogNum)"])) {
                $leaveLogNum = explode('-', $row["MAX(leaveLogNum)"]);
                return $leaveLogNum[0] . '-' . $leaveLogNum[1] . '-' . ($leaveLogNum[2] + 1);
            }
        }
        return $staffCode . '-' . $leaveTypeNum . '-0';
    }
    
    /**
     * leave log to text.
     *
     * @param       string  $staffCode  staff code.
     * @param       array   $date       date.
     * @param       string  $leaveType  leave type.
     * @param       string  $status     leave log status.
     * @return      string              "startDate leaveType duration".
     * by Andy (2020-01-21)
     */
    function leaveLogToText($staffCode, $date, $leaveType, $status)
    {
        $result = "";
        $buf = $this->getLeaveLogByDateLeaveType($staffCode, $date, $leaveType, $status);
        foreach ($buf as $row) {
            $result .= $row['startDate'] . " ";
            $result .= $leaveType . " ";
            $result .= $row['duration'] . "<br>";
        }
        return $result;
    }
    
    /**
     * edit leave log.
     *
     * @param       string  $leaveLogNum    leavelog number.
     * @param       string  $agentStaffCode agent staff code.
     * @param       string  $leaveType      leave type.
     * @param       string  $startDate      start date.
     * @param       string  $startTime      start time.
     * @param       string  $endDate        end date.
     * @param       string  $endTime        end time.
     * @param       string  $duration       duration.
     * @param       string  $reason         reason.
     * @param       string  $annexPath      annex path.
     * @param       string  $oldLeaveLogNum old leave log number.
     * @return      bool                    edit success / edit fail.
     * by Andy (2020-01-21)
     */
    function editLeaveLog($leaveLogNum, $agentStaffCode, $leaveType, $startDate, $startTime, $endDate, $endTime, $duration, $reason, $annexPath, $oldLeaveLogNum)
    {
        $officialLeave = new OfficialLeave();
        $row = $this->getLeaveLog($oldLeaveLogNum);
        // 編輯假單前，必須退回員工特定假期的時間。
        if ($row["leaveType"] == "特休假" and $row["status"] == "已通過") {
            $staffCode = $row["staffCode"];
            $newOffLeave = $row["duration"] / 7.5 + $officialLeave->getOfficialLeave($staffCode);
            if ($officialLeave->updateOfficialLeave($staffCode, $newOffLeave)) {
                echo "退還特休成功<br>";
            } else {
                echo "退還特休失敗<br>";
                return false;
            }
        } else {
            echo "不需要退還特休<br>";
        }
    
        if ($this->updateLeaveLog($leaveLogNum, $agentStaffCode, $leaveType, $startDate, $startTime, $endDate, $endTime, $duration, $reason, $annexPath, $oldLeaveLogNum)) {
            echo "休假更新成功<br>";
            $result = true;
        } else {
            echo "休假更新失敗<br>";
            $result = false;
        }
        return $result;
    }
    
    /**
     * delete leave log.
     *
     * @param       string  $leaveLogNum    leavelog number.
     * @return      bool                    delete success / delete fail.
     * by Andy (2020-01-21)
     */
    function deleteLeave($leaveLogNum)
    {
        $mail = new Mail();
        $staff = new Staff();
        $leaveType = new LeaveType();
        $specialLeave = new SpecialLeave();
        $state = true;
        $leaveLog = $this->getLeaveLog($leaveLogNum);
        if (isset($leaveLog["leaveLogNum"])) $leaveLogNum = $leaveLog["leaveLogNum"];
        else return false;
        // 刪除假單前，必須退回員工特定假期的時間。
        $state = $staff->updateLeaveQuota($leaveLog["staffCode"], $leaveLogNum, $leaveLog["duration"], $leaveLog["status"], "刪除");
        if ($state) {
            if ($this->deleteLeaveLog($leaveLogNum)) {
                echo "刪除假期成功!";
                if (explode('-', $leaveLogNum)[1] == $leaveType->getLeaveTypeNum("特別假")) $state = $specialLeave->deleteSpecialLeaveReference($leaveLogNum);
                if ($state) $state = $mail->leaveCancelLetter($leaveLog);
                else echo "資料庫異常，部分記錄未刪除!";
            } else {
                echo "刪除假期失敗!";
                $state = false;
            }
        }
        return $state;
    }
    
    /**
     * check leave log by date leave type.
     *
     * @param       string  $staffCode  staff code.
     * @param       array   $date       date.
     * @param       string  $leaveType  leave type.
     * @param       string  $status     leave log status.
     * @return      bool                exist / not exist.
     * by Andy (2020-01-21)
     */
    function checkLeaveLogByDateLeaveType($staffCode, $date, $leaveType, $status)
    {
        $buf = $this->getLeaveLogByDateLeaveType($staffCode, $date, $leaveType, $status);
        foreach ($buf as $row) {
            if (isset($row['leaveLogNum'])) return true;
        }
        return false;
    }
    
    /**
     * get leave log of period.
     *
     * @param       string  $staffCode  staff code.
     * @param       string  $startDate  start date.
     * @param       string  $endDate    end date.
     * @return      bool    staff is leave / staff isn't leave.
     * by Andy (2020-01-27)
     */
    function staffIsLeave($staffCode, $startDate, $endDate)
    {
        $buf = $this->getLeaveLogOfPeriod($startDate, $endDate);
        foreach ($buf as $row) {
            if (isset($row['staffCode'])) {
                if ($staffCode == $row['staffCode']) return true;
            }
        }
        return false;
    }
}