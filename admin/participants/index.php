<?php
    require_once('../../util/main.php');
    require_once('util/secure_conn.php');
    require_once('util/valid_admin.php');
    require_once('database/participants_db.php');

    $limit = filter_input(INPUT_GET, 'limit', FILTER_VALIDATE_INT);
    $participant_id = filter_input(INPUT_GET, 'participant_id', FILTER_VALIDATE_INT);

    $group_id = filter_input(INPUT_POST, 'group_id', FILTER_VALIDATE_INT);
    if ($group_id == NULL || $group_id === "") {
        $group_id = filter_input(INPUT_GET, 'group_id', FILTER_VALIDATE_INT);
    }
    $colorteam_id = filter_input(INPUT_POST, 'colorteam_id', FILTER_VALIDATE_INT);
    if ($colorteam_id == NULL || $colorteam_id === "") {
        $colorteam_id = filter_input(INPUT_GET, 'colorteam_id', FILTER_VALIDATE_INT);
    }
    if ($colorteam_id === "") {
        $colorteam_id = NULL;
    }

    $status = strtolower(filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT));
    $statuses = strtolower(filter_input(INPUT_POST, 'statuses', FILTER_VALIDATE_INT));
    $id0 = filter_input(INPUT_POST, 'id0', FILTER_VALIDATE_INT);
    $id1 = filter_input(INPUT_POST, 'id1', FILTER_VALIDATE_INT);
    $id2 = filter_input(INPUT_POST, 'id2', FILTER_VALIDATE_INT);
    $id3 = filter_input(INPUT_POST, 'id3', FILTER_VALIDATE_INT);
    $id4 = filter_input(INPUT_POST, 'id4', FILTER_VALIDATE_INT);
    $id5 = filter_input(INPUT_POST, 'id5', FILTER_VALIDATE_INT);
    $id6 = filter_input(INPUT_POST, 'id6', FILTER_VALIDATE_INT);
    $id7 = filter_input(INPUT_POST, 'id7', FILTER_VALIDATE_INT);
    $id8 = filter_input(INPUT_POST, 'id8', FILTER_VALIDATE_INT);
    $id9 = filter_input(INPUT_POST, 'id9', FILTER_VALIDATE_INT);
    $doEntireGroupStatus = strtolower(filter_input(INPUT_POST, 'selectAll'));

    $name = strtolower(filter_input(INPUT_POST, 'name'));
    $display_name = $name;
    $action = strtolower(filter_input(INPUT_POST, 'action'));
    $submit = strtolower(filter_input(INPUT_POST, 'submit'));
    $view_id = 0;
    $view_name = "";

    if ($participant_id !== NULL)
    {
        $action = 'view_participant';
        $view_id = $participant_id;
        $view_name = "participant_id";
    }
    elseif ($colorteam_id !== NULL)
    {
        $action = 'view_colorteam';
        $view_id = $colorteam_id;
        $view_name = "colorteam_id";
    }
    elseif ($group_id !== NULL)
    {
        $action = 'view_group';
        $view_id = $group_id;
        $view_name = "group_id";
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
        if ($doEntireGroupStatus != NULL) {
            if ($group_id != NULL) {
                update_group_status($group_id, $status);
            } else if ($colorteam_id != NULL) {
                update_colorteam_status($colorteam_id, $status);
            }
        } else {
            // suspect id0-9 as fields to check
            if ($id0 != NULL) {
                update_status($id0, $status);
            }
            if ($id1 != NULL) {
                update_status($id1, $status);
            }
            if ($id2 != NULL) {
                update_status($id2, $status);
            }
            if ($id3 != NULL) {
                update_status($id3, $status);
            }
            if ($id4 != NULL) {
                update_status($id4, $status);
            }
            if ($id5 != NULL) {
                update_status($id5, $status);
            }
            if ($id6 != NULL) {
                update_status($id6, $status);
            }
            if ($id7 != NULL) {
                update_status($id7, $status);
            }
            if ($id8 != NULL) {
                update_status($id8, $status);
            }
            if ($id9 != NULL) {
                update_status($id9, $status);
            }
        }
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

        case 'update_participant':
            $participant_id = filter_input(INPUT_POST, 'participant_id',
                    FILTER_VALIDATE_INT);
            $group = filter_input(INPUT_POST, 'group', FILTER_VALIDATE_INT);
            $age = filter_input(INPUT_POST, 'age', FILTER_VALIDATE_INT);
            $colorteam = filter_input(INPUT_POST, 'colorteam', FILTER_VALIDATE_INT);

            $firstname = filter_input(INPUT_POST, 'firstname');
            $lastname = filter_input(INPUT_POST, 'lastname');
            $adult = filter_input(INPUT_POST, 'adult');
            $phone = filter_input(INPUT_POST, 'phone');
            $email = filter_input(INPUT_POST, 'email');
            $assignment = filter_input(INPUT_POST, 'assignment');
            $gender = filter_input(INPUT_POST, 'gender');
            $grade = filter_input(INPUT_POST, 'grade');
            $shirtsize = filter_input(INPUT_POST, 'shirtsize');
            $special = filter_input(INPUT_POST, 'special');

            if ($special == "Allergies, dietary needs, physical needs, etc.") {
                $special = "N/A";
            }

            update_participant(
                $participant_id, $group, $firstname, $lastname,
                $adult, $phone, $email, $assignment, $gender,
                $grade, $age, $shirtsize, $special, $colorteam);
            update_status_absence($participant_id, $statuses);
            header("Location: .?participant_id=$participant_id");
            break;

        case 'add_participant':
            $group = filter_input(INPUT_POST, 'group', FILTER_VALIDATE_INT);
            $age = filter_input(INPUT_POST, 'age', FILTER_VALIDATE_INT);
            $colorteam = filter_input(INPUT_POST, 'colorteam', FILTER_VALIDATE_INT);

            $firstname = filter_input(INPUT_POST, 'firstname');
            $lastname = filter_input(INPUT_POST, 'lastname');
            $adult = filter_input(INPUT_POST, 'adult');
            $phone = filter_input(INPUT_POST, 'phone');
            $email = filter_input(INPUT_POST, 'email');
            $assignment = filter_input(INPUT_POST, 'assignment');
            $gender = filter_input(INPUT_POST, 'gender');
            $grade = filter_input(INPUT_POST, 'grade');
            $shirtsize = filter_input(INPUT_POST, 'shirtsize');
            $special = filter_input(INPUT_POST, 'special');

            if ($special == "Allergies, dietary needs, physical needs, etc.")
            {
                $special = "N/A";
            }

            add_participant(
                $group, $firstname, $lastname,
                $adult, $phone, $email, $assignment, $gender,
                $grade, $age, $shirtsize, $special, $colorteam);
            add_status($participant_id, $group, $colorteam, $statuses);
            header("Location: .?participant_id=$participant_id");
            break;

        case 'view_participant':
            $participant_id = filter_input(INPUT_GET, 'participant_id',
                    FILTER_VALIDATE_INT);
            $participants = get_participant($participant_id);
            include_once('participant_add_edit.php');
            break;
/*
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
