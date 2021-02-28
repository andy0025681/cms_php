<?php

namespace model;

class SpecialLeave
{
    function __construct()
    {
        $this->sql = new SendSQL();
    }

    /**
     * get special leave.
     *
     * @param       string  $leaveLogNum    leave log number.
     * @return      array|bool              special leave.
     * by Andy (2020-01-21)
     */
    function getSpecialLeave($specialLeaveNum)
    {
        $buf = $this->sql->sqlSelectSpecialLeaveBySpecialLeaveNum($specialLeaveNum);
        foreach ($buf as $row) {
            return $row;
        }
        return false;
    }

    /**
     * get special leave.
     *
     * @param       string  $staffCode  staff code.
     * @param       array   $date       date.
     * @return      float               duration.
     * by Andy (2020-01-21)
     */
    function getSpecialLeaveQuota($staffCode, $date)
    {
        $result = 0;
        $buf = $this->sql->sqlSelectSpecialLeaveWithinTimeLimit($staffCode, $date);
        foreach ($buf as $row) {
            if ($row['duration'] != null) $result += $row['duration'];
        }
        return $result;
    }

    /**
     * get unexpire special leave.
     *
     * @param       string  $staffCode  staff code.
     * @param       string  $date       use leave date.
     * @return      mysqli_result|bool  For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function getUnexpireSpecialLeave($staffCode, $date)
    {
        return $this->sql->sqlSelectUnexpireSpecialLeave($staffCode, $date);
    }

    /**
     * get usable special leave .
     *
     * @param       string  $staffCode  staff code.
     * @param       string  $startDate  start date.
     * @param       string  $endDate    end date.
     * @param       string  $duration   leave duration.
     * @return      int|bool            special leave.
     * by Andy (2020-01-21)
     */
    function getUsableSpecialLeave($staffCode, $startDate, $endDate, $duration)
    {
        $buf = $this->sql->sqlSelectUsableSpecialLeave($staffCode, $startDate, $endDate, $duration);
        foreach ($buf as $row) {
            if (isset($row["specialLeaveNum"])) return $row["specialLeaveNum"];
        }
        return false;
    }

    /**
     * check special leave that overlaps the date.
     *
     * @param       string  $staffCode  staff code.
     * @param       string  $startDate  start date.
     * @param       string  $endDate    end date.
     * @return      bool                special leave usable / special leave unusable.
     * by Andy (2020-01-21)
     */
    function specialLeaveOverlap($staffCode, $startDate, $endDate)
    {
        $buf = $this->sql->sqlSelectSpecialLeaveOverlap($staffCode, $startDate, $endDate);
        foreach ($buf as $row) {
            if (isset($row['specialLeaveNum'])) return true;
        }
        return false;
    }

    /**
     * get last special leave number to create new special leave number.
     *
     * @return      int     new special leave number.
     * by Andy (2020-01-21)
     */
    function getNewSpecialLeaveNum()
    {
        $buf = $this->sql->sqlSelectNewSpecialLeaveNum();
        foreach ($buf as $row) {
            if (isset($row["newSpecialLeaveNum"])) return $row["newSpecialLeaveNum"];
        }
        return 1;
    }

    /**
     * get special leave use reference.
     *
     * @param       string  $leaveLogNum    leave log number.
     * @return      array|bool              special leave.
     * by Andy (2020-01-21)
     */
    function getSpecialLeaveByReference($leaveLogNum)
    {
        $buf = $this->sql->sqlSelectSpecialLeaveUseReference($leaveLogNum);
        foreach ($buf as $row) {
            return $row;
        }
        return false;
    }

