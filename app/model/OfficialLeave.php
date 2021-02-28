<?php

namespace model;

class OfficialLeave
{
    function __construct()
    {
        $this->sql = new SendSQL();
    }

    /**
     * Conversion of seniority into official leaves days.
     *
     * @param       string  $seniority  seniority. (format: Y-M-D)
     * @return      int                 official leaves days.
     * by Andy (2020-12-03)
     */
    function seniorityToLeave($seniority)
    {
        $dateInt = explode("-", $seniority);
        if ($dateInt[0] == 0) {
            if ($dateInt[1] > 0 or $dateInt[2] > 0) {
                return 3;
            } else {
                return 0;
            }
        } elseif ($dateInt[0] == 1)
            return 7;
        elseif ($dateInt[0] == 2)
            return 10;
        elseif ($dateInt[0] == 3 or $dateInt[0] == 4)
            return 14;
        elseif ($dateInt[0] >= 5 and $dateInt[0] <= 9)
            return 15;
        elseif ($dateInt[0] == 10)
            return 16;
        elseif ($dateInt[0] == 11)
            return 17;
        elseif ($dateInt[0] == 12)
            return 18;
        elseif ($dateInt[0] == 13)
            return 19;
        elseif ($dateInt[0] == 14)
            return 20;
        elseif ($dateInt[0] == 15)
            return 21;
        elseif ($dateInt[0] == 16)
            return 22;
        elseif ($dateInt[0] == 17)
            return 23;
        elseif ($dateInt[0] == 18)
            return 24;
        elseif ($dateInt[0] == 19)
            return 25;
        elseif ($dateInt[0] == 20)
            return 26;
        elseif ($dateInt[0] == 21)
            return 27;
        elseif ($dateInt[0] == 22)
            return 28;
        elseif ($dateInt[0] == 23)
            return 29;
        elseif ($dateInt[0] >= 24)
            return 30;
        else return 0;
    }

    /**
     * The percentage of official leave for each stage of a person is the same, this function can calculate the percentage corresponding to all dates.
     * For details, please see the notes of calOfficialLeave function.
     * 
     * @param       int  $fy                 First year.
     * @param       int  $fm                 First month.
     * @param       int  $fd                 First day.
     * @param       int  $seniorityRange     Corresponding to the current seniority, if the seniority is less than or equal to half a year, it is 6; if the seniority is greater than or equal to one year or higher, it is 12.
     * @return      int                      Proportion of official leave.
     * by Andy (2020-12-03)
     */
    function yearProportion($fy, $fm, $fd, $seniorityRange)
    {
        $res = (($fm - 1) + ($fd - 1) / cal_days_in_month(CAL_GREGORIAN, $fm, $fy)) / $seniorityRange;
        return ($res > 1) ? 1 : $res;
    }

    /**
     * Calculate first date.
     * If staff have leave without pay before, the first working day needs to be postponed on the number of days leave without pay.
     * 
     * @param       int  $staffCode staffCode.
     * @param       int  $startDate startDate.
     * @return      string          date.
     * by Andy (2020-01-21)
     */
    function calFirstDate($staffCode, $startDate)
    {
        $_leaveWithoutPay = new LeaveWithoutPay();
        $result = strtotime($startDate);
        $leaveWithoutPay = $_leaveWithoutPay->getLeaveWithoutPay($staffCode); // 留職停薪紀錄
        foreach ($leaveWithoutPay as $row) {
            if ($row['resignDay'] != null and $row['returnDay'] != null) {
                $buf = date_diff(date_create($row['resignDay']), date_create($row['returnDay']))->format("%y-%m-%d");
                $buf = explode('-', $buf);
                $result = strtotime("+$buf[0] years +$buf[1] months +$buf[2] days", $result);
            }
        }
        return date("Y-m-d", $result);
    }

