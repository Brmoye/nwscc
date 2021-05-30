<?php
    require_once('../util/main.php');
    require_once('../database/participants_db.php');
    //require_once('../database/category_db.php');

    //global $category_id;

    $limit = filter_input(INPUT_GET, 'limit', FILTER_VALIDATE_INT);
    
    $group_id = filter_input(INPUT_GET, 'group_id', FILTER_VALIDATE_INT);
    
    $colorteam_id = filter_input(INPUT_GET, 'colorteam_id', FILTER_VALIDATE_INT);
    $participant_id = filter_input(INPUT_GET, 'participant_id', FILTER_VALIDATE_INT);
    $action = filter_input(INPUT_GET, 'action');

    if ($group_id !== null) 
    {
        $action = 'view_group';
    } 
    elseif ($participant_id !== null) 
    {
        $action = 'view_participant';
    }
    elseif ($colorteam_id !== null) 
    {
        $action = 'view_colorteam';
    }
/*    elseif ($limit !== null)
    {
        $action = 'view_colorteam';
    }
*/
    switch ($action) 
    {
/*
        // Display the specified category
        case 'view_category':
            // Get category data
            
            $category = get_category($category_id);
            $category_name = $category->data[0]['name'];
            $invByCat = get_inventory_by_category($category_id);

            // Display category
            include('./category_view.php');
            break;
*/
        case 'view_participant':
            // Get inventory data
            $participants = get_participant($participant_id);

            // Display Participant
            include('./participant_view.php');
            break;
/*
        // Display list of groups
        case 'list_groups';
            $categories = get_categories();
            include('./category_list.php');
            break;
        case 'list_colorteams';
            $categories = get_categories();
            include('./category_list.php');
            break;
*/
        default:
            $error_message = 'Unknown catalog action: ' . $action;
            include('errors/error.php');
            break;
    }
?>