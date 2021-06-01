<?php include('inc/header.php'); ?>
<?php include('inc/navbar_admin.php'); ?>
<main class="nofloat">
    <h1>Delete Account</h1>
    <p>Are you sure you want to delete the account for
       <?php echo 'name: "'.htmlspecialchars($fullname) . '" email "' .
                  htmlspecialchars($email) .
                  '" (' . htmlspecialchars($username) . ')'; ?>?</p>
    <form action="." method="post">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="username"
               value="<?php echo $username; ?>">
        <input type="submit" value="Delete Account">
    </form>
    <form action="." method="post">
        <input type="submit" value="Cancel">
    </form>
</main>
<?php include('inc/footer.php'); ?>