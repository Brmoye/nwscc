<?php
    function get_results($statement)
    {
        $results = [];
        $rs = $statement->get_result();
        while ( $row = $rs->fetch_assoc() ) 
        {
            $results[]  = $row;
        }
        
        $result         = new stdClass();
        $result->data   = $results;
        $statement->close();
        return $result;
    }
?>