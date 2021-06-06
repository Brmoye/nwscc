<?php
require_once('../../util/main.php');
require_once('util/secure_conn.php');
require_once('database/admin_db.php');
require_once('database/participants_db.php');
require_once('database/fields.php');
require_once('database/validate.php');

$action = filter_input(INPUT_POST, 'action');
/*if (admin_count() == 0)
{
    if ($action != 'create')
    {
        $action = 'view_account';
    }
}
else*/if (isset($_SESSION['admin']))
{
    if ($action == null)
    {
        $action = filter_input(INPUT_GET, 'action');
        if ($action == null )
        {
            $action = 'view_account';
        }
    }
}
elseif ($action == 'login')
{
    $action = 'login';
}
else
{
    $action = 'view_login';
}

// Set up all possible fields to validate
$validate = new Validate();
$fields = $validate->getFields();

// for the Add Account page and other pages
$fields->addField('username');
$fields->addField('password_1');
$fields->addField('password_2');
$fields->addField('fullname');
$fields->addField('email');
$fields->addField('signupDate');
$fields->addField('address');
$fields->addField('city');
$fields->addField('state');
$fields->addField('comments');
$fields->addField('flags');
$fields->addField('groupID');
$fields->addField('isBanned');
$fields->addField('isApproved');
$fields->addField('csvimport');
$fields->addField('reset_key');
$fields->addField('reset_expiry');

// for the Login page
$fields->addField('password');
$email_message = "";

