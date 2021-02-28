<?php
namespace model;

class AccAuthority
{
    function __construct()
    {
        $this->sql = new SendSQL();
    }

    /**
     * get all account authority type.
     *
     * @return      mysqli_result|bool  For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE. Returns FALSE on failure.
     * by Andy (2020-01-21)
     */
    function getAccAuthorityList()
    {
        return $this->sql->sqlSelectAccAuthorityList();
    }
    
    /**
     * get account authority type by explanation.
     *
     * @param       string  $explanation    explanation.
     * @return      string|bool             authority.
     * by Andy (2020-01-21)
     */
    function getAccAuthority($explanation)
    {
        $buf = $this->sql->sqlSelectAccAuthorityByExplain($explanation);
        foreach ($buf as $row) {
            if (isset($row['authority'])) return $row['authority'];
        }
        return false;
    }
    
    /**
     * get account authority type by authority.
     *
     * @param       string  $authority  authority.
     * @return      string|bool         explanation.
     * by Andy (2020-01-21)
     */
    function getAccExplain($authority)
    {
        $buf = $this->sql->sqlSelectAccAuthorityByAuthority($authority);
        foreach ($buf as $row) {
            if (isset($row['explanation'])) return $row['explanation'];
        }
        return false;
    }

    /**
     * check account authority is same.
     *
     * @param       string  $authority      authority.
     * @param       string  $explanation    explanation.
     * @return      bool                    same / not same.
     * by Andy (2020-01-21)
     */
    function sameAuthority($authority, $explanation)
    {
        return $this->getAccAuthority($explanation) == $authority;
    }
    
    /**
     * confirm account is disabled.
     *
     * @param       string  $authority      authority.
     * @return      bool                    disabled / not disabled.
     * by Andy (2020-01-21)
     */
    function accDisable($authority)
    {
        return $this->sameAuthority($authority, '封鎖');
    }
}
