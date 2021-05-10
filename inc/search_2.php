<?php
    global $conn;

    $search = filter_input(INPUT_POST, 'search');
    $statement = $conn->stmt_init();

    if (is_numeric($search))
    {
        $search = (int)$search;
        $query = "SELECT * FROM `inventory` WHERE `inventoryID` = ?";

        $statement->prepare($query);
        $statement->bind_param('i', $search);
        $statement->execute();
    }
    else
    {
        //$search = strtolower($search);
        $search = $search . "%";
        $query = "SELECT * FROM `inventory` WHERE `name` LIKE ?";

        $statement->prepare($query);
        $statement->bind_param('s', $search);
        $statement->execute();
    }
    $results = $statement->get_result();
?>