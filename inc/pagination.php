<?php
    require_once('util/main.php');
    require_once('database/paginator.class.php');

    global $conn;
    global $action;

    $limit      = ( isset( $_GET['limit'] ) ) ? $_GET['limit'] : 10;
    $page       = ( isset( $_GET['page'] ) ) ? $_GET['page'] : 1;
    $links      = ( isset( $_GET['links'] ) ) ? $_GET['links'] : 7;

    if ($action == 'view_category' || $action == 'list_inventory')
    {
        $query = "SELECT category.name AS c_name, inventory.*
                    FROM inventory INNER JOIN category
                    ON inventory.categoryID = category.categoryID
                    WHERE inventory.categoryID = " . $category_id;
    }
    else
    {
        $query = "SELECT category.name AS c_name, inventory.* FROM inventory 
                    INNER JOIN category ON inventory.categoryID = category.categoryID 
                    ORDER BY inventory.categoryID, inventoryID";
    }
  
    $Paginator  = new Paginator( $conn, $query );
  
    $results    = $Paginator->getData( $limit, $page );
?>