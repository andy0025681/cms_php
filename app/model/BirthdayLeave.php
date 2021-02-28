<?php

namespace model;

class BirthdayLeave
{
    function __construct()
    {
        $this->sql = new SendSQL();
        $this->staff = new Staff();
    }
    
    /**
     * get birthday leave.
     *
     * @param       string  $staffCode  staff code.
     * @return      string|float        birthday leave quota.
     * by Andy (2020-01-21)
     */
    function getBirthdayLeave($staffCode)
    {
        $staff = $this->staff->getstaffData($staffCode);
        if (isset($staff["birthday_leave"])) return $staff["birthday_leave"];
        else return 0.0;
    }

    /**
     * update birthday leave.
     *
     * @param       string  $staffCode      staff code.
     * @param       string  $value          value.
     * @return      mysqli_result|bool      For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function updateBirthdayLeave($staffCode, $value)
    {
        return $this->sql->sqlUpdateBirthdayLeave($staffCode, $value);
    }
    
    /**
     * reset birthday leave.
     *
     * @return      mysqli_result|bool          For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function resetBirthdayLeave()
    {
        return $this->sql->sqlResetBirthdayLeave();
    }

    /**
     * check leave date is in birthday leave period.
     *
     * @param       string  $staffCode  staff code.
     * @param       string  $leavedate  leave day.
     * @return      bool                can use / can't use.
     * by Andy (2020-01-21)
     */
    function dateInBirthdayLeavePeriod($staffCode, $leaveDay)
    {
        $time = new Time();
        $leaveLog = new LeaveLog();
        $staff = $this->staff->getstaffData($staffCode);
        if (isset($staff["birthday"])) {
            // 如果員工工作未滿3個月
            if (!$time->dateInterval(date('Y-m-d', strtotime($staff["firstDay"] . ' + 3 months')), $leaveDay)) {
                echo "<p style='color: red;'>工作未滿三個月，無法使用生日特別假</p>";
                return false;
                // 如果請假日期在生日之前
            } elseif (!$time->dateIntervalInTheYear($staff["birthday"], $leaveDay)) {
                echo "<p style='color: red;'>請假日期在生日之前，請假日期必須在生日開始一個月內</p>";
                return false;
                // 如果請假日期已經超過生日一個月以後
            } elseif (!$time->dateIntervalInTheYear($leaveDay, date('Y-m-d', strtotime($staff["birthday"] . ' + 1 months')))) {
                echo "<p style='color: red;'>請假日期超過生日一個月以後，請假日期必須在生日開始一個月內</p>";
                return false;
                // 如果請假日當年，已有生日特別假申請紀錄
            } elseif ($leaveLog->checkLeaveLogByDateLeaveType($staffCode, [explode('-', $leaveDay)[0]], '生日特別假', '')) {
                echo "<p style='color: red;'>請假日當年，已有生日特別假申請紀錄</p>";
                return false;
            } else return true;
            // 資料庫沒有紀錄員工的生日
        } else return false;
    }
}
?>