switch ($action)
{
    case 'view_login':
        // Clear login data
        $username = '';
        $password = '';
        $password_message = '';

        include 'account_login.php';
        break;
    case 'login':
        // Get username/password
        $username = filter_input(INPUT_POST, 'username');
        $password = filter_input(INPUT_POST, 'password');

        // Validate user data
        $validate->text('username', $username);
        $validate->text('password', $password, true, 6, 30);

        // If validation errors, redisplay Login page and exit controller
        if ($fields->hasErrors())
        {
            include 'admin/account/account_login.php';
            break;
        }

        // Check database - if valid username/password, log in
        if (is_valid_admin_login($username, $password))
        {
            $_SESSION['admin'] = get_admin_by_username($username);
        }
        else
        {
            $password_message = 'Login failed. Invalid email or password.';
            include 'admin/account/account_login.php';
            break;
        }

        // Update participant_status_map and colorteam aliases
        update_participants_status();
        update_colorteam_aliases();

        // Display Admin Menu page
        redirect('..');
        break;

    default:
    case 'view_account':
        // Get all accounts from database
        $admins = get_all_admins();

        // Set up variables for add form
        $username = '';
        $fullname = '';
        $email = '';
        $address = '';
        $city = '';
        $state = '';
        if (!isset($email_message))
        {
            $email_message = '';
        }
        if (!isset($password_message))
        {
            $password_message = '';
        }

        // View admin accounts
        include 'account_view.php';
        break;

    case 'create':
        // Get admin user data
        $username = filter_input(INPUT_POST, 'username');
        $email = filter_input(INPUT_POST, 'email');
        $groupID = filter_input(INPUT_POST, 'group', FILTER_VALIDATE_INT);
        $fullname = filter_input(INPUT_POST, 'fullname');
        $address = filter_input(INPUT_POST, 'address');
        $city = filter_input(INPUT_POST, 'city');
        $state = filter_input(INPUT_POST, 'state');
        $password_1 = filter_input(INPUT_POST, 'password_1');
        $password_2 = filter_input(INPUT_POST, 'password_2');

        $admins = get_all_admins();
        $email_message = '';
        $password_message = '';

        // Validate admin user data
        $validate->text('username', $username);
        $validate->email('email', $email);
        $validate->text('fullname', $fullname);
        $validate->text('address', $address);
        $validate->text('city', $city);
        $validate->text('state', $state);
        $validate->text('password_1', $password_1, true, 6, 30);
        $validate->text('password_2', $password_2, true, 6, 30);

        // If validation errors, redisplay Login page and exit controller
        if ($fields->hasErrors())
        {
            include 'admin/account/account_view.php';
            break;
        }

        if (is_valid_admin_email($email))
        {
            $email_message = 'This email is already in use.';
            include 'admin/account/account_view.php';
            break;
        }

        if (is_valid_admin_username($username))
        {
            $email_message = 'This username is already in use.';
            include 'admin/account/account_view.php';
            break;
        }

        if ($password_1 !== $password_2)
        {
            $password_message = 'Passwords do not match.';
            include 'admin/account/account_view.php';
            break;
        }

        // Add admin user
        add_admin(
            $username, $password_1, $email, $groupID,
            $fullname, $address, $city, $state);

        // Set admin user in session
        if (!isset($_SESSION['admin']))
        {
            $_SESSION['admin'] = get_admin_by_username($username);
        }

        redirect('.');
        break;

    case 'view_edit':
        // Get admin user data
        $username_id = filter_input(INPUT_POST, 'username_id');
        $username = filter_input(INPUT_POST, 'username');
        if ($username_id == NULL) {
            $username_id = $username;
        }
        $admin = get_admin_by_username($username_id);
        $groupID = $admin['groupID'];
        $email = $admin['email'];
        $fullname = $admin['custom1'];
        $address = $admin['custom2'];
        $city = $admin['custom3'];
        $state = $admin['custom4'];
        $isBanned = $admin['isBanned'];
        $isApproved = $admin['isApproved'];
        $signup_date = $admin['signupDate'];
        $comments = $admin['comments'];
        $reset_key = $admin['pass_reset_key'];
        $reset_expiry = $admin['pass_reset_expiry'];
        $csvimport = $admin['allowCSVImport'];
        $flags = $admin['flags'];

        $password_message = '';

        // Display Edit page
        include 'account_edit.php';
        break;


    case 'update':
        $username_id = filter_input(INPUT_POST, 'username_id');
        $username = filter_input(INPUT_POST, 'username');
        if ($username_id == NULL) {
            $username_id = $username;
        }
        $email = filter_input(INPUT_POST, 'email');
        $groupID =  filter_input(INPUT_POST, 'group', FILTER_VALIDATE_INT);
        $fullname = filter_input(INPUT_POST, 'fullname');
        $address = filter_input(INPUT_POST, 'address');
        $city = filter_input(INPUT_POST, 'city');
        $state = filter_input(INPUT_POST, 'state');
        $isBanned = filter_input(INPUT_POST, 'banned', FILTER_VALIDATE_INT);
        $isApproved = filter_input(INPUT_POST, 'approved', FILTER_VALIDATE_INT);
        $comments = filter_input(INPUT_POST, 'comments');
        $reset_key = filter_input(INPUT_POST, 'reset_key');
        $reset_expiry = filter_input(INPUT_POST, 'reset_expiry', FILTER_VALIDATE_INT);
        $csvimport = filter_input(INPUT_POST, 'csvimport', FILTER_VALIDATE_INT);
        $flags = filter_input(INPUT_POST, 'flags');
        $password_1 = filter_input(INPUT_POST, 'password_1');
        $password_2 = filter_input(INPUT_POST, 'password_2');

        $admin = get_admin_by_username($username_id);
        $signup_date = $admin['signupDate'];

        // Validate admin user data
        $validate->email('email', $email);
        $validate->text('fullname', $fullname);
        $validate->text('username', $username);
        $validate->text('password_1', $password_1, false, 6, 60);
        $validate->text('password_2', $password_2, false, 6, 60);

        // If validation errors, redisplay Login page and exit controller
        if ($fields->hasErrors())
        {
            include 'admin/account/account_edit.php';
            break;
        }

        if ($password_1 !== $password_2)
        {
            $password_message = 'Passwords do not match.';
            include 'admin/account/account_edit.php';
            break;
        } else if ($password_1 !== "" && strlen($password_1) < 6) {
            $password_message = "Passwords must be at least 6 characters long. (Got ".strlen($password_1)." characters)";
            include 'admin/account/account_edit.php';
            break;
        }

        update_admin($username_id, $username, $password_1, $password_2,
            $email, $groupID, $fullname, $address, $city, $state,
            $comments, $isBanned, $isApproved, $csvimport,
            $reset_key, $reset_expiry, $flags);

        if ($username_id == $_SESSION['admin']['memberID'])
        {
            $_SESSION['admin'] = get_admin_by_username($username_id);
        }
        redirect($app_path . 'admin/account/.?action=view_account');
        break;

    case 'view_delete_confirm':
        $username = filter_input(INPUT_POST, 'username');
        if ($username == $_SESSION['admin']['memberID'])
        {
            display_error('You cannot delete your own account.');
        }
        $admin = get_admin_by_username($username);

        $fullname = $admin['custom1'];
        $groupID = $admin['groupID'];
        $email = $admin['email'];
        include 'account_delete.php';
        break;

    case 'delete':
        $username = filter_input(INPUT_POST, 'username');
        delete_admin($username);
        redirect($app_path . 'admin/account');
        break;

    case 'logout':
        unset($_SESSION['admin']);
        redirect($app_path . 'admin/account');
        break;
}
?>