    /**
     * Calculate official leave start date.
     * 
     * @param       int  $firstDay  first day.
     * @param       int  $startYear start year.
     * @return      string          date.
     * by Andy (2020-01-21)
     */
    function calOffStartDate($firstDay, $startYear)
    {
        $firstOffStartDate = date("Y-m-d", strtotime($firstDay . "+6 month"));
        $datetime_startYear = date_create("{$startYear}-01-01");
        $datetime_firstOff = date_create($firstOffStartDate);
        if ($datetime_firstOff->getTimestamp() > $datetime_startYear->getTimestamp())
            return $firstOffStartDate;
        else return "$startYear-01-01";
    }

    /**
     * Calculate this year’s official leaves. (Can't calculate the first year)
     * 
     * Description:
     * The work start day can divide the time into "before the working day" and "after the work start date".
     * Official leaves of one year consists of part A and part B.
     * Part A is the seniority that started last year, proportion of leave from the beginning of the new year to the day before work start day. Which is "before the working day".
     * Part B is the seniority that started this year, Percentage of leave from the beginning of work to the end of the year. Which is "after the work start date".
     * 
     * @param       string  $staffCode  staff code.
     * @param       string  $startYear  The work start year.
     * @return      array               [0 ~ 1] Period of use, [2] official leave days.
     * by Andy (2020-12-03)
     */
    function calOfficialLeave($staffCode, $startYear)
    {
        $time = new Time();
        $_staff = new Staff();
        $result = 0;
        $thisYear = $startYear;
        $nextYear = $startYear + 1;
        $staff = $_staff->getstaffData($staffCode);
        $firstDay = $this->calFirstDate($staffCode, $staff['firstDay']);
        $startBuf = explode("-", $firstDay);
        $day = cal_days_in_month(CAL_GREGORIAN, $startBuf[1], $startBuf[0]);

        // Last seniority, next half official leave.
        $seniority = $time->dateInterval($firstDay, "{$thisYear}-01-01");
        $offLeave = $this->seniorityToLeave($seniority);
        $seniorityRange = ($offLeave <= 3) ? 6 : 12;
        $lastOffLeave = $this->yearProportion($startBuf[0], $startBuf[1], $startBuf[2], $seniorityRange) * $offLeave;
        $result += $lastOffLeave;
        $log = "(({$startBuf[1]} - 1) + ({$startBuf[2]} - 1) / {$day}) / {$seniorityRange} * {$offLeave}";

        // This seniority, first half official leave.
        if ((int)$startBuf[1] == 1 and (int)$startBuf[2] == 1) {
            // 如果是1月1號入職，官方公式會出錯，當公式出錯時，參考網站直接顯示結果，不顯示公式。
            // https://calc.mol.gov.tw/Trail_New/html/RestDays.html
            $seniority = $time->dateInterval($firstDay, "{$thisYear}-01-01");
        } else $seniority = $time->dateInterval($firstDay, "{$nextYear}-01-01");
        $offLeave = $this->seniorityToLeave($seniority);
        $seniorityRange = ($offLeave <= 3) ? 6 : 12;
        $thisOffLeave = $offLeave - ($this->yearProportion($startBuf[0], $startBuf[1], $startBuf[2], $seniorityRange) * $offLeave);
        $result += $thisOffLeave;
        $result = round(round(round(round(round($result, 5), 4), 3), 2), 1);
        // echo "新年到了!" . "<br>";
        // echo "在職期間: " . "{$firstDay} ~ {$thisYear}-01-01" . "<br>";
        // echo "目前年資: " . dateInterval($firstDay, "{$thisYear}-01-01") . " | " . "現階段特休: " . seniorityToLeave(dateInterval($firstDay, "{$thisYear}-01-01")) . "天" . "<br>";
        // echo "公式: {$log} + {$offLeave} - (({$startBuf[1]} - 1) + ({$startBuf[2]} - 1) / {$day}) / {$seniorityRange} * {$offLeave}" . "<br>";
        if ($result > 0) {
            // echo "{$thisYear}-01-01 ~ {$thisYear}-12-31 有特休: " . $result . "天<br>";
            return [$this->calOffStartDate($firstDay, $thisYear), "{$thisYear}-12-31", $result];
        } else {
            return ["0000-00-00", "0000-00-00", 0];
        }
    }

