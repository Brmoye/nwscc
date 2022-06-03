<?php
    require_once('util/results.php');
    // All functions below connect via mysqli

    function get_all_participants()
    {
        global $conn;

        $query = 'SELECT
            p.id,p.lastname,p.firstname,p.group,p.special,m.statusID,s.color,p.colorteam AS scheduleID
            FROM participants AS p
            LEFT JOIN participant_status_map AS m ON
                p.id = m.participantID
            LEFT JOIN schedule AS s ON
                s.id = p.colorteam
            ORDER BY p.group,p.lastname,p.id';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        $statement->execute();
        return get_results($statement);
    }

    function get_participants_by_colorteam($schedule_id)
    {
        global $conn;

        $query = 'SELECT p.id,p.lastname,p.firstname,p.group,s.color,p.colorteam AS scheduleID,p.special,m.statusID
            FROM participants AS p
            LEFT JOIN participant_status_map AS m ON
                p.id = m.participantID
            LEFT JOIN schedule AS s ON
                s.id = p.colorteam
            WHERE p.colorteam = ?
            ORDER BY p.lastname,p.firstname,p.id';

        $statement = $conn->stmt_init();
        $statement->prepare($query);

        $statement->bind_param('i', $schedule_id);

        $statement->execute();
        return get_results($statement);
    }

    function get_participants_by_group($group_id)
    {
        global $conn;

        $query = 'SELECT p.id,p.lastname,p.firstname,p.group,s.color,p.colorteam AS scheduleID,p.special,m.statusID
            FROM participants AS p
            LEFT JOIN participant_status_map AS m ON
                p.id = m.participantID
            LEFT JOIN schedule AS s ON
                s.id = p.colorteam
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

        $query = 'SELECT
            p.id,p.group,p.lastname,p.firstname,p.adult,p.phone,p.email,p.assignment,p.gender,p.grade,p.age,p.shirtsize,p.special,m.statusID,s.color,p.colorteam AS scheduleID
            FROM participants AS p
            LEFT JOIN participant_status_map AS m ON
                p.id = m.participantID
            LEFT JOIN schedule AS s ON
                s.id = p.colorteam
            WHERE p.id = ?';

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

    function get_participants_count_colorteam($colorteam_id1, $colorteam_id2 = "")
    {
        if ($colorteam_id2 == "") {
            $all_ids = get_color_ids($colorteam_id1);
            if ($all_ids != NULL && count($all_ids->data) >= 2) {
                $colorteam_id1 = $all_ids->data[0]['colorteamID'];
                $colorteam_id2 = $all_ids->data[1]['colorteamID'];
            } else {
                $colorteam_id2 = $colorteam_id1;
            }
        }
        global $conn;

        $query = 'SELECT COUNT(*) AS participantsCount
                    FROM participants
                    WHERE (colorteam = ? OR colorteam = ?)';

        $statement = $conn->stmt_init();
        $statement->prepare($query);

        $statement->bind_param('ii', $colorteam_id1, $colorteam_id2);

        $statement->execute();
        $result = get_results($statement);
        return $result->data[0]['participantsCount'];
    }

    function update_participant($participant_id, $group, $firstname, $lastname, $adult, $phone, $email, $assignment, $gender, $grade, $age, $shirtsize, $special, $schedule)
    {
        global $conn;

        // colorteam column is misnamed and is actually the schedule
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
            $schedule,$participant_id);

        $statement->execute();
        $statement->close();
    }

    function add_participant($group, $firstname, $lastname, $adult, $phone, $email, $assignment, $gender, $grade, $age, $shirtsize, $special, $schedule) {
        global $conn;

        // colorteam column is misnamed and is actually the schedule
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
            $shirtsize, $special, $group, $age, $schedule);

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

    // Get a specific name of a schedule by its id
    function get_schedule_color($schedule_id)
    {
        if ($schedule_id === NULL || $schedule_id === "") {
            return "none";
        }
        global $conn;

        $query = 'SELECT cg.displayColor
            FROM colorgroup_alias_map AS cg
            WHERE cg.scheduleID = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);

        $statement->bind_param('i', $schedule_id);

        $statement->execute();
        //return get_results($statement);
        $result = get_results($statement);
        $team_name = "none";
        if (!empty($result->data) && $result->data[0]['displayColor'] != NULL)
        {
            $team_name = $result->data[0]['displayColor'];
        }
        return $team_name;
    }

    function get_groups_in_color($color) {
        if ($color == NULL || $color === "" || $color === "none") {
            $result = new stdClass();
            $result->data = [];
            return $result;
        }
        global $conn;
        $query = 'SELECT c.id,c.grouplead,c.colorgroup,c.workday,c.admin_notes,p.firstname,p.lastname,p.phone
            FROM colorgroups AS c
            LEFT JOIN participants AS p ON
                c.grouplead = p.id
            WHERE c.colorgroup = ?';
        $statement = $conn->stmt_init();
        $statement->prepare($query);
        $statement->bind_param('s', $color);
        $statement->execute();
        return get_results($statement);
    }

    // Get a specific name of colorteam by its id
    function get_colorteam_leads($schedule_id)
    {
        if ($schedule_id == NULL || $schedule_id === "") {
            $result = new stdClass();
            $result->data = [];
            return $result;
        }

        return get_groups_in_color(get_schedule_color($schedule_id));
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

    function get_schedule_display_id($id) {
        if ($id == NULL) {
            return NULL;
        }
        global $conn;

        $query = 'SELECT DISTINCT displayID,displayColor FROM colorgroup_alias_map
                    WHERE scheduleID = ?
                    ORDER BY displayColor';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        $statement->bind_param('i', $id);
        $statement->execute();
        return get_results($statement);
    }

    function get_color_ids($colorteam_id) {
        // allow colorteam_id to actually be the schedule.id
        if ($colorteam_id == NULL) {
            return NULL;
        }
        $display_data = get_schedule_display_id($colorteam_id);
        if ($display_data == NULL || count($display_data->data) == 0) {
            return NULL;
        }
        $display_id = $display_data->data[0]['displayID'];

        global $conn;

        $query = 'SELECT *
            FROM colorgroup_alias_map
            WHERE displayID = ?
            ORDER BY scheduleID';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        $statement->bind_param('i', $display_id);
        $statement->execute();
        return get_results($statement);
    }

    function get_colorteams()
    {
        global $conn;

        $query = 'SELECT DISTINCT displayID,displayColor FROM colorgroup_alias_map
                    ORDER BY displayColor,displayID';

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

        $result = get_results($statement);
        if (empty($result->data))
        {
            return 0;
        }
        return $result->data[0]['statusID'];
    }

    // Get a specific status string of a participant by statusID
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
    function add_status($participant_id, $group_id, $colorteam_id = "", $schedule_id = "", $status_id = 7)
    {
        // Apparently values used in bind_param get cleared after execute()?
        $save_participant_id = $participant_id;

        global $conn;

        $query = 'INSERT INTO participant_status_map
                    (participantID, groupID, statusID)
                VALUES
                    (?, ?, ?)';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        $statement->bind_param('iii', $participant_id, $group_id, $status_id);
        $statement->execute();
        $statement->close();

        if ($colorteam_id !== NULL && $colorteam_id !== "") {
            $participant_id = $save_participant_id;
            $query = 'UPDATE participant_status_map SET colorteamID = ? WHERE participantID = ?';
            $statement = $conn->stmt_init();
            $statement->prepare($query);
            $statement->bind_param('ii', $colorteam_id, $participant_id);
            $statement->execute();
            $admin_id = $conn->insert_id;
            $statement->close();
        }
        if ($schedule_id !== NULL && $schedule_id !== "") {
            $participant_id = $save_participant_id;
            $query = 'UPDATE participant_status_map SET scheduleID = ? WHERE participantID = ?';
            $statement = $conn->stmt_init();
            $statement->prepare($query);
            $statement->bind_param('ii', $schedule_id, $participant_id);
            $statement->execute();
            $admin_id = $conn->insert_id;
            $statement->close();
        }

        return $status_id;
    }

    // Get a specific status of a participant by statusID
    function get_add_status($participant_id, $group_id, $schedule_id = "", $colorteam_id = "")
    {
        $status_id = get_statusID($participant_id);

        if ($status_id == 0) {
            $status_id = add_status(
                $participant_id, $group_id, $colorteam_id, $schedule_id);
        }
        return get_status($status_id);
    }

    // Update status of a participant
    function update_status($participant_id, $status_id, $schedule_id = "", $colorteam_id = "", $clear_absent = false)
    {
        if ($participant_id == NULL) {
            return;
        }

        // Apparently values used in bind_param get cleared after execute()?
        $save_participant_id = $participant_id;

        global $conn;

        $query = 'UPDATE participant_status_map SET statusID = ? WHERE ( participantID = ? AND statusID != 8 )';
        if ($clear_absent == true) {
            $query = 'UPDATE participant_status_map SET statusID = ? WHERE participantID = ?';
        }

        $statement = $conn->stmt_init();
        $statement->prepare($query);

        $statement->bind_param('ii', $status_id, $participant_id);

        $statement->execute();
        $statement->close();

        if ($colorteam_id != NULL && $colorteam_id !== "") {
            $participant_id = $save_participant_id;
            $query = 'UPDATE participant_status_map SET colorteamID = ? WHERE participantID = ?';
            $statement = $conn->stmt_init();
            $statement->prepare($query);
            $statement->bind_param('ii', $colorteam_id, $participant_id);
            $statement->execute();
            $admin_id = $conn->insert_id;
            $statement->close();
        }
        if ($schedule_id != NULL && $schedule_id !== "") {
            $participant_id = $save_participant_id;
            $query = 'UPDATE participant_status_map SET scheduleID = ? WHERE participantID = ?';
            $statement = $conn->stmt_init();
            $statement->prepare($query);
            $statement->bind_param('ii', $schedule_id, $participant_id);
            $statement->execute();
            $admin_id = $conn->insert_id;
            $statement->close();
        }
    }

    // Update status of a participant
    function update_status_absence($participant_id, $status_id)
    {
        update_status($participant_id, $status_id, "", "", true);
    }

    function update_participants_status()
    {
        // Now freshly build a status map for every participant
        foreach (get_all_participants()->data as $participant) {
            $participant_id = $participant['id'];

            $status_id = get_statusID($participant_id);
            $schedule_id = $participant['scheduleID'];
            $colorteam_id = $participant['color'];
            $group_id = $participant['group'];

            if ($status_id == 0) {
                $status_id = add_status(
                    $participant_id, $group_id, $schedule_id, $colorteam_id);
            }

            update_status($participant_id, $status_id, $schedule_id, $colorteam_id, false);
        }
    }

    function get_colorteam_displayID($color) {
        $found_id = NULL;
        if ($color === NULL) {
            return $found_id;
        }
        global $conn;

        $query = 'SELECT * FROM `colorgroup_alias_map`
            WHERE `displayColor` = ?';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        $statement->bind_param('s', $color);
        $statement->execute();
        $result = get_results($statement);

        if (empty($result->data)) {
            return $found_id;
        }

        $found_id = $result->data[0]['displayID'];

        return $found_id;
    }

    function get_all_schedules()
    {
        global $conn;

        $query = 'SELECT s.id AS scheduleID,s.color AS colorteamID,c.colorgroup,c.grouplead,c.workday,c.admin_notes,p.firstname,p.lastname,p.group,p.phone
            FROM schedule AS s
            LEFT JOIN colorgroups AS c ON
                s.color = c.id
            LEFT JOIN participants AS p ON
                        c.grouplead = p.id
            ORDER BY c.colorgroup';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        $statement->execute();
        return get_results($statement);
    }

    function add_colorteam_alias($colorteam_id, $schedule_id, $leader_id, $display_color) {
        $display_id = $schedule_id;

        global $conn;
        $query = 'INSERT INTO colorgroup_alias_map
                    (colorteamID, leaderID, displayID, scheduleID, displayColor)
                VALUES
                    (?, ?, ?, ?, ?)';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        $statement->bind_param('iiiis',
            $colorteam_id, $leader_id, $display_id, $schedule_id, $display_color);
        $statement->execute();
        $statement->close();
    }

    // Only color groups that are assigned to a schedule should be added
    function update_colorteam_aliases()
    {
        // Get our work list
        $all_schedules = get_all_schedules();

        // Clear out the table and build the list afresh.
        global $conn;

        // LOCK apparently returns a bool
        $result = $conn->query('LOCK TABLES colorgroup_alias_map WRITE');

        $query = 'TRUNCATE colorgroup_alias_map';

        $statement = $conn->stmt_init();
        $statement->prepare($query);
        $statement->execute();
        $statement->close();

        // Build up the alias map of all available schedules
        foreach ($all_schedules->data as $colorteam) {
            add_colorteam_alias($colorteam['colorteamID'],
                $colorteam['scheduleID'], $colorteam['grouplead'],
                $colorteam['colorgroup']);
        }

        $result = $conn->query('UNLOCK TABLES');
    }

    // Update status of participants
    function update_group_status($group_id, $status_id)
    {
        if ($group_id == NULL) {
            return;
        }

        global $conn;

        $query = 'UPDATE participant_status_map SET statusID = ?
                    WHERE ( groupID = ? AND statusID != 8 )';

        $statement = $conn->stmt_init();
        $statement->prepare($query);

        $statement->bind_param('ii', $status_id, $group_id);

        $statement->execute();
        $statement->close();
    }

    // Update status of participants
    function update_colorteam_status($schedule_id, $status_id)
    {
        if ($schedule_id == NULL) {
            return;
        }

        global $conn;

        $query = 'UPDATE participant_status_map SET statusID = ?
                    WHERE ( scheduleID = ? AND statusID != 8 )';

        $statement = $conn->stmt_init();
        $statement->prepare($query);

        $statement->bind_param('ii', $status_id, $schedule_id);

        $statement->execute();
        $statement->close();
    }

?>
