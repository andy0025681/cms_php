<?php

namespace model;

class Staff
{
    function __construct()
    {
        $this->sql = new SendSQL();
    }

    /**
     * get staff data by staff code.
     *
     * @param       string  $staffCode  staff code.
     * @return      array|bool          staff data.
     * by Andy (2020-01-21)
     */
    function getstaffData($staffCode)
    {
        $buf = $this->sql->sqlSelectStaff($staffCode);
        foreach ($buf as $row) {
            return $row;
        }
        return false;
    }

    /**
     * get staff list data by exclude and authority.
     *
     * @param       string  $exclude        set exclude to choose state.
     * @param       string  $authority      authority.
     * @return      mysqli_result|bool      For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function getStaffList($exclude, $authority)
    {
        return $this->sql->sqlSelectStaffList($exclude, $authority);
    }

    /**
     * get staff list data by authority.
     *
     * @param       string  $authority      authority.
     * @return      mysqli_result|bool      For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function getStaffListByAuthority($authority)
    {
        return $this->sql->sqlSelectStaffListByAuthority($authority);
    }

    /**
     * get staff data and staff account by email.
     *
     * @param       string  $email  email.
     * @return      array|bool      staff data and account.
     * by Andy (2020-01-21)
     */
    function getstaffDataAndAccByEmail($email)
    {
        $buf = $this->sql->sqlSelectStaffDataAndAccByEmail($email);
        foreach ($buf as $row) {
            return $row;
        }
        return false;
    }

    /**
     * get department leader staff data by department.
     *
     * @param       string  $department department.
     * @return      string              "name_email".
     * by Andy (2020-01-21)
     */
    function getDepartmentLeaderNameEmail($department)
    {
        $buf = $this->sql->sqlSelectDepartmentLeader($department);
        $name = null;
        $email = null;
        foreach ($buf as $row) {
            $name = $row["desknum"] . ' ' . $row["eName"] . ' ' . $row["eLastName"] . " / QA Transport";
            $email = $row["email"];
        }
        if ($name == null or $email == null) return false;
        return $name . "_" . $email;
    }

    /**
     * get last staff code to create new staff code.
     *
     * @return      int     new staff code.
     * by Andy (2020-01-21)
     */
    function getNewStaffCode()
    {
        $buf = $this->sql->sqlSelectNewStaffCode();
        foreach ($buf as $row) {
            if (isset($row["newStaffCode"])) return $row["newStaffCode"];
        }
        return 0;
    }

    /**
     * get the name and email of the top leader
     *
     * @return      string              "name_email".
     * by Andy (2020-12-03)
     */
    function getSupremeLeaderNameEmail()
    {
        $accAuthority = new AccAuthority();
        $buf = $this->getStaffList(false, $accAuthority->getAccAuthority("最高權限"));
        foreach ($buf as $row) {
            $name = $row["desknum"] . ' ' . $row["eName"] . ' ' . $row["eLastName"] . " / QA Transport";
            $email = $row["email"];
            break;
        }
        return $name . "_" . $email;
    }

