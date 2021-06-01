<?php
    require_once('database/database.php');
    require_once('database/participants_db.php');
?>
<nav class="navbar">
    <a href="<?php echo $app_path; ?>">Home</a>
    <?php
    // Check if user is logged in and
    // display appropriate account links
    $account_url = $app_path . 'admin/account';
    ?>

    <a href="<?php echo $account_url; ?>">Login</a>
    <a href="<?php echo $app_path; ?>admin">Admin</a>
</nav>
