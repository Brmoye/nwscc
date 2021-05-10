<?php
    require_once('util/results.php');

    // All functions below connect via mysqli

    function get_categories() 
    {
        global $conn;

        $query = 'SELECT * FROM category
                ORDER BY categoryID';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        $statement->execute();
        return get_results($statement);
    }

    function get_category($category_id) 
    {
        global $conn;

        $query = 'SELECT * FROM category
                WHERE categoryID = ?';
        
        $statement = $conn->stmt_init();
        $statement->prepare($query);
        
        $statement->bind_param('i', $category_id);
        
        $statement->execute();
        return get_results($statement);
    }

    function get_category_by_inventory($inventory_id) 
    {
        global $conn;

        $query = 'SELECT categoryID FROM inventory
                WHERE inventoryID = ?';
        
        $statement = $conn->stmt_init();
        $statement->prepare($query);
        
        $statement->bind_param('i', $inventory_id);
        
        $statement->execute();
        return get_results($statement);
    }

    function get_category_name($category_id) 
    {
        global $conn;

        $query = 'SELECT category.name FROM category
                WHERE categoryID = ?';
        
        $statement = $conn->stmt_init();
        $statement->prepare($query);
        
        $statement->bind_param('i', $category_id);
        
        $statement->execute();
        return get_results($statement);
    }

    function add_category($name) 
    {
        global $conn;

        $query = 'INSERT INTO category (name)
                    VALUES (?)';
        
        $statement = $conn->stmt_init();
        $statement->prepare($query);
        
        $statement->bind_param('s', $name);
        
        $statement->execute();

        // Get the last category ID that was automatically generated
        $category_id = $conn->insert_id;
        return $category_id;
    }

    function update_category($category_id, $name) 
    {
        global $conn;

        $query = 'UPDATE category SET name = ?
            WHERE categoryID = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        
        $statement->bind_param('si', $name, $category_id);
        
        $statement->execute();
    }

    function delete_category($category_id) 
    {
        global $conn;

        $query = 'DELETE FROM category WHERE categoryID = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        
        $statement->bind_param('i', $category_id);
        
        $statement->execute();
    }

    /*
    // All functions below connect via mysql

    function get_categories() 
    {
        global $db;
        $query = 'SELECT * FROM category
                ORDER BY categoryID';
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
            display_db_error($e->getMessage());
        }
    }

    function get_category($category_id) 
    {
        global $db;
        $query = 'SELECT * FROM category
                WHERE categoryID = :category_id';
        try 
        {
            $statement = $db->prepare($query);
            $statement->bindValue(':category_id', $category_id);
            $statement->execute();
            $result = $statement->fetch();
            $statement->closeCursor();
            return $result;
        } 
        catch (PDOException $e) 
        {
            display_db_error($e->getMessage());
        }
    }

    function add_category($name) 
    {
        global $db;
        $query = 'INSERT INTO category
                    (name)
                VALUES
                    (:name)';
        try 
        {
            $statement = $db->prepare($query);
            $statement->bindValue(':name', $name);
            $statement->execute();
            $statement->closeCursor();

            // Get the last category ID that was automatically generated
            $category_id = $db->lastInsertId();
            return $category_id;
        } 
        catch (PDOException $e) 
        {
            $error_message = $e->getMessage();
            display_db_error($error_message);
        }
    }

    function update_category($category_id, $name) 
    {
        global $db;
        $query = '
            UPDATE category
            SET name = :name
            WHERE categoryID = :category_id';
        try 
        {
            $statement = $db->prepare($query);
            $statement->bindValue(':name', $name);
            $statement->bindValue(':category_id', $category_id);
            $statement->execute();
            $statement->closeCursor();
        } 
        catch (PDOException $e) 
        {
            $error_message = $e->getMessage();
            display_db_error($error_message);
        }
    }

    function delete_category($category_id) 
    {
        global $db;
        $query = 'DELETE FROM category WHERE categoryID = :category_id';
        try 
        {
            $statement = $db->prepare($query);
            $statement->bindValue(':category_id', $category_id);
            $statement->execute();
            $statement->closeCursor();
        } 
        catch (PDOException $e) 
        {
            $error_message = $e->getMessage();
            display_db_error($error_message);
        }
    }

    */

?>