    /**
     * get same department staff data, by staff's staff code and department.
     *
     * @param       string  $staffCode      staff code.
     * @param       string  $department     department.
     * @return      mysqli_result|bool      For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function getDepartmentStaffList($staffCode, $department)
    {
        return $this->sql->sqlSelectDepartmentStaffList($staffCode, $department);
    }

    /**
     * add new staff data.
     *
     * @param       string  $staffCode              staff code.
     * @param       string  $cName                  cName.
     * @param       string  $eName                  eName.
     * @param       string  $eLastName              eLastName.
     * @param       string  $gender                 gender.
     * @param       string  $email                  email.
     * @param       string  $firstDay               firstDay.
     * @param       string  $desknum                desknum.
     * @param       int     $official_leave         official leave.
     * @param       string  $off_leave_start_date   off leave start date.
     * @param       string  $off_leave_end_date     off leave end date.
     * @param       string  $authority              authority.
     * @param       string  $department             department.
     * @param       string  $birthday               birthday.
     * @return      bool                            success / fail.
     * by Andy (2020-01-21)
     */
    function addNewStaff($staffCode, $cName, $eName, $eLastName, $gender, $email, $cellPhone, $firstDay, $desknum, $authority, $department, $birthday)
    {
        $officialLeave = new OfficialLeave();
        $offLeave = $officialLeave->newEmpOrNewYear($staffCode, $firstDay, true);
        // insert staff into staff list
        if ($this->sql->sqlInsertNewStaff(
            $staffCode,
            $cName,
            $eName,
            $eLastName,
            $gender,
            $email,
            $cellPhone,
            $firstDay,
            $desknum,
            $offLeave[2],
            $offLeave[0],
            $offLeave[1],
            $authority,
            $department,
            $birthday
        )) {
            echo "職員新增成功!" . "<br>";
            // insert staff account into staff account list
            $staffAccountList = new StaffAccountList();
            if ($staffAccountList->addNewAcc($staffCode, $email)) {
                echo "職員帳號初始化成功!";
            } else {
                echo "職員帳號初始化失敗";
                return false;
            }
        } else {
            echo "職員新增失敗" . "<br>";
            return false;
        }
        return true;
    }

    /**
     * update staff data.
     *
     * @param       string  $staffCode              staff code.
     * @param       string  $cName                  cName.
     * @param       string  $eName                  eName.
     * @param       string  $eLastName              eLastName.
     * @param       string  $gender                 gender.
     * @param       string  $email                  email.
     * @param       string  $firstDay               firstDay.
     * @param       string  $desknum                desknum.
     * @param       int     $official_leave         official leave.
     * @param       string  $off_leave_start_date   off leave start date.
     * @param       string  $off_leave_end_date     off leave end date.
     * @param       string  $authority              authority.
     * @param       string  $job_title              job title.
     * @param       string  $department             department.
     * @param       string  $birthday               birthday.
     * @param       string  $resignDay              resign day.
     * @return      mysqli_result|bool              For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function updateStaffData($staffCode, $cName, $eName, $eLastName, $gender, $email, $cellPhone, $firstDay, $desknum, $official_leave, $off_leave_start_date, $off_leave_end_date, $authority, $job_title, $department, $birthday, $resignDay)
    {
        return $this->sql->sqlUpdateStaffData($staffCode, $cName, $eName, $eLastName, $gender, $email, $cellPhone, $firstDay, $desknum, $official_leave, $off_leave_start_date, $off_leave_end_date, $authority, $job_title, $department, $birthday, $resignDay);
    }

    /**
     * update staff resign day by staff code and resign day.
     *
     * @param       string  $staffCode      staff code.
     * @param       string  $resignDay      resign day.
     * @return      mysqli_result|bool      For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function updateStaffResignDay($staffCode, $resignDay)
    {
        return $this->sql->sqlUpdateStaffResignDay($staffCode, $resignDay);
    }

    /**
     * update staff email by staff code and email.
     *
     * @param       string  $staffCode  staff code.
     * @param       string  $email      email.
     * @return      mysqli_result|bool  For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function updateStaffEmail($staffCode, $email)
    {
        return $this->sql->sqlUpdateStaffEmail($staffCode, $email);
    }

    /**
     * update staff authority by staff code and authority.
     *
     * @param       string  $staffCode      staff code.
     * @param       string  $authority      authority.
     * @return      mysqli_result|bool      For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function updateAccAuthority($staffCode, $explanation)
    {
        $accAuthority = new AccAuthority();
        return $this->sql->sqlUpdateAccAuthority($staffCode, $accAuthority->getAccAuthority($explanation));
    }

    /**
     * disable staff account.
     *
     * @param       string  $staffCode  staff code.
     * @return      bool                success / fail.
     * by Andy (2020-01-21)
     */
    function disableStaffAcc($staffCode)
    {
        $result = true;
        $today = date('Y') . '-' . date('m') . '-' . date('d');
        if (!$this->updateStaffResignDay($staffCode, $today)) $result = false;
        if (!$this->updateStaffEmail($staffCode, '')) $result = false;
        if (!$this->updateAccAuthority($staffCode, '封鎖')) $result = false;
        return $result;
    }

