<?php

namespace model;

class ExtraAgentRelation
{
    function __construct()
    {
        $this->sql = new SendSQL();
    }

    /**
     * get extra agent relation list.
     *
     * @return      mysqli_result|bool  For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function getExtraAgentRelationList($staffCode)
    {
        return $this->sql->sqlSelectExtraAgentRelationList($staffCode);
    }
    
    /**
     * delete all related extra agent relation by staff code.
     *
     * @return      mysqli_result|bool  For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function deleteExtraAgentRelationByStaffCode($staffCode)
    {
        return $this->sql->sqlDeleteExtraAgentRelationByStaffCode($staffCode);
    }
}
