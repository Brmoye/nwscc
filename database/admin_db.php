<?php
    require_once('util/results.php');

    // All functions below connect via mysqli
    function is_valid_admin_login($username, $password)
    {
        global $conn;

        $query = 'SELECT * FROM `membership_users`
                WHERE `memberID` = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        $statement->bind_param('s', $username);
        $statement->execute();
        $result = mysqli_stmt_get_result($statement);

        $result1 = $result->fetch_assoc();
        $group = $result1['groupID'];
        $passValid = password_verify($password, $result1['passMD5']);

        $valid = ($result->num_rows == 1
            && $passValid
            && ($group == 2 || $group == 4)
        );
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

        $query = 'SELECT *
            FROM membership_users AS a
            ORDER BY a.groupID,a.memberID';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        $statement->execute();
        $admins = $statement->get_result();
        $statement->close();
        return $admins;
    }

    function get_admin_by_email($email)
    {
        global $conn;

        $query = 'SELECT * FROM `membership_users` WHERE `email` = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        $statement->bind_param('s', $email);
        $statement->execute();
        $admin = $statement->get_result()->fetch_assoc();
        $statement->close();
        return $admin;
    }

    function get_admin_by_username($username)
    {
        global $conn;

        $query = 'SELECT * FROM `membership_users` WHERE `memberID` = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        $statement->bind_param('s', $username);
        $statement->execute();
        //return get_results($statement);
        $admin = $statement->get_result()->fetch_assoc();
        $statement->close();
        return $admin;
    }

    function is_valid_admin_email($email)
    {
        if ($email == NULL) {
            return false;
        }
        global $conn;

        $query = 'SELECT * FROM `membership_users`
            WHERE `email` = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        $statement->bind_param('s', $email);
        $statement->execute();
        $result = mysqli_stmt_get_result($statement);
        $result1 = $result->fetch_assoc();
        $group = $result1['groupID'];

        $valid = ($result->num_rows == 1);

        $statement->close();
        return $valid;
    }

    function is_valid_admin_username($username)
    {
        global $conn;

        $query = 'SELECT * FROM `membership_users`
            WHERE `memberID` = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        $statement->bind_param('s', $username);
        $statement->execute();

        $valid = ($statement->get_result()->num_rows == 1);

        $statement->close();
        return $valid;
    }

    function is_valid_super_admin_username($username)
    {
        global $conn;

        $query = 'SELECT * FROM `membership_users`
            WHERE `memberID` = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        $statement->bind_param('s', $username);
        $statement->execute();
        $result = mysqli_stmt_get_result($statement);
        $result1 = $result->fetch_assoc();
        $group = $result1['groupID'];

        $valid = ($result->num_rows == 1 && $group == 2);
        $statement->close();
        return $valid;
    }

    // Get a specific status of a participant by statusID
    function get_admin_group($username)
    {
        if ($username == NULL) {
            return "anonymous";
        }
        $admin = get_admin_by_username($username);
        global $conn;

        $query = 'SELECT g.name FROM membership_groups AS g WHERE groupID = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);

        $statement->bind_param('i', $admin['groupID']);

        $statement->execute();
        //return get_results($statement);
        $result = get_results($statement);
        return $result->data[0]['name'];
    }

    function get_admin_groups()
    {
        global $conn;

        $query = 'SELECT g.groupID,g.name FROM membership_groups AS g';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        $statement->execute();
        return get_results($statement);
    }

    function add_admin($username, $password_1, $email, $group_id, $fullName, $address, $city, $state, $comments = "", $isBanned = 0, $isApproved = 1, $reset_key="", $reset_expiry=0, $csv_import = 0, $flags="", $signup_date = "")
    {
        global $conn;

        //$password = sha1($email . $password_1);
        $password = password_hash($password_1, PASSWORD_DEFAULT );
        if ($signup_date == "") {
            $signup_date = date("Y-m-d");
        }

        $query = 'INSERT INTO `membership_users`
                (`memberID`, `passMD5`, `email`, `groupID`,
                signupDate, isBanned, isApproved, custom1,
                custom2, custom3, custom4, allowCSVImport, comments)
            VALUES (?, ?, ?, ?, ?, ?, ?,
                    ?, ?, ?, ?, ?, ?)';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        $statement->bind_param('sssisiissssis',
            $username, $password, $email, $group_id, $signup_date,
            $isBanned, $isApproved, $fullName, $address, $city,
            $state, $csv_import, $comments);
        $statement->execute();
        $statement->close();

        if ($flags != "") {
            $query = 'UPDATE membership_users SET flags = ? WHERE memberID = ?';
            $statement = $conn->stmt_init();
            $statement->prepare($query);
            $statement->bind_param('ss', $username, $flags);
            $statement->execute();
            $admin_id = $conn->insert_id;
            $statement->close();
        }
        if ($reset_key != "") {
            $query = 'UPDATE membership_users SET pass_reset_key = ? WHERE memberID = ?';
            $statement = $conn->stmt_init();
            $statement->prepare($query);
            $statement->bind_param('ss', $username, $reset_key);
            $statement->execute();
            $admin_id = $conn->insert_id;
            $statement->close();
        }
        if ($reset_expiry != 0) {
            $query = 'UPDATE membership_users SET pass_reset_expiry = ? WHERE memberID = ?';
            $statement = $conn->stmt_init();
            $statement->prepare($query);
            $statement->bind_param('si', $username, $reset_expiry);
            $statement->execute();
            $admin_id = $conn->insert_id;
            $statement->close();
        }
        return $username;
    }

    function update_admin($username_id, $username, $password_1, $password_2, $email, $group_id, $fullName, $address, $city, $state, $comments = "", $isBanned = 0, $isApproved = 1, $csv_import = 0, $reset_key = "", $reset_expiry = 0, $flags = "")
    {
        global $conn;

        $query = 'UPDATE `membership_users` SET
                `memberID` = ?, `email` = ?, `groupID` = ?,
                isBanned = ?, isApproved = ?, custom1 = ?,
                custom2 = ?, custom3 = ?, custom4 = ?,
                allowCSVImport = ?, comments = ?
            WHERE `memberID` = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        $statement->bind_param('ssiiissssiss',
            $username, $email, $group_id, $isBanned,
            $isApproved, $fullName, $address, $city, $state,
            $csv_import, $comments, $username_id);
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
            $query = 'UPDATE `membership_users` SET `passMD5` = ?
                WHERE `memberID` = ?';

            $password = password_hash($password_1, PASSWORD_DEFAULT );
            $statement = $conn->stmt_init();
            $statement->prepare($query);
            $statement->bind_param('si', $password, $username);
            $statement->execute();
            $statement->close();
        }
        if ($flags != "") {
            $query = 'UPDATE membership_users SET flags = ? WHERE memberID = ?';
            $statement = $conn->stmt_init();
            $statement->prepare($query);
            $statement->bind_param('ss', $username, $flags);
            $statement->execute();
            $admin_id = $conn->insert_id;
            $statement->close();
        }
        if ($reset_key != "") {
            $query = 'UPDATE membership_users SET pass_reset_key = ? WHERE memberID = ?';
            $statement = $conn->stmt_init();
            $statement->prepare($query);
            $statement->bind_param('ss', $username, $reset_key);
            $statement->execute();
            $admin_id = $conn->insert_id;
            $statement->close();
        }
        if ($reset_expiry != 0) {
            $query = 'UPDATE membership_users SET pass_reset_expiry = ? WHERE memberID = ?';
            $statement = $conn->stmt_init();
            $statement->prepare($query);
            $statement->bind_param('si', $username, $reset_expiry);
            $statement->execute();
            $admin_id = $conn->insert_id;
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