<?php
    require_once('../util/main.php');
    require_once('../database/inventory_db.php');
    require_once('../database/category_db.php');

    //global $category_id;

    $limit = filter_input(INPUT_GET, 'limit', FILTER_VALIDATE_INT);
    
    $category_id = filter_input(INPUT_GET, 'category_id', FILTER_VALIDATE_INT);
    
    $inventory_id = filter_input(INPUT_GET, 'inventory_id', FILTER_VALIDATE_INT);
    $action = filter_input(INPUT_GET, 'action');

    if ($category_id !== null) 
    {
        $action = 'view_category';
    } 
    elseif ($inventory_id !== null) 
    {
        $action = 'inventory';
    }
    elseif ($limit !== null)
    {
        $action = 'view_category';
    }

    switch ($action) 
    {
        // Display the specified category
        case 'view_category':
            // Get category data
            
            $category = get_category($category_id);
            $category_name = $category->data[0]['name'];
            $invByCat = get_inventory_by_category($category_id);

            // Display category
            include('./category_view.php');
            break;

        // Display the specified inventory
        case 'inventory':
            // Get inventory data
            $inventory = get_inventory($inventory_id);

            // Display inventory
            include('./inventory_view.php');
            break;
        // Display list of categories
        case 'list_categories';
            $categories = get_categories();
            include('./category_list.php');
            break;

        default:
            $error_message = 'Unknown catalog action: ' . $action;
            include('errors/error.php');
            break;
    }
?>