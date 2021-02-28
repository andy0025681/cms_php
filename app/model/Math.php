<?php

namespace model;

class Math
{
    /**
     * unconditional carry (arithmetic)
     * 
     * @param       int  $v              Value to be carry.
     * @param       int  $precision      Number of decimal places.
     * @return      int                  Answer.
     * by Andy (2020-12-03)
     */
    function ceil_dec($v, $precision){
        $c = pow(10, $precision);
        return ceil($v*$c)/$c;
    }
    
    /**
     * unconditionally discard (arithmetic)
     * 
     * @param       int  $v              Value to be carry.
     * @param       int  $precision      Number of decimal places.
     * @return      int                  Answer.
     * by Andy (2020-12-03)
     */
    function floor_dec($v, $precision)
    {
        $c = pow(10, $precision);
        return floor($v * $c) / $c;
    }
}