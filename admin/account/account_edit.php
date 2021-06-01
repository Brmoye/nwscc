<?php include('inc/header.php'); ?>
<?php include('inc/navbar_admin.php'); ?>
<main>
    <h1>Edit Account</h1>
    <div id="edit_account_form">
    <h2>Member Since: <?php echo $signup_date;?></h2>
    <form action="." method="post">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="username_id"
               value="<?php echo $username; ?>">

        <label for="username">Username:</label>
        <input type="text" id="username" name="username"
               value="<?php echo htmlspecialchars($username); ?>">
        <span class="error"><?php echo $email_message; ?></span>
        <?php echo $fields->getField('username')->getHTML(); ?><br>

        <?php
        if (is_valid_super_admin_username($_SESSION['admin']['memberID'])) { ?>
        <label for="group">Group:</label>
        <select id="group" name="group">
            <?php foreach (get_admin_groups()->data as $admin_group) :
                ?>
                <option value="<?php echo $admin_group['groupID']; ?>" <?php if ($admin_group['groupID'] == $groupID) {echo "selected";}?> >
                    <?php echo htmlspecialchars($admin_group['name']); ?>
                </option>
            <?php endforeach; ?>
        </select><br>
        <?php } else { ?>
            <input type="hidden" name="group"
               value="<?php echo $groupID; ?>">
        <?php } ?>

        <label>E-Mail:</label>
        <input type="text" name="email"
               value="<?php echo htmlspecialchars($email); ?>">
        <?php echo $fields->getField('email')->getHTML(); ?><br>

        <label>Full Name:</label>
        <input type="text" name="fullname"
               value="<?php echo htmlspecialchars($fullname); ?>">
        <?php echo $fields->getField('fullname')->getHTML(); ?><br>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address"
               value="<?php echo htmlspecialchars($address); ?>">
        <?php echo $fields->getField('address')->getHTML(); ?><br>

        <label for="city">City:</label>
        <input type="text" id="city" name="city"
               value="<?php echo htmlspecialchars($city); ?>">
        <?php echo $fields->getField('city')->getHTML(); ?><br>

        <label for="state">State:</label>
        <input type="text" id="state" name="state"
               value="<?php echo htmlspecialchars($state); ?>">
        <?php echo $fields->getField('state')->getHTML(); ?><br>

        <?php if (is_valid_super_admin_username($_SESSION['admin']['memberID'])) { ?>
        <label for="banned">Banned:</label>
        <select id="banned" name="banned">
            <option value="0"<?php if ($isBanned == 0){echo " selected";}?>>No</option>
            <option value="1"<?php if ($isBanned == 1){echo " selected";}?>>Yes</option>
        </select>
        <br>

        <label for="approved">Approved:</label>
        <select id="approved" name="approved">
            <option value="0"<?php if ($isApproved == 0){echo " selected";}?>>No</option>
            <option value="1"<?php if ($isApproved == 1){echo " selected";}?>>Yes</option>
        </select>
        <br>

        <label for="csvimport">CSV Import:</label>
        <select id="csvimport" name="csvimport">
            <option value="0"<?php if ($csvimport == 0){echo " selected";}?>>No</option>
            <option value="1"<?php if ($csvimport == 1){echo " selected";}?>>Yes</option>
        </select>
        <br>

        <label for="reset_key">Reset Key:</label>
        <input class="edit_admin" type="text" name="reset_key" id="reset_key" value="<?php echo htmlspecialchars($reset_key); ?>">
        <br>

        <label for="reset_expiry">Reset Expiry:</label>
        <input class="edit_admin" type="text" name="reset_expiry" id="reset_expiry" value="<?php echo htmlspecialchars($reset_expiry); ?>">
        <br>

        <label for="flags">Flags:</label>
        <input class="edit_admin" type="text" name="flags" id="flags" value="<?php echo htmlspecialchars($flags); ?>">
        <br>

        <label for="comments">Comments:</label>
        <textarea rows="6" id="comments" name="comments"><?php echo htmlspecialchars($comments); ?></textarea>
        <br>
        <?php } else { ?>
            <input type="hidden" name="banned" value="<?php echo $isBanned; ?>">
            <input type="hidden" name="approved" value="<?php echo $isApproved; ?>">
            <input type="hidden" name="csvimport" value="<?php echo $csvimport; ?>">
            <input type="hidden" name="reset_key" value="<?php echo $reset_key; ?>">
            <input type="hidden" name="reset_expiry" value="<?php echo $reset_expiry; ?>">
            <input type="hidden" name="flags" value="<?php echo $flags; ?>">
            <input type="hidden" name="comments" value="<?php echo $comments; ?>">
        <?php } ?>

        <label>New Password:</label>
        <input type="password" name="password_1">
        <span>Leave blank to leave unchanged</span>
        <?php echo $fields->getField('password_1')->getHTML(); ?><br>

        <label>Retype Password:</label>
        <input type="password" name="password_2">
        <?php echo $fields->getField('password_2')->getHTML(); ?><br>

        <label>&nbsp;</label>
        <input type="submit" value="Update Account">
        <span class="error">
            <?php echo htmlspecialchars($password_message); ?>
        </span><br>
    </form>
    <form action="." method="post">
        <label>&nbsp;</label>
        <input type="submit" value="Cancel">
    </form>
    </div>
</main>
<?php include('inc/footer.php'); ?>