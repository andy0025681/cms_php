<?php

namespace model;

class CreateOption
{
    /**
     * gender option on form.
     *
     * @param       string  $gender gender.
     * @return      string          HTML form option.
     * by Andy (2020-01-21)
     */
    function genderOption($gender)
    {
        $result = '<label>男</label>';
        $result .= '<input type="radio" name="gender" id="male" value="male"';
        if ($gender == 'male') $result .= 'checked ';
        $result .= 'required>';
        $result .= '<label>女</label>';
        $result .= '<input type="radio" name="gender" id="female" value="female"';
        if ($gender == 'female') $result .= 'checked';
        $result .= '>';
        return $result;
    }

    /**
     * staff list option on form.
     *
     * @return      string          HTML form option.
     * by Andy (2020-01-21)
     */
    function staffListOption()
    {
        $staff = new Staff();
        $accAuthority = new AccAuthority();
        $result = '';
        $buf = $staff->getStaffList(true, $accAuthority->getAccAuthority("封鎖"));
        foreach ($buf as $row) {
            if (isset($row["desknum"]) and isset($row["eName"]) and isset($row["staffCode"]))
                $result .= '<option value="' . $row["staffCode"] . '">' . $row["desknum"] . '/' . $row["eName"] . '</option>';
        }
        return $result;
    }

    /**
     * leave type option list on form.
     *
     * @param       string  $staffCode  staff code.
     * @param       string  $gender     gender.
     * @return      string              HTML form option.
     * by Andy (2020-01-21)
     */
    function leaveTypeListOption($staffCode, $gender)
    {
        $time = new Time();
        $leaveType = new LeaveType();
        $specialLeave = new SpecialLeave();
        $result = '';
        $buf = $leaveType->getLeaveTypeByGender($gender);
        foreach ($buf as $row) {
            if (isset($row["leaveType"])) {
                $leaveType = $row["leaveType"];
                if ($leaveType == '特休假') {
                    // 特休假選項必須一直存在，因為使用者可以預先請任何一年的特休假。
                } elseif ($leaveType == '特別假' and !$specialLeave->specialLeaveUsable($staffCode, $time->todayTW())) {
                    continue;
                } elseif ($leaveType == '生日特別假') {
                    // 生日特別假一直存在，因為使用者可以預先請任何一年。
                }
                $result .= '<option value="' . $leaveType . '">' . $leaveType . '</option>';
            }
        }
        return $result;
    }

    /**
     * agent option list on form.
     *
     * @param       string  $staffCode  staff code.
     * @param       string  $department department.
     * @return      string              HTML form option.
     * by Andy (2020-01-21)
     */
    function agentListOption($staffCode, $department)
    {
        $staff = new Staff();
        $extraAgentRelation = new ExtraAgentRelation();
        $accAuthority = new AccAuthority();
        $result = '';
        $staffData = $staff->getstaffData($staffCode);
        $sameDepartment = $staff->getDepartmentStaffList($staffCode, $department);
        $extraAgent = $extraAgentRelation->getExtraAgentRelationList($staffCode);
        foreach ($extraAgent as $row) { // 先處裡額外代理人
            if (isset($row['colleagueStaffCode']) and $row['isAgent']) {
                $agentStaff = $staff->getstaffData($row['colleagueStaffCode']);
                if (!$accAuthority->accDisable($agentStaff['authority'])) // 如果是合格額外代理人
                    $result .= "<option value='" . $agentStaff['staffCode'] . "/" . $agentStaff['eName'] . "'>" . $agentStaff['desknum'] . "/" . $agentStaff['eName'] . "</option>";
            }
        }
        if (strpos($staffData['job_title'], "主管") or strpos($staffData['job_title'], "經理")) {
        } else {
            foreach ($sameDepartment as $row1) {
                $isAgent = true;
                foreach ($extraAgent as $row2) { // 如果和額外代理人重疊直接跳過
                    $isAgent = $row1['staffCode'] != $row2['colleagueStaffCode'];
                    if (!$isAgent) break;
                }
                if ($isAgent) {
                    $agentStaff = $staff->getstaffData($row1['staffCode']);
                    if (!$accAuthority->accDisable($agentStaff['authority'])) // 如果是合格代理人
                        $result .= "<option value='" . $agentStaff['staffCode'] . "/" . $agentStaff['eName'] . "'>" . $agentStaff['desknum'] . "/" . $agentStaff['eName'] . "</option>";
                }
            }
        }
        return $result;
    }

    /**
     * department option list on form.
     *
     * @param       string  $department department.
     * @return      string              HTML form option.
     * by Andy (2020-01-21)
     */
    function getDepartmentListOption($department)
    {
        $department = new Department();
        $result = '';
        $buf = $department->getDepartmentList();
        foreach ($buf as $row) {
            if (isset($row["department"])) {
                if ($row["department"] == $department)
                    $result .= '<option value="' . $row["department"] . '" selected >' . $row["department"] . '</option>';
                else $result .= '<option value="' . $row["department"] . '">' . $row["department"] . '</option>';
            }
        }
        return $result;
    }
}
