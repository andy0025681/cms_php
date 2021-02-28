<?php

namespace model;

class LeaveType
{
    function __construct()
    {
        $this->sql = new SendSQL();
    }

    /**
     * get all leave type.
     *
     * @return      mysqli_result|bool      For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function getLeaveType()
    {
        return $this->sql->sqlSelectLeaveType();
    }

    /**
     * get leave type by gender.
     *
     * @param       string  $gender     gender.
     * @return      mysqli_result|bool  For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function getLeaveTypeByGender($gender)
    {
        return $this->sql->sqlSelectLeaveTypeByGender($gender);
    }

    /**
     * get leave type number.
     *
     * @param       string  $leaveType  leave type.
     * @return      int                 return leave number or false.
     * by Andy (2020-01-21)
     */
    function getLeaveTypeNum($leaveType)
    {
        $buf = $this->sql->sqlSelectLeaveTypeByLeaveType($leaveType);
        foreach ($buf as $row) {
            if (isset($row["leaveNum"])) return $row["leaveNum"];
        }
        return false;
    }
}
