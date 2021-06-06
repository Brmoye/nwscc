<?php
    require_once('../../util/main.php');
    include_once('../../inc/header.php');
    require_once('util/secure_conn.php');
    require_once('util/valid_admin.php');
?>

<header>
    <?php include('../../inc/navbar_admin.php'); ?>
</header>
<main>
<?php
    if (isset($participant_id)) {
        $heading_text = 'Edit Participant';
    } else {
        $heading_text = 'Add Participant';
    }
    ?>
    <h1>Participant Editor - <?php echo $heading_text; ?></h1>
    <form action="." method="post" id="add_participant_form">
        <?php if (isset($participant_id)) : ?>
            <input type="hidden" name="action" value="update_participant">
            <input type="hidden" name="participant_id" value="<?php echo $participants->data[0]['id']; ?>">
        <?php else: ?>
            <input type="hidden" name="action" value="add_participant">
            <?php $participants->data[0] = [
                'group' => '',
                'firstname' => '',
                'lastname' => '',
                'adult' => '',
                'phone' => '',
                'email' => '',
                'assignment' => '',
                'gender' => 'Unspecified',
                'grade' => 'N/A',
                'age' => '',
                'shirtsize' => 'None',
                'special' => 'Allergies, dietary needs, physical needs, etc.',
                'scheduleID' => '',
                'color' => ''
                ]; ?>
        <?php endif; ?>
        <div class="add_edit_form">
            <label class="col-25" for="group">Group:</label>
            <select class="edit_participant" id="group" name="group">
            <?php foreach (get_groups()->data as $group) :
                if ($group['id'] == $participants->data[0]['group'])
                {
                    $selected = 'selected';
                }
                else
                {
                    $selected = '';
                } ?>
                <option value="<?php echo $group['id']; ?>" <?php echo $selected ?>>
                    <?php echo htmlspecialchars($group['groupname']); ?>
                </option>
            <?php endforeach; ?>
            </select>
            <br>

            <label class="col-25" for="colorteam">Schedule / Color Team:</label>
            <select class="edit_participant" id="colorteam" name="colorteam">
            <?php foreach (get_all_schedules()->data as $colorteam) :
                if ($colorteam['scheduleID'] == $participants->data[0]['scheduleID'])
                {
                    $selected = 'selected';
                }
                else
                {
                    $selected = '';
                }
                $colorteam_display_name = $colorteam['colorgroup'].' - '.$colorteam['firstname'].' '.$colorteam['lastname'];
                ?>
                <option value="<?php echo $colorteam['scheduleID']; ?>" <?php echo $selected ?>>
                    <?php echo htmlspecialchars($colorteam_display_name); ?>
                </option>
            <?php endforeach; ?>
            </select>
            <br>

            <label class="col-25" for="firstname">First Name:</label>
            <input class="edit_participant" type="text" name="firstname" id="firstname" value="<?php echo $participants->data[0]['firstname']; ?>">
            <br>

            <label class="col-25" for="lastname">Last Name:</label>
            <input class="edit_participant" type="text" name="lastname" id="lastname" value="<?php echo $participants->data[0]['lastname']; ?>">
            <br>

            <?php
                $gender = $participants->data[0]['gender'];
                if ($gender == NULL) {
                    $gender = "Unspecified";
                }
            ?>
            <label class="col-25" for="gender">Gender:</label>
            <select class="edit_participant" id="gender" name="gender">
                <option value="Female"<?php if ($gender == "Female"){echo " selected";}?>>Female</option>
                <option value="Male"<?php if ($gender == "Male"){echo " selected";}?>>Male</option>
                <option value="Unspecified"<?php if ($gender == "Unspecified"){echo " selected";}?>>Unspecified</option>
            </select>
            <br>

            <label class="col-25" for="age">Age:</label>
            <input class="edit_participant" type="number" name="age" id="age" min="1" max="150" step="1" value="<?php echo $participants->data[0]['age']; ?>">
            <br>

            <label class="col-25" for="adult">Adult Status:</label>
            <select class="edit_participant" id="adult" name="adult">
            <?php
                $adult_status = $participants->data[0]['adult'];
                $age = $participants->data[0]['age'];
                if ($age == NULL) {
                    $age = 0;
                }
                if ($age >= 18 || $adult_status == "Adult (18 & over)") { ?>
                    <option value="None">None</option>
                    <option value="Student (17 or younger)">Student (17 or younger)</option>
                    <option value="Adult (18 & over)" selected>Adult (18 & over)</option>
                        <?php
                } else { ?>
                    <option value="None">None</option>
                    <option value="Student (17 or younger)" selected>Student (17 or younger)</option>
                    <option value="Adult (18 & over)">Adult (18 & over)</option>
                        <?php
                } ?>
            </select>
            <br>

            <?php
                $grade = $participants->data[0]['grade'];
                if ($grade == NULL) {
                    $grade = "N/A";
                }
            ?>
            <label class="col-25" for="grade">Grade:</label>
            <select class="edit_participant" id="grade" name="grade">
                <option value="N/A"<?php if ($grade == "N/A"){echo " selected";}?>>N/A</option>
                <option value="6"<?php if ($grade == 6){echo " selected";}?>>6</option>
                <option value="7"<?php if ($grade == 7){echo " selected";}?>>7</option>
                <option value="8"<?php if ($grade == 8){echo " selected";}?>>8</option>
                <option value="9"<?php if ($grade == 9){echo " selected";}?>>9</option>
                <option value="10"<?php if ($grade == 10){echo " selected";}?>>10</option>
                <option value="11"<?php if ($grade == 11){echo " selected";}?>>11</option>
                <option value="12"<?php if ($grade == 12){echo " selected";}?>>12</option>
            </select>
            <br>

            <label class="col-25" for="phone">Phone:</label>
            <input class="edit_participant" type="tel" id="phone" name="phone" value="<?php echo $participants->data[0]['phone']; ?>" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}">
            <br>

            <label class="col-25" for="email">E-Mail:</label>
            <input class="edit_participant" type="email" id="email" name="email" value="<?php echo $participants->data[0]['email']; ?>">
            <br>

            <?php
                $assignment = $participants->data[0]['assignment'];
                if ($assignment == NULL) {
                    $assignment = "None";
                }
            ?>
            <label class="col-25" for="assignment">Assignment:</label>
            <select class="edit_participant" id="assignment" name="assignment">
                <option value="None"<?php if ($assignment == "None"){echo " selected";}?>>None</option>
                <option value="Driver"<?php if ($assignment == "Driver"){echo " selected";}?>>Driver</option>
                <option value="Kitchen Help"<?php if ($assignment == "Kitchen Help"){echo " selected";}?>>Kitchen Help</option>
                <option value="Project group leader"<?php if ($assignment == "Project group leader"){echo " selected";}?>>Project group leader</option>
            </select>
            <br>

            <?php
                $shirtsize = $participants->data[0]['shirtsize'];
                if ($shirtsize == NULL) {
                    $shirtsize = "None";
                }
            ?>
            <label class="col-25" for="shirtsize">Shirt Size:</label>
            <select class="edit_participant" id="shirtsize" name="shirtsize">
                <option value="None"<?php if ($shirtsize == "None"){echo " selected";}?>>None</option>
                <option value="S"<?php if ($shirtsize == "S"){echo " selected";}?>>S</option>
                <option value="M"<?php if ($shirtsize == "M"){echo " selected";}?>>M</option>
                <option value="L"<?php if ($shirtsize == "L"){echo " selected";}?>>L</option>
                <option value="XL"<?php if ($shirtsize == "XL"){echo " selected";}?>>XL</option>
                <option value="XXL"<?php if ($shirtsize == "XXL"){echo " selected";}?>>XXL</option>
            </select>
            <br>

            <label class="col-25" for="special">Special Needs:</label>
            <textarea rows="6" id="special" name="special"><?php echo $participants->data[0]['special']; ?></textarea>
            <br>

            <label class="col-25" for="statuses">Location Status:</label>
            <select class="edit_participant" id="statuses" name="statuses">
            <?php
            $my_status_id = 7;
            if (isset($participant_id)) {
                $my_status_id = get_statusID($participants->data[0]['id']);
            }
            foreach (get_statuses()->data as $statii) :
                if ($statii['id'] == $my_status_id)
                {
                    $selected = 'selected';
                }
                else
                {
                    $selected = '';
                } ?>
                <option value="<?php echo $statii['id']; ?>" <?php echo $selected ?>>
                    <?php echo htmlspecialchars($statii['status']); ?>
                </option>
            <?php endforeach; ?>
            </select>
            <br>

            <div class="add_edit_buttons">
            <label class="col-15">&nbsp;</label>
            <input class="add_submit_btn" type="submit" name="submit" value="Submit">

            <label class="col-15">&nbsp;</label>
            <input class="add_submit_btn" type="submit" name="submit" value="Delete">
            </div>
        </div>

    </form>
</main>
<?php include '../../inc/footer.php'; ?>
