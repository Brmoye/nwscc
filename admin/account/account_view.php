<?php include('inc/header.php'); ?>

<header>
    <?php include('inc/navbar_admin.php'); ?>
</header>
<main>
    <h1>Administrator Accounts</h1>
    <?php if (isset($_SESSION['admin'])) : ?>
    <h2>My Account</h2>
    <p><?php echo $_SESSION['admin']['custom1'] .
            ' (' . $_SESSION['admin']['memberID'] . ') in '.
            get_admin_group($_SESSION['admin']['memberID']); ?></p>
    <form action="." method="post">
        <input type="hidden" name="action" value="view_edit">
        <input type="hidden" name="username"
            value="<?php echo $_SESSION['admin']['memberID']; ?>">
        <input type="submit" value="Edit">
    </form>
    <?php endif; ?>
    <?php if (is_valid_super_admin_username($_SESSION['admin']['memberID'])) { ?>
    <?php if ( admin_count() > 1 ) : ?>
        <h2>Other Administrators</h2>
        <table>
        <?php foreach($admins as $admin):
            if ($admin['memberID'] != $_SESSION['admin']['memberID']) : ?>
            <tr>
                <td><?php echo get_admin_group($admin['memberID']); ?></td>
                <td><?php echo $admin['memberID']; ?></td>
                <td><?php echo $admin['custom1']; ?></td>
                <td>
                    <form action="." method="post" class="inline">
                        <input type="hidden" name="action"
                            value="view_edit">
                        <input type="hidden" name="username"
                            value="<?php echo $admin['memberID']; ?>">
                        <input type="submit" value="Edit">
                    </form>
                    <form action="." method="post" class="inline">
                        <input type="hidden" name="action"
                            value="view_delete_confirm">
                        <input type="hidden" name="username"
                            value="<?php echo $admin['memberID']; ?>">
                        <input type="submit" value="Delete">
                    </form>
                </td>
            </tr>
            <?php endif; ?>
        <?php endforeach; ?>
        </table>
    <?php endif; /*admin count / Other Admins*/ ?>
    <h2>Add an Administrator</h2>
    <form action="." method="post" id="add_admin_user_form">
        <input type="hidden" name="action" value="create">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username"
               value="<?php echo htmlspecialchars($username); ?>">
        <span class="error"><?php echo $email_message; ?></span>
        <?php echo $fields->getField('username')->getHTML(); ?><br>

        <label for="group">Group:</label>
        <select id="group" name="group">
        <?php foreach (get_admin_groups()->data as $admin_group) :
                ?>
                <option value="<?php echo $admin_group['groupID']; ?>">
                    <?php echo htmlspecialchars($admin_group['name']); ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <label for="email">E-Mail:</label>
        <input type="email" id="email" name="email"
               value="<?php echo htmlspecialchars($email); ?>">
        <?php echo $fields->getField('email')->getHTML(); ?><br>

        <label for="fullname">Full Name:</label>
        <input type="text" id="fullname" name="fullname"
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

        <label>Password:</label>
        <input type="password" name="password_1">
        <span><?php echo htmlspecialchars($password_message); ?></span>
        <?php echo $fields->getField('password_1')->getHTML(); ?><br>

        <label>Retype password:</label>
        <input type="password" name="password_2">
        <?php echo $fields->getField('password_2')->getHTML(); ?><br>

        <label>&nbsp;</label>
        <input type="submit" value="Add Admin User">
    </form>
    <?php } /* valid super admin */ ?>
</main>
<?php include('inc/footer.php'); ?>