    /**
     * Calculate first year’s official leaves. (Only calculate the first year)
     * The seniority in the first year can be up to one year, at least 0.
     * 
     * Description:
     * The work start day can divide the time into "before the working day" and "after the work start date".
     * Official leaves of one year consists of part A and part B.
     * Part A is the seniority that started last year, proportion of leave from the beginning of the new year to the day before work start day. Which is "before the working day".
     * Part B is the seniority that started this year, Percentage of leave from the beginning of work to the end of the year. Which is "after the work start date".
     * 
     * @param       string  $staffCode  staff code.
     * @param       string  $startDate  The work start day.
     * @return      array               [0 ~ 1] Period of use, [2] official leave days.
     * by Andy (2020-12-03)
     */
    function calOfficialLeaveNewEmp($staffCode, $startDate)
    {
        $time = new Time();
        $result = 0;
        $thisYear = date('Y');
        $startDate = $this->calFirstDate($staffCode, $startDate);
        $startBuf = explode("-", $startDate);
        // $day = cal_days_in_month(CAL_GREGORIAN, $startBuf[1], $startBuf[0]);

        // This seniority, first half official leave.
        $seniority = $time->dateInterval($startDate, "{$thisYear}-12-31");
        $offLeave = $this->seniorityToLeave($seniority);
        $seniorityRange = ($offLeave <= 3) ? 6 : 12;
        $result = $offLeave - ($this->yearProportion($startBuf[0], $startBuf[1], $startBuf[2], $seniorityRange) * $offLeave);
        $result = round(round(round(round(round($result, 5), 4), 3), 2), 1);
        // echo "新員工到了!" . "<br>";
        // echo "預期今年在職: " . "{$startDate} ~ {$thisYear}-12-31" . "<br>";
        // echo "年資將有: " . "{$seniority}" . " | " . "現階段特休: " . $offLeave . "天" . "<br>";
        // echo "公式: {$offLeave} - (({$startBuf[1]} - 1) + ({$startBuf[2]} - 1) / {$day}) / {$seniorityRange} * {$offLeave}" . "<br>";
        if ($result > 0) {
            $date = date("Y-m-d", mktime(0, 0, 0, $startBuf[1] + 6, $startBuf[2], $startBuf[0]));
            // echo "{$date} ~ {$thisYear}-12-31 有特休: " . $result . "天<br>";
            return [$date, "{$thisYear}-12-31", $result];
        } else {
            return ["0000-00-00", "0000-00-00", 0];
        }
    }

    /**
     * Merge management calculate official leaves function.
     * 
     * @param       string  $staffCode  staff code.
     * @param       string  $startDate  If new employee is work start day, if new year is calculate offical leave start year.
     * @param       string  $newEmp     Determine whether it is the first year.
     * @return      array               [0 ~ 1] Period of use, [2] official leave days.
     * by Andy (2020-12-03)
     */
    function newEmpOrNewYear($staffCode, $startDate, $newEmp)
    {
        if ($newEmp) {
            $buf = $this->calOfficialLeaveNewEmp($staffCode, $startDate);
            // echo $buf[0].$buf[1].$buf[2];
        } else {
            $buf = $this->calOfficialLeave($staffCode, $startDate);
            // echo $buf[0].$buf[1].$buf[2];
        }
        // echo "<br>";
        return $buf;
    }

