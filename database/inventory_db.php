<?php
    require_once('util/results.php');
    // All functions below connect via mysqli

    function get_all_inventory()
    {
        global $conn;

        $query = 'SELECT category.name AS c_name, inventory.* 
                    FROM inventory 
                    INNER JOIN category 
                    ON inventory.categoryID = category.categoryID 
                    ORDER BY inventory.categoryID, inventoryID';
        
        $statement = $conn->stmt_init();
        $statement->prepare($query);
        $statement->execute();
        return get_results($statement);
    }

    function get_inventory_by_category($category_id)
    {
        global $conn;

        $query = 'SELECT category.name AS c_name, inventory.*
                    FROM inventory
                    INNER JOIN category
                    ON inventory.categoryID = category.categoryID
                    WHERE inventory.categoryID = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        
        $statement->bind_param('i', $category_id);
        
        $statement->execute();
        return get_results($statement);
    }

    // Get a specific unit of inventory by its id
    function get_inventory($inventory_id)
    {
        global $conn;

        $query = 'SELECT * FROM inventory WHERE inventoryID = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        
        $statement->bind_param('i', $inventory_id);
        
        $statement->execute();
        return get_results($statement);
    }

    function get_inventory_count($category_id)
    {
        global $conn;

        $query = 'SELECT COUNT(*) AS inventoryCount
                    FROM inventory
                    WHERE categoryID = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        
        $statement->bind_param('i', $category_id);
        
        $statement->execute();
        $result = get_results($statement);
        return $result->data[0]['inventoryCount'];
    }

    function update_inventory($inventory_id, $name, $itemCondition, $location, $description, $category_id)
    {
        global $conn;

        $query = 'UPDATE inventory SET name = ?,
                    itemCondition = ?, description = ?,
                    location = ?, categoryID = ?
                    WHERE inventoryID = ?';
        
        $statement = $conn->stmt_init();
        $statement->prepare($query);
        
        $statement->bind_param('ssssii', $name, $itemCondition, $description, 
                        $location, $category_id, $inventory_id);
        
        $statement->execute();
        $statement->close();
    }

    function add_inventory($name, $itemCondition, $location, $description, $category_id) {
        global $conn;

        $query = 'INSERT INTO inventory
                    (name, description, itemCondition, location, categoryID)
                VALUES
                    (?, ?, ?, ?, ?)';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        
        $statement->bind_param('ssssi', $name, $description, $itemCondition, $location, $category_id);
        
        $statement->execute();
        $statement->close();

        // Get the last inventory ID that was automatically generated
        $inventory_id = $conn->insert_id;
        return $inventory_id;
    }

    function delete_inventory($inventory_id) {
        global $conn;

        $query = 'DELETE FROM inventory WHERE inventoryID = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        
        $statement->bind_param('i', $inventory_id);
        
        $statement->execute();
        $statement->close();
    }

    /*
    // All functions below connect via mysql

    function get_all_inventory()
    {
        global $db;
        $query = 'SELECT category.name AS c_name, inventory.* 
                    FROM inventory 
                    INNER JOIN category 
                    ON inventory.categoryID = category.categoryID 
                    ORDER BY inventory.categoryID, inventoryID';
        try
        {
            $statement = $db->prepare($query);
            $statement->execute();
            $result = $statement->fetchAll();
            $statement->closeCursor();
            return $result;
        }
        catch (PDOException $e)
        {
            $error_message = $e->getMessage();
            display_db_error($error_message);
        }
    }

    function get_inventory_by_category($category_id)
    {
        global $db;
        $query = 'SELECT category.name AS c_name, inventory.*
            FROM inventory
            INNER JOIN category
            ON inventory.categoryID = category.categoryID
            WHERE inventory.categoryID = :category_id';
        try
        {
            $statement = $db->prepare($query);
            $statement->bindValue(':category_id', $category_id);
            $statement->execute();
            $result = $statement->fetchAll();
            $statement->closeCursor();
            return $result;
        }
        catch (PDOException $e)
        {
            $error_message = $e->getMessage();
            display_db_error($error_message);
        }
    }

    // Get a specific unit of inventory by its id
    function get_inventory($inventory_id)
    {
        global $db;
        $query = 'SELECT *
                FROM inventory
                WHERE inventoryID = :inventory_id';
        try
        {
            $statement = $db->prepare($query);
            $statement->bindValue(':inventory_id', $inventory_id);
            $statement->execute();
            $result = $statement->fetch();
            $statement->closeCursor();
            return $result;
        }
        catch (PDOException $e)
        {
            $error_message = $e->getMessage();
            display_db_error($error_message);
        }
    }

    function get_inventory_count($category_id)
    {
        global $db;

        $query = 'SELECT COUNT(*) AS inventoryCount
                    FROM inventory
                    WHERE categoryID = :category_id';

        $statement = $db->prepare($query);
        $statement->bindValue(':category_id', $category_id);
        $statement->execute();
        $result = $statement->fetchAll();
        $statement->closeCursor();

        $inventory_count = $result[0]['inventoryCount'];
        return $inventory_count;
    }

    function update_inventory($inventory_id, $name, $itemCondition, $description, $location, $category_id)
    {
        global $db;
        $query = 'UPDATE inventory
                    SET name = :name,
                    itemCondition = :itemCondition,
                    description = :description,
                    location = :location,
                    categoryID = :category_id
                    WHERE inventoryID = :inventory_id';
        try
        {
            $statement = $db->prepare($query);
            $statement->bindValue(':name', $name);
            $statement->bindValue(':itemCondition', $itemCondition);
            $statement->bindValue(':description', $description);
            $statement->bindValue(':location', $location);
            $statement->bindValue(':category_id', $category_id);
            $statement->bindValue(':inventory_id', $inventory_id);
            $statement->execute();
            $statement->closeCursor();
        }
        catch (PDOException $e)
        {
            $error_message = $e->getMessage();
            display_db_error($error_message);
        }
    }

    function add_inventory($name, $itemCondition, $description, $location, $category_id) {
        global $db;
        $query = 'INSERT INTO inventory
                    (name, description, itemCondition, location, categoryID)
                VALUES
                    (:name, :description, :itemCondition, :location, :category_id)';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':name', $name);
            $statement->bindValue(':description', $description);
            $statement->bindValue(':itemCondition', $itemCondition);
            $statement->bindValue(':location', $location);
            $statement->bindValue(':category_id', $category_id);
            $statement->execute();
            $statement->closeCursor();

            // Get the last inventory ID that was automatically generated
            $inventory_id = $db->lastInsertId();
            return $inventory_id;
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            display_db_error($error_message);
        }
    }

    function delete_inventory($inventory_id) {
        global $db;
        $query = 'DELETE FROM inventory WHERE inventoryID = :inventory_id';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':inventory_id', $inventory_id);
            $statement->execute();
            $statement->closeCursor();
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            display_db_error($error_message);
        }
    }

    */

?>
