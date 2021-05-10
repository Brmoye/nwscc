<?php
    require_once('../../util/main.php');
    require_once('util/secure_conn.php');
    require_once('util/valid_admin.php');
    require_once('../../database/database.php');
    require_once('../../database/category_db.php');
    require_once('../../database/inventory_db.php');

    $action = strtolower(filter_input(INPUT_POST, 'action'));
    if ($action == NULL) {
        $action = strtolower(filter_input(INPUT_GET, 'action'));
        if ($action == NULL) {
            $action = 'list_categories';
        }
    }

    switch ($action) {
        case 'list_categories':
            $categories = get_categories();
            include('category_list.php');
            break;

        case 'delete_category':
            $category_id = filter_input(INPUT_POST, 'category_id',
                    FILTER_VALIDATE_INT);
            
            if (get_inventory_count($category_id) == 0)
            {
                delete_category($category_id);
                header("Location: .?action=list_categories");
            }
            else
            {
                $error_message = 'Category has inventory.';
                include('errors/error.php');
            }
            break;

        case 'add_category':
            $name = filter_input(INPUT_POST, 'name');

            // Validate inputs
            if (empty($name))
            {
                $error_message = 'You must include a name for the category.
                               Please try again.';
                include('errors/error.php');
            }
            else
            {
                $category_id = add_category($name);
                header("Location: .");
            }
            break;

        case 'update_category':
            $category_id = filter_input(INPUT_POST, 'category_id',
                    FILTER_VALIDATE_INT);
            $name = filter_input(INPUT_POST, 'name');

            // Validate inputs
            if (empty($name))
            {
                $error_message = 'You must include a name for the category.
                              Please try again.';
                include('errors/error.php');
            }
            else
            {
                update_category($category_id, $name);

            header("Location: .");
            }
            break;
    }

?>