    /**
     * Calculate official leave start date.
     * 
     * @param       string  $staffCode  staff code.
     * @param       string  $startDate  start date.
     * @param       string  $endDate    end date.
     * @param       string  $leaveDays  leave days.
     * @param       bool    $isWebPage  is web page.
     * @return      bool                can use / can't use.
     * by Andy (2020-01-21)
     */
    function checkOfficialLeave($staffCode, $startDate, $endDate, $leaveDays, $isWebPage)
    {
        $time = new Time();
        $startYear = explode('-', explode(' ', $startDate)[0])[0];
        $officialLeave = $this->newEmpOrNewYear($staffCode, $startYear, false);
        return ($officialLeave[2] >= $this->statsUsingOfficialLeave($staffCode, $startYear, $leaveDays, $isWebPage) and // 今年特休總天數 >= 使用中的特休天數。 ($leaveDays: 正要使用的天數，$isWebPage: true會包含審查中的天數)
            $time->datesInclude($officialLeave[0] . ' 00:00:00', $officialLeave[1] . ' 23:59:59', $startDate, $endDate) // 請假期間必須完全包含在特休期間才能請特休假。
        );
    }

    /**
     * Get data about employees' remaining official leave.
     *
     * @param       string  $staffCode  staff code.
     * @return      float               This year official leave days remaining.
     * by Andy (2020-12-03)
     */
    function getOfficialLeave($staffCode)
    {
        $_staff = new Staff();
        $staff = $_staff->getstaffData($staffCode);
        if (isset($staff["official_leave"])) return $staff["official_leave"];
        else return 0.0;
    }

    /**
     * Get data about employees' remaining official leave.
     *
     * @param       string  $staffCode  staff code.
     * @param       string  $startYear  start year.
     * @param       string  $leaveDays  leave days.
     * @param       bool    $isWebPage  is web page.
     * @return      float               official leave days remaining.
     * by Andy (2020-01-21)
     */
    function statsUsingOfficialLeave($staffCode, $startYear, $leaveDays, $isWebPage)
    {
        $leaveLog = new LeaveLog();
        $buf = $leaveLog->getLeaveLogByDateStatus($staffCode, $startYear . "-01", $startYear . "-12", $isWebPage);
        $useing = $leaveDays;
        foreach ($buf as $row) {
            if ($row["leaveType"] == "特休假" and $row["status"] != '已駁回') $useing += ($row["duration"] / 7.5);
        }
        return $useing;
    }

    /**
     * update official leave.
     *
     * @param       string  $staffCode  staff code.
     * @param       string  $OffLeave   official leave.
     * @return      mysqli_result|bool  For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function updateOfficialLeave($staffCode, $officialLeave)
    {
        return $this->sql->sqlUpdateOfficialLeave($staffCode, $officialLeave);
    }

    /**
     * reset official leave.
     *
     * @param       string  $staffCode  staff code.
     * @param       array   $offLeave   official leave.
     * @return      mysqli_result|bool  For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function updateOffLeaveReset($staffCode, $offLeave)
    {
        $_staff = new Staff();
        $staffList = $_staff->getstaffData($staffCode);
        $buf = $this->sql->sqlUpdateOffLeaveReset($staffCode, $offLeave[2], $offLeave[0], $offLeave[1]);
        echo $buf;
        if ($buf == 1) {
            echo "職員" . $staffList['desknum'] . "/" . $staffList['cName'] . "，特休更新成功!" . "<hr>";
            // echo $offLeave[0]."~".$offLeave[1]."有".$offLeave[2]."天";
            // echo "<hr>";
        } else {
            echo "職員" . $staffList['desknum'] . "/" . $staffList['cName'] . "，特休更新失敗" . "<hr>";
            // echo "<hr>";
        }
    }

    /**
     * When new year first day, must run this function to reset official leaves.
     *
     * by Andy (2020-12-03)
     */
    function resetOfficialLeave()
    {
        $_staff = new Staff();
        $accAuthority = new AccAuthority();
        $buf = $_staff->getStaffList(true, $accAuthority->getAccAuthority("封鎖"));
        foreach ($buf as $row) {
            $staffCode =  $row['staffCode'];
            $offLeave = $this->newEmpOrNewYear($staffCode, date('Y'), false);
            $offLeave[2] = $offLeave[2] - $this->statsUsingOfficialLeave($staffCode, date('Y'), 0, false);
            $this->updateOffLeaveReset($staffCode, $offLeave);
        }
    }
}
?>