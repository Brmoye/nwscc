<?php
    require_once('util/results.php');

    // All functions below connect via mysqli
    function is_valid_admin_login($username, $password) 
    {
        global $conn;
        
        //$password = sha1($email . $password);
        $password = md5($password);

        $query = 'SELECT * FROM `membership_users`
                WHERE `memberID` = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        $statement->bind_param('s', $username);
        $statement->execute();
        $result = mysqli_stmt_get_result($statement);

        $result1 = $result->fetch_assoc();
        $hash = $result1['passMD5'];
        $passValid = password_verify($password, $hash);
        echo $passValid."\n<br/>\n";
        $valid = ($result->num_rows == 1);
        $statement->close();
        return $valid;
    }

    function admin_count() 
    {
        global $conn;

        $query = 'SELECT count(*) AS `adminCount` FROM `membership_users`';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        $statement->execute();
        $result = mysqli_stmt_get_result($statement)->fetch_assoc();
        $statement->close();
        return $result['adminCount'];
    }

    function get_all_admins() 
    {
        global $conn;

        $query = 'SELECT * FROM `membership_users` ORDER BY `groupID`, `isApproved`';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        $statement->execute();
        $admins = $statement->get_result();
        $statement->close();
        return $admins;
    }

    function get_admin($admin_id) 
    {
        global $conn;

        $query = 'SELECT * FROM `membership_users` WHERE `memberID` = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        $statement->bind_param('i', $admin_id);
        $statement->execute();
        $admin = $statement->get_result()->fetch_assoc();
        $statement->close();
        return $admin;
    }

    function get_admin_by_email($email) 
    {
        global $conn;

        $query = 'SELECT * FROM `membership_users` WHERE `memberID` = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        $statement->bind_param('s', $email);
        $statement->execute();
        $admin = $statement->get_result()->fetch_assoc();
        $statement->close();
        return $admin;
    }

    function is_valid_admin_email($email) 
    {
        global $conn;

        $query = 'SELECT * FROM `membership_users`
            WHERE `memberID` = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        $statement->bind_param('s', $email);
        $statement->execute();
        $result1 = $result->fetch_assoc();
        $group = $result1['groupID'];

        $valid = ($statement->get_result()->num_rows == 1 && $group == 1);

        echo "\n<br/>\n Admin:".$email." group:".$group." valid:".$valid."\n<br/>\n";
        $statement->close();
        return $valid;
    }

    function add_admin($email, $member_id, $group_id, $password_1) 
    {
        global $conn;

        //$password = sha1($email . $password_1);
        $password = md5($password_1);

        $query = 'INSERT INTO `membership_users` (`memberID`, `passMD5`, `email`, `groupID`)
            VALUES (?, ?, ?, ?)';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        $statement->bind_param('ssss', $email, $password, $member_id, $group_id);
        $statement->execute();
        $admin_id = $conn->insert_id;
        $statement->close();
        return $admin_id;
    }

    function update_admin($admin_id, $email, $first_name, $last_name,
                        $password_1, $password_2) 
    {
        global $conn;

        $query = 'UPDATE `membership_users` SET `memberID` = ?,
            `firstName` = ?, `lastName` = ? WHERE `memberID` = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        $statement->bind_param('sssi', $email, $first_name, $last_name, $admin_id);
        $statement->execute();
        $statement->close();

        if (!empty($password_1) && !empty ($password_2)) 
        {
            if ($password_1 !== $password_2) 
            {
                display_error('Passwords do not match.');
            } 
            elseif (strlen($password_1) < 6) 
            {
                display_error('Password must be at least six characters.');
            }
            $password = sha1($email . $password_1);
            $query = 'UPDATE `administrators` SET `password` = ?
                WHERE `memberID` = ?';

            $statement = $conn->stmt_init();
            $statement->prepare($query);
            $statement->bind_param('si', $password, $admin_id);
            $statement->execute();
            $statement->close();
        }
    }

    function delete_admin($admin_id) 
    {
        global $conn;

        $query = 'DELETE FROM `membership_users` WHERE `memberID` = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        $statement->bind_param('i', $admin_id);
        $statement->execute();
        $statement->close();
    }

?>