<?php
    require_once('util/main.php');
    require_once('database/paginator.class.php');

    global $conn;
    global $action;

    $limit      = ( isset( $_GET['limit'] ) ) ? $_GET['limit'] : 10;
    $page       = ( isset( $_GET['page'] ) ) ? $_GET['page'] : 1;
    $links      = ( isset( $_GET['links'] ) ) ? $_GET['links'] : 7;

    if ($action == 'view_group')
    {
        $query = 'SELECT lastname,firstname,group,colorteam,special
                    FROM participants AS p
                    INNER JOIN participant_status_map AS m ON
                        p.id = m.participantID
                    WHERE p.group = " . $group_id;
                    ORDER BY p.lastname,p.id';
    }
    else if ($action == 'view_colorteam')
    {
        $query = 'SELECT lastname,firstname,group,colorteam,special
                    FROM participants AS p
                    INNER JOIN participant_status_map AS m ON
                        p.id = m.participantID
                    WHERE p.colorteam = " . $colorteam_id;
                    ORDER BY p.lastname,p.id';
    }
    else
    {
        $query = 'SELECT lastname,firstname,group,colorteam,special
                    FROM participants
                    INNER JOIN participant_status_map ON
                        participants.id = participant_status_map.participantID
                    ORDER BY group,lastname, id';
    }
  
    $Paginator  = new Paginator( $conn, $query );
  
    $results    = $Paginator->getData( $limit, $page );
?>