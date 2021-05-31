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
        $query = "SELECT p.id,p.lastname,p.firstname,p.group,p.colorteam,p.special,m.statusID
                    FROM participants AS p
                    LEFT JOIN participant_status_map AS m ON
                        p.id = m.participantID
                    WHERE p.group = $group_id
                    ORDER BY p.lastname,p.id";
    }
    else if ($action == 'view_colorteam')
    {
        $query = "SELECT p.id,p.lastname,p.firstname,p.group,p.colorteam,p.special,m.statusID
                    FROM participants AS p
                    LEFT JOIN participant_status_map AS m ON
                        p.id = m.participantID
                    WHERE p.colorteam = $colorteam_id
                    ORDER BY p.lastname,p.id";
    }
    else
    {
        $query = 'SELECT p.id,p.lastname,p.firstname,p.group,p.colorteam,p.special,m.statusID
                    FROM participants AS p
                    LEFT JOIN participant_status_map AS m ON
                        p.id = m.participantID
                    ORDER BY p.group,p.lastname,p.id';
}
  
    $Paginator  = new Paginator( $conn, $query );
  
    $results    = $Paginator->getData( $limit, $page );

?>