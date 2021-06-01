<?php
    require_once('util/results.php');
    // All functions below connect via mysqli

    function get_all_participants()
    {
        global $conn;

        $query = 'SELECT p.id,p.lastname,p.firstname,p.group,p.colorteam,p.special,m.statusID
                    FROM participants AS p
                    LEFT JOIN participant_status_map AS m ON
                        p.id = m.participantID
                    ORDER BY p.group,p.lastname,p.id';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        $statement->execute();
        return get_results($statement);
    }

    function get_participants_by_colorteam($colorteam_id)
    {
        global $conn;

        $query = 'SELECT p.id,p.lastname,p.firstname,p.group,p.colorteam,p.special,m.statusID
                    FROM participants AS p
                    LEFT JOIN participant_status_map AS m ON
                        p.id = m.participantID
                    WHERE p.colorteam = ?
                    ORDER BY p.lastname,p.id';

        $statement = $conn->stmt_init();
        $statement->prepare($query);

        $statement->bind_param('i', $colorteam_id);

        $statement->execute();
        return get_results($statement);
    }

    function get_participants_by_group($group_id)
    {
        global $conn;

        $query = 'SELECT p.id,p.lastname,p.firstname,p.group,p.colorteam,p.special,m.statusID
                    FROM participants AS p
                    LEFT JOIN participant_status_map AS m ON
                        p.id = m.participantID
                    WHERE p.group = ?
                    ORDER BY p.lastname,p.id';

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

    function update_participant($participant_id, $group, $firstname, $lastname, $adult, $phone, $email, $assignment, $gender, $grade, $age, $shirtsize, $special, $colorteam)
    {
        global $conn;

        /*$query = 'UPDATE participants SET phone = ?,
                    email = ?, special = ?,
                    assignment = ?, colorteam = ?
                    WHERE id = ?';*/
        $query = 'UPDATE participants AS p SET
                p.firstname = ?, p.lastname = ?, p.adult = ?, p.phone = ?,
                p.email = ?, p.assignment = ?, p.gender = ?, p.grade = ?,
                p.shirtsize = ?, p.special = ?, p.group = ?, p.age = ?,
                p.colorteam = ?
            WHERE p.id = ?';


        $statement = $conn->stmt_init();
        $statement->prepare($query);

        $statement->bind_param('ssssssssssiiii',
            $firstname, $lastname, $adult, $phone,
            $email, $assignment, $gender, $grade,
            $shirtsize, $special, $group, $age,
            $colorteam,$participant_id);

        $statement->execute();
        $statement->close();
    }

    function add_participant($group, $firstname, $lastname, $adult, $phone, $email, $assignment, $gender, $grade, $age, $shirtsize, $special, $colorteam) {
        global $conn;

        $query = 'INSERT INTO participants
                (firstname, lastname, adult, phone,
                email, assignment, gender, grade,
                shirtsize, special, group, age, colorteam)
            VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

        $statement = $conn->stmt_init();
        $statement->prepare($query);

        $statement->bind_param('ssssssssssiii',
            $firstname, $lastname, $adult, $phone,
            $email, $assignment, $gender, $grade,
            $shirtsize, $special, $group, $age, $colorteam);

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
        if ($colorteam_id == NULL) {
            return "none";
        }
        global $conn;

        $query = 'SELECT c.colorgroup
            FROM colorgroups AS c
            WHERE c.id = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);

        $statement->bind_param('i', $colorteam_id);

        $statement->execute();
        //return get_results($statement);
        $result = get_results($statement);
        $team_name = "none";
        if (!empty($result->data) && $result->data[0]['colorgroup'] != NULL)
        {
            $team_name = $result->data[0]['colorgroup'];
        }
        return $team_name;
    }

    // Get a specific name of colorteam by its id
    function get_full_colorteam($colorteam_id)
    {
        global $conn;

        $query = 'SELECT * FROM colorgroups WHERE id = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);

        $statement->bind_param('i', $colorteam_id);

        $statement->execute();
        return get_results($statement);
    }

    // Get a specific name of colorteam by its id
    function get_colorteam_lead($colorteam_id)
    {
        $full_colorteam = get_full_colorteam($colorteam_id);
        global $conn;

        $query = 'SELECT * FROM participants WHERE id = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);

        $statement->bind_param('i', $full_colorteam->data[0]['grouplead']);

        $statement->execute();
        return get_results($statement);
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
        if (empty($result->data))
        {
            return "none";
        }
        return $result->data[0]['groupname'];
    }

    // Get a specific name of a group by its id
    function get_full_group($group_id)
    {
        global $conn;

        $query = 'SELECT * FROM register WHERE id = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);

        $statement->bind_param('i', $group_id);

        $statement->execute();
        return get_results($statement);
    }

    function get_groups()
    {
        global $conn;

        $query = 'SELECT id,groupname FROM register
                    ORDER BY groupname';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        $statement->execute();
        return get_results($statement);
    }

    function get_colorteams()
    {
        global $conn;

        $query = 'SELECT id,colorgroup FROM colorgroups
                    ORDER BY colorgroup';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        $statement->execute();
        return get_results($statement);
    }

    function get_statuses()
    {
        global $conn;

        $query = 'SELECT id,status FROM statuses
                    ORDER BY status';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        $statement->execute();
        return get_results($statement);
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
    function add_status($participant_id, $group_id, $colorteam_id, $status_id = 1)
    {
        global $conn;

        $query = 'INSERT INTO participant_status_map
                    (participantID, groupID, colorteamID, statusID)
                VALUES
                    (?, ?, ?, ?)';

        $statement = $conn->stmt_init();
        $statement->prepare($query);

        $statement->bind_param('iiii', $participant_id, $group_id, $colorteam_id, $status_id);

        $statement->execute();
        $statement->close();

        return $status_id;
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
        if ($participant_id == NULL) {
            return;
        }

        global $conn;

        $query = 'UPDATE participant_status_map SET statusID = ?
                    WHERE participantID = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);

        $statement->bind_param('ii', $status_id, $participant_id);

        $statement->execute();
        $statement->close();
    }

    // Update status of participants
    function update_group_status($group_id, $status_id)
    {
        if ($group_id == NULL) {
            return;
        }

        global $conn;

        $query = 'UPDATE participant_status_map SET statusID = ?
                    WHERE groupID = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);

        $statement->bind_param('ii', $status_id, $group_id);

        $statement->execute();
        $statement->close();
    }

    // Update status of participants
    function update_colorteam_status($colorteam_id, $status_id)
    {
        if ($colorteam_id == NULL) {
            return;
        }

        global $conn;

        $query = 'UPDATE participant_status_map SET statusID = ?
                    WHERE colorteamID = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);

        $statement->bind_param('ii', $status_id, $colorteam_id);

        $statement->execute();
        $statement->close();
    }

?>
