<?php
    require_once('../../util/main.php');
    require_once('util/secure_conn.php');
    require_once('util/valid_admin.php');
    //require_once('database/inventory_db.php');
    require_once('database/participants_db.php');

    $limit = filter_input(INPUT_GET, 'limit', FILTER_VALIDATE_INT);
    //$inventory_id = filter_input(INPUT_GET, 'inventory_id', FILTER_VALIDATE_INT);
    $participant_id = filter_input(INPUT_GET, 'participant_id', FILTER_VALIDATE_INT);

    $action = strtolower(filter_input(INPUT_POST, 'action'));
    $submit = strtolower(filter_input(INPUT_POST, 'submit'));
    

    if ($participant_id !== NULL)
    {
        $action = 'view_participant';
    }
    elseif ($limit !== null)
    {
        $action = 'list_participants';
    }
    elseif ($action == NULL)
    {
        $action = strtolower(filter_input(INPUT_GET, 'action'));
        if ($action == NULL)
        {
            $action = 'list_participants';
        }
    }
    /*if ($submit == 'delete')
    {
        $action = 'delete_participant';
    }*/
    
    switch ($action) 
    {
        case 'list_participants':
            // display inventory list
            include('participants_list.php');
            break;
/*
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
*/
        case 'view_participant':
            //$categories = get_categories();
            $participant_id = filter_input(INPUT_GET, 'participant_id',
                    FILTER_VALIDATE_INT);
            $participant = get_particpant($participant_id);
            include_once('participant_add_edit.php');
            break;
/*
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
*/
    }
?>