    /**
     * add new special leave use reference.
     *
     * @param       string  $leaveLogNum        leave log number.
     * @param       string  $specialLeaveNum    special leave number.
     * @return      mysqli_result|bool          For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function addSpecialLeaveReference($leaveLogNum, $specialLeaveNum)
    {
        return $this->sql->sqlInsertSpecialLeaveReference($leaveLogNum, $specialLeaveNum);
    }

    /**
     * add new special leave.
     *
     * @param       string  $specialLeaveNum    special leave number.
     * @param       string  $staffCode          staff code.
     * @param       string  $startDate          start date.
     * @param       string  $endDate            end date.
     * @param       string  $duration           duration.
     * @param       string  $reason             reason.
     * @return      mysqli_result|bool          For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function addSpecialLeave($specialLeaveNum, $staffCode, $startDate, $endDate, $duration, $reason)
    {
        return $this->sql->sqlInsertSpecialLeave($specialLeaveNum, $staffCode, $startDate, $endDate, $duration, $reason);
    }
    
    /**
     * update special leave duration.
     *
     * @param       string  $specialLeaveNum    special leave number.
     * @param       string  $duration           duration.
     * @return      mysqli_result|bool          For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function updateSpecialLeaveDuration($specialLeaveNum, $duration)
    {
        return $this->sql->sqlUpdateSpecialLeaveDuration($specialLeaveNum, $duration);
    }
    
    /**
     * update special leave status.
     *
     * @param       string  $specialLeaveNum    special leave number.
     * @param       string  $status             status.
     * @return      mysqli_result|bool          For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function updateSpecialLeaveStatus($specialLeaveNum, $status)
    {
        return $this->sql->sqlUpdateSpecialLeaveStatus($specialLeaveNum, $status);
    }

    /**
     * delete special leave use reference by leave log number.
     *
     * @param       string  $leaveLogNum    leave log number.
     * @return      mysqli_result|bool      For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function deleteSpecialLeaveReference($leaveLogNum)
    {
        return $this->sql->sqlDeleteSpecialLeaveReferenceByLeaveLogNum($leaveLogNum);
    }

    /**
     * use special leave quota.
     *
     * @param       string  $staffCode      staff code.
     * @param       string  $leaveLogNum    leave log number.
     * @return      bool                    success / fail.
     * by Andy (2020-01-21)
     */
    function useSpecialLeaveQuota($staffCode, $leaveLogNum)
    {
        $_leaveLog = new LeaveLog();
        $leaveLog = $_leaveLog->getLeaveLog($leaveLogNum);
        $specialLeaveNum = $this->getUsableSpecialLeave($staffCode, $leaveLog['startDate'], $leaveLog['endDate'], $leaveLog['duration']);
        if ($specialLeaveNum) {
            if ($this->addSpecialLeaveReference($leaveLogNum, $specialLeaveNum)) {
                $specialLeave = $this->getSpecialLeave($specialLeaveNum);
                if ($specialLeave['duration'] >= $leaveLog['duration']) {
                    if ($this->updateSpecialLeaveDuration($specialLeaveNum, ($specialLeave['duration'] - $leaveLog['duration']))) {
                        echo "扣除特別假額度成功<br>";
                        return true;
                    } else echo "扣除特別假額度失敗<br>";
                } else echo "無法通過，超出特別假額度<br>";
            } else echo "新增特別假參考失敗<br>";
        }
        return false;
    }
    
    /**
     * return special leave quota.
     *
     * @param       string  $leaveLogNum    leave log number.
     * @return      mysqli_result|bool      For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function returnSpecialLeaveQuota($leaveLogNum)
    {
        $_leaveLog = new LeaveLog();
        $leaveLog = $_leaveLog->getLeaveLog($leaveLogNum);
        $specialLeaveWithRef = $this->getSpecialLeaveByReference($leaveLogNum);
        $duration = $specialLeaveWithRef['duration'] + $leaveLog['duration'];
        return $this->updateSpecialLeaveDuration($specialLeaveWithRef['specialLeaveNum'], $duration);
    }
    
    /**
     * check special leave usable.
     *
     * @param       string  $staffCode  staff code.
     * @param       string  $date       use leave date.
     * @return      mysqli_result|bool  For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-22)
     */
    function specialLeaveUsable($staffCode, $today)
    {
        $buf = $this->getUnexpireSpecialLeave($staffCode, $today);
        foreach ($buf as $row) {
            if (isset($row['specialLeaveNum'])) return true;
        }
        return false;
    }
}
