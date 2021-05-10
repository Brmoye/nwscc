<?php
    require_once('../../util/main.php');
    require_once('util/secure_conn.php');
    require_once('util/valid_admin.php');
    require_once('database/inventory_db.php');
    require_once('database/category_db.php');

    $limit = filter_input(INPUT_GET, 'limit', FILTER_VALIDATE_INT);
    $inventory_id = filter_input(INPUT_GET, 'inventory_id', FILTER_VALIDATE_INT);
    $category_id = filter_input(INPUT_GET, 'category_id', FILTER_VALIDATE_INT);

    $action = strtolower(filter_input(INPUT_POST, 'action'));
    $submit = strtolower(filter_input(INPUT_POST, 'submit'));
    

    if ($inventory_id !== NULL)
    {
        $action = 'view_inventory';
    }
    elseif ($limit !== null)
    {
        $action = 'list_inventory';
    }
    elseif ($action == NULL)
    {
        $action = strtolower(filter_input(INPUT_GET, 'action'));
        if ($action == NULL)
        {
            $action = 'list_inventory';
        }
    }
    if ($submit == 'delete')
    {
        $action = 'delete_inventory';
    }
    
    switch ($action) 
    {
        case 'list_inventory':
            // display inventory list
            include('inventory_list.php');
            break;

        case 'update_inventory':
            $inventory_id = filter_input(INPUT_POST, 'inventory_id',
                    FILTER_VALIDATE_INT);
            $category_id = filter_input(INPUT_POST, 'category_id',
                    FILTER_VALIDATE_INT);
            $name = filter_input(INPUT_POST, 'name');
            $itemCondition = filter_input(INPUT_POST, 'itemCondition');
            $description = filter_input(INPUT_POST, 'description');
            $location = filter_input(INPUT_POST, 'location');

            // Validate inputs
            if (empty($name) || empty($itemCondition) ||
                empty($location))
            {
                $error_message = 'Invalid inventory data.
                        Check all fields and try again.';
                include('../../errors/error.php');
            }
            else
            {
                update_inventory($inventory_id, $name, $itemCondition,
                    $location, $description, $category_id);
                header("Location: .?inventory_id=$inventory_id");
            }
            break;

        case 'view_inventory':
            $categories = get_categories();
            $inventory_id = filter_input(INPUT_GET, 'inventory_id',
                    FILTER_VALIDATE_INT);
            $inventory = get_inventory($inventory_id);
            include_once('inventory_add_edit.php');
            break;

        case 'add_inventory':
            $category_id = filter_input(INPUT_POST, 'category_id',
                    FILTER_VALIDATE_INT);
            $name = filter_input(INPUT_POST, 'name');
            $itemCondition = filter_input(INPUT_POST, 'itemCondition');
            $description = filter_input(INPUT_POST, 'description');
            $location = filter_input(INPUT_POST, 'location');

            // Validate inputs
            if (empty($category_id) || empty($name) || empty($itemCondition) ||
                empty($description) || empty($location))
            {
                $error_message = 'Invalid inventory data.
                        Check all fields and try again.';
                include('../../errors/error.php');
            } 
            else 
            {
                $categories = get_categories();
                $inventory_id = add_inventory($name, $itemCondition, $location, $description, $category_id);
                $inventory = get_inventory($inventory_id);
                include_once('inventory_add_edit.php');
            }
            break;

        case 'delete_inventory':
            $category_id = filter_input(INPUT_POST, 'category_id',
                    FILTER_VALIDATE_INT);
            $inventory_id = filter_input(INPUT_POST, 'inventory_id',
                    FILTER_VALIDATE_INT);
            delete_inventory($inventory_id);

            // Display the Inventory List page for the current category
            header("Location: .?category_id=$category_id");
            break;
    }
?>
