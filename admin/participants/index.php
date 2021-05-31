<?php
    require_once('../../util/main.php');
    require_once('util/secure_conn.php');
    require_once('util/valid_admin.php');
    //require_once('database/inventory_db.php');
    require_once('database/participants_db.php');

    $limit = filter_input(INPUT_GET, 'limit', FILTER_VALIDATE_INT);
    //$inventory_id = filter_input(INPUT_GET, 'inventory_id', FILTER_VALIDATE_INT);
    $participant_id = filter_input(INPUT_GET, 'participant_id', FILTER_VALIDATE_INT);

    $group_id = filter_input(INPUT_GET, 'group_id', FILTER_VALIDATE_INT);
    if ($group_id == NULL) {
        $group_id = filter_input(INPUT_POST, 'group_id', FILTER_VALIDATE_INT);
    }
    $colorteam_id = filter_input(INPUT_GET, 'colorteam_id', FILTER_VALIDATE_INT);
    if ($colorteam_id == NULL) {
        $colorteam_id = filter_input(INPUT_POST, 'colorteam_id', FILTER_VALIDATE_INT);
    }

    $status = strtolower(filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT));
    $id0 = filter_input(INPUT_POST, 'id0', FILTER_VALIDATE_INT);
    $id1 = filter_input(INPUT_POST, 'id1', FILTER_VALIDATE_INT);
    $id2 = filter_input(INPUT_POST, 'id2', FILTER_VALIDATE_INT);
    $id3 = filter_input(INPUT_POST, 'id3', FILTER_VALIDATE_INT);
    $id4 = filter_input(INPUT_POST, 'id4', FILTER_VALIDATE_INT);
    $id4 = filter_input(INPUT_POST, 'id5', FILTER_VALIDATE_INT);
    $id6 = filter_input(INPUT_POST, 'id6', FILTER_VALIDATE_INT);
    $id7 = filter_input(INPUT_POST, 'id7', FILTER_VALIDATE_INT);
    $id8 = filter_input(INPUT_POST, 'id8', FILTER_VALIDATE_INT);
    $id9 = filter_input(INPUT_POST, 'id9', FILTER_VALIDATE_INT);

    $name = strtolower(filter_input(INPUT_POST, 'name'));
    $action = strtolower(filter_input(INPUT_POST, 'action'));
    $submit = strtolower(filter_input(INPUT_POST, 'submit'));

    if ($participant_id !== NULL)
    {
        $action = 'view_participant';
    }
    elseif ($colorteam_id !== NULL)
    {
        $action = 'view_colorteam';
    }
    elseif ($group_id !== NULL)
    {
        $action = 'view_group';
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

    if ($status != NULL) {
        // Update status on selected id's then display
        // suspect id0-9 as fields to check
        update_status($id0, $status);
        update_status($id1, $status);
        update_status($id2, $status);
        update_status($id3, $status);
        update_status($id4, $status);
        update_status($id5, $status);
        update_status($id6, $status);
        update_status($id7, $status);
        update_status($id8, $status);
        update_status($id9, $status);
    }
    switch ($action) 
    {
        case 'list_participants':
            // display inventory list
            include('participants_list.php');
            break;
        case 'view_colorteam':
            // display list by colorteam
            $results = get_participants_by_colorteam($colorteam_id);
            include('participants_list.php');
            break;
        case 'view_group':
            // display list by group
            $results = get_participants_by_group($group_id);
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
            $participants = get_participant($participant_id);
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
