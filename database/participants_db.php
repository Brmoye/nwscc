<?php
    require_once('util/results.php');
    // All functions below connect via mysqli

    function get_all_participants()
    {
        global $conn;

        $query = 'SELECT lastname,firstname,group,colorteam,assignment,special,adult,gender
                    FROM participants 
                    ORDER BY group,lastname, id';
        
        $statement = $conn->stmt_init();
        $statement->prepare($query);
        $statement->execute();
        return get_results($statement);
    }

    function get_participants_by_colorteam($colorteam_id)
    {
        global $conn;

        $query = 'SELECT lastname,group,adult,assignment,gender,special,colorteam
                    FROM participants 
                    WHERE colorteam = ?
                    ORDER BY lastname, id';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        
        $statement->bind_param('i', $colorteam_id);
        
        $statement->execute();
        return get_results($statement);
    }

    function get_participants_by_group($group_id)
    {
        global $conn;

        $query = 'SELECT lastname,group,adult,assignment,gender,special,colorteam
                    FROM participants 
                    WHERE group = ?
                    ORDER BY lastname, id';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        
        $statement->bind_param('i', $group_id);
        
        $statement->execute();
        return get_results($statement);
    }

    // Get a specific unit of participants by its id
    function get_participant($participant_id)
    {
        global $conn;

        $query = 'SELECT * FROM participants WHERE id = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        
        $statement->bind_param('i', $participant_id);
        
        $statement->execute();
        return get_results($statement);
    }

    function get_participants_count_group($group_id)
    {
        global $conn;

        $query = 'SELECT COUNT(*) AS participantsCount
                    FROM participants
                    WHERE group = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        
        $statement->bind_param('i', $group_id);
        
        $statement->execute();
        $result = get_results($statement);
        return $result->data[0]['participantsCount'];
    }

    function get_participants_count_colorteam($colorteam_id)
    {
        global $conn;

        $query = 'SELECT COUNT(*) AS participantsCount
                    FROM participants
                    WHERE colorteam = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        
        $statement->bind_param('i', $colorteam_id);
        
        $statement->execute();
        $result = get_results($statement);
        return $result->data[0]['participantsCount'];
    }

    function update_participant($participant_id, $phone, $email, $assignment, $special, $colorteam)
    {
        global $conn;

        $query = 'UPDATE participants SET phone = ?,
                    email = ?, special = ?,
                    assignment = ?, colorteam = ?
                    WHERE id = ?';
        
        $statement = $conn->stmt_init();
        $statement->prepare($query);
        
        $statement->bind_param('ssssii', $phone, $email, $special, 
                        $assignment, $colorteam, $participant_id);
        
        $statement->execute();
        $statement->close();
    }

    function add_participant($group, $firstname, $lastname, $adult, $phone, $email, $assignment, $gender, $grade, $age, $shirtsize, $special, $colorteam) {
        global $conn;

        $query = 'INSERT INTO participants
                    (group, firstname, lastname, adult,
                    phone, email, assignment, gender,
                    grade, age, shirtsize, special, colorteam)
                VALUES
                    (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        
        $statement->bind_param('ssssi', $group, $firstname, $lastname, $adult,
        $phone, $email, $assignment, $gender,
        $grade, $age, $shirtsize, $special, $colorteam);
        
        $statement->execute();
        $statement->close();

        // Get the last participants ID that was automatically generated
        $participants_id = $conn->insert_id;
        return $participants_id;
    }

    function delete_participant($participant_id) {
        global $conn;

        $query = 'DELETE FROM participants WHERE id = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        
        $statement->bind_param('i', $participant_id);
        
        $statement->execute();
        $statement->close();
    }

    // Get a specific name of colorteam by its id
    function get_colorteam($colorteam_id)
    {
        global $conn;

        $query = 'SELECT colorgroup FROM colorgroups WHERE id = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        
        $statement->bind_param('i', $group_id);
        
        $statement->execute();
        //return get_results($statement);
        $result = get_results($statement);
        return $result->data[0]['colorgroup'];
    }

    // Get a specific name of a group by its id
    function get_group($group_id)
    {
        global $conn;

        $query = 'SELECT groupname FROM register WHERE id = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        
        $statement->bind_param('i', $group_id);
        
        $statement->execute();
        //return get_results($statement);
        $result = get_results($statement);
        return $result->data[0]['groupname'];
    }

    // Get a specific status of a participant by statusID
    function get_statusID($participant_id)
    {
        global $conn;

        $query = 'SELECT statusID FROM participant_status_map WHERE participantID = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        
        $statement->bind_param('i', $participant_id);
        
        $statement->execute();
        //return get_results($statement);
        $result = get_results($statement);
        if (empty($result->data))
        {
            return 0;
        }
        return $result->data[0]['statusID'];
    }

    // Get a specific status of a participant by statusID
    function get_status($status_id)
    {
        global $conn;

        $query = 'SELECT status FROM statuses WHERE id = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        
        $statement->bind_param('i', $status_id);
        
        $statement->execute();
        //return get_results($statement);
        $result = get_results($statement);
        return $result->data[0]['status'];
    }

    // Set a specific status of a participant by statusID
    function add_status($participant_id, $group_id, $colorteam_id)
    {
        global $conn;

        $default_status = 1;
        $query = 'INSERT INTO participant_status_map
                    (participantID, groupID, colorteamID, statusID)
                VALUES
                    (?, ?, ?, ?)';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        
        $statement->bind_param('iiii', $participant_id, $group_id, $colorteam_id, $default_status);
        
        $statement->execute();
        $statement->close();

        return $default_status;
    }

    // Get a specific status of a participant by statusID
    function get_add_status($participant_id, $group_id, $colorteam_id)
    {
        $status_id = get_statusID($participant_id);

        if ($status_id == 0) {
            $status_id = add_status(
                $participant_id, $group_id, $colorteam_id);
        }
        return get_status($status_id);
    }

    // Update status of a participant
    function update_status($participant_id, $status_id)
    {
        global $conn;

        $query = 'UPDATE participant_status_map SET statusID = ?
                    WHERE participantID = ?';
        
        $statement = $conn->stmt_init();
        $statement->prepare($query);
        
        $statement->bind_param('ii', $status_id, $participant_id);
        
        $statement->execute();
        $statement->close();
    }

?>