    /**
     * if leave type is specific update leave quota.
     *
     * @param       string  $staffCode      staff code.
     * @param       string  $leaveLogNum    leave log number.
     * @param       string  $leaveDuration  leave duration.
     * @param       string  $oldStatus      old status.
     * @param       string  $newStatus      new status.
     * @return      mysqli_result|bool      For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-20)
     */
    function updateLeaveQuota($staffCode, $leaveLogNum, $leaveDuration, $oldStatus, $newStatus)
    {
        $_officialLeave = new OfficialLeave();
        $birthdayLeave = new BirthdayLeave();
        $specialLeave = new SpecialLeave();
        $leaveType = new LeaveType();
        $result = true;
        $leaveNum = explode('-', $leaveLogNum)[1];
        if ($newStatus == "已通過") { // 如果新狀態是已通過
            if ($leaveNum == $leaveType->getLeaveTypeNum("特休假")) { // 如果是特休假。
                $oldLeave = $_officialLeave->getOfficialLeave($staffCode); // 取得現有特休額度
                if ($oldLeave < ($leaveDuration / 7.5)) $result = false; // 如果申請超出額度 (前端已限制，現有額度必須足夠，使用者才能申請特休。 由於使用者申請並不會扣除額度，因此有可能超額申請，此時主管點選同意，將在此被阻擋。)
                else {
                    $officialLeave = $oldLeave - ($leaveDuration / 7.5); // 刪除休假用掉的特休。 公式: 現有特休(天) - ( 使用特休(小時) / 7.5(每日工時) )(天)
                    if (!$_officialLeave->updateOfficialLeave($staffCode, $officialLeave)) $result = false; // 如果更新失敗，改為異常狀態。
                }
            } elseif ($leaveNum == $leaveType->getLeaveTypeNum("特別假")) { // 如果是特別假。
                if (!$specialLeave->useSpecialLeaveQuota($staffCode, $leaveLogNum)) $result = false;
            } elseif ($leaveNum == $leaveType->getLeaveTypeNum("生日特別假")) { // 如果是生日特別假。
                $_leaveLog = new LeaveLog();
                $leaveLog = $_leaveLog->getLeaveLog($leaveLogNum);

                if ( // 額度不夠
                    $birthdayLeave->getBirthdayLeave($staffCode) < $leaveLog['duration'] or
                    // 日期無效
                    !$birthdayLeave->dateInBirthdayLeavePeriod($staffCode, $leaveLog['startDate'])
                ) {
                    $result = false;
                    echo "不合格，生日特別假無法通過<br>";
                } else {
                    if ($birthdayLeave->updateBirthdayLeave($staffCode, 0)) echo "生日特別假扣除成功<br>";
                    else {
                        $result = false;
                        echo "生日特別假扣除失敗<br>";
                    }
                }
            }
        } else { // 如果新狀態不是已通過
            if ($oldStatus != '審核中') { // 如果舊狀態不是審核中，才需要退還
                if ($leaveNum == $leaveType->getLeaveTypeNum("特休假")) { // 如果是特休假。
                    $oldLeave = $_officialLeave->getOfficialLeave($staffCode); // 取得現有特休額度
                    $officialLeave = $oldLeave + ($leaveDuration / 7.5); // 退還扣除的特休，因為狀態改變。 公式: 現有特休(天) + ( 使用特休(小時) / 7.5(每日工時) )(天)
                    if (!$_officialLeave->updateOfficialLeave($staffCode, $officialLeave)) $result = false; // 如果更新失敗，改為異常狀態。
                } elseif ($leaveNum == $leaveType->getLeaveTypeNum("特別假")) { // 如果是特別假。
                    if (!$specialLeave->returnSpecialLeaveQuota($leaveLogNum)) $result = false; // 如果退還額度失敗，改為異常狀態。
                } elseif ($leaveNum == $leaveType->getLeaveTypeNum("生日特別假")) { // 如果是生日特別假。
                    if (!$birthdayLeave->updateBirthdayLeave($staffCode, 1)) $result = false; // 如果退還額度失敗，改為異常狀態。
                }
            }
        }
        return $result;
    }
}
