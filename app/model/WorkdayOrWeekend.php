<?php

namespace model;

class WorkdayOrWeekend
{
    function __construct()
    {
        $this->sql = new SendSQL();
    }

    /**
     * get workday or weekend by date.
     *
     * @param       string  $date       date.
     * @return      mysqli_result|bool  For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function getWorkdayOrWeekendByDate($date)
    {
        return $this->sql->sqlSelectWorkdayOrWeekendByDate($date);
    }
    
    /**
     * get last id to create new id.
     *
     * @return      mysqli_result|bool  For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function getNewWorkdayOrWeekendID()
    {
        $buf = $this->sql->sqlSelectNewWorkdayOrWeekendID();
        foreach ($buf as $row) {
            if (isset($row['newId'])) return $row['newId'];
        }
        return 1;
    }

    /**
     * add new workday or weekend.
     *
     * @param       string  $id         id.
     * @param       string  $startDate  start date.
     * @param       string  $endDate    end date.
     * @param       string  $reason     reason.
     * @param       string  $workday    workday.
     * @return      mysqli_result|bool  For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function addWorkdayOrWeekend($startDate, $endDate, $reason, $workday)
    {
        return $this->sql->sqlInsertworkdayOrWeekend($this->getNewWorkdayOrWeekendID(), $startDate, $endDate, $reason, $workday);
    }
}
