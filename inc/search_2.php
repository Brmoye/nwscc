<?php
    global $conn;

    $search = filter_input(INPUT_POST, 'search');
    $statement = $conn->stmt_init();

    //$search = strtolower($search);
    $search = $search . "%";
    $query = "SELECT * FROM `participants` WHERE (`lastname` LIKE ? OR `firstname` LIKE ?)";

    $statement->prepare($query);
    $statement->bind_param('ss', $search, $search);
    $statement->execute();
    $results = $statement->get_result();
?>