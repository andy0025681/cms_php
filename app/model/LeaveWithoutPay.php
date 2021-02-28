<?php

namespace model;

class LeaveWithoutPay
{
    function __construct()
    {
        $this->sql = new SendSQL();
    }
    
    /**
     * get leave without pay by staff code.
     *
     * @param       string  $staffCode  staff code.
     * @return      mysqli_result|bool  For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function getLeaveWithoutPay($staffCode)
    {
        return $this->sql->sqlSelectLeaveWithoutPay($staffCode);
    }
    
    /**
     * get new id.
     *
     * @return      mysqli_result|bool  For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function getNewLeaveWithoutPayID()
    {
        $buf = $this->sql->sqlSelectNewLeaveWithoutPayID();
        foreach ($buf as $row) {
            if (isset($row['newId'])) return $row['newId'];
        }
        return 1;
    }

    /**
     * add new leave without pay.
     *
     * @param       string  $staffCode  staff code.
     * @param       string  $resignDay  resign day.
     * @param       string  $returnDay  return day.
     * @return      mysqli_result|bool  For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function addNewLeaveWithoutPayLog($staffCode, $resignDay, $returnDay)
    {
        return $this->sql->sqlInsertLeaveWithoutPay($this->getNewLeaveWithoutPayID(), $staffCode, $resignDay, $returnDay);
    }

    /**
     * delete leave without pay by staff code.
     *
     * @param       string  $staffCode  staff code.
     * @return      mysqli_result|bool  For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function deleteLeaveWithoutPay($staffCode)
    {
        return $this->sql->sqlDeleteLeaveWithoutPay($staffCode);
    }

    /**
     * have leave without pay.
     *
     * @param       string  $staffCode  staff code.
     * @return      bool                have / haven't.
     * by Andy (2020-01-21)
     */
    function haveLeaveWithoutPay($staffCode)
    {
        $buf = $this->getLeaveWithoutPay($staffCode);
        foreach ($buf as $row) {
            if (isset($row['id'])) return true;
        }
        return false;
    }
}
