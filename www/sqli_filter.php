<?php

    function sqli_detect_signup($str)
    {
        $signup_filter = array(
            'or', 'and', 'true', 'false', 'union', 'like','=', '>', '<', ';', '--', '/', '*', 'admin', "'", "select", "limit", "||", "script"
        );
        $str = strtolower($str);
        $str_origin = $str;

        for ($x = 0; $x <= sizeof($signup_filter); $x++)
        {
            $str = str_replace($signup_filter[$x], " ", $str);
        }
        
        if($str_origin !== $str)
        return true;
        
        else
        return false;
        
    }

    function sqli_detect_blog($str)
    {
        $blog_filter = array(
            'and', 'true', 'false', 'union', 'like', '>', '<', ';', '--', '*', 'admin', "'", "select", "limit", "||", "script"
        );
        $str = strtolower($str);

        for ($x = 0; $x <= sizeof($blog_filter); $x++)
        {
            $str = str_replace($blog_filter[$x], " ", $str);
        } 
        
        return $str;
    }
?>