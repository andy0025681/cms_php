<?php

namespace model;

class Department
{
    function __construct()
    {
        $this->sql = new SendSQL();
    }

    /**
     * get all department type.
     *
     * @return      mysqli_result|bool      For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function getDepartmentList()
    {
        return $this->sql->sqlSelectDepartmentList();
    }
}
