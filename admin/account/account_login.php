<?php include('inc/header.php'); ?>

<header>
    <h1>NWSCC CIS Inventory</h1>
    <?php include('inc/navbar.php'); ?>
</header>
<main>
    <h1>Admin Login</h1>
    <form action="." method="post" id="login_form">
        <input type="hidden" name="action" value="login">
        
        <label>E-Mail:</label>
        <input type="text" name="email"
               value="<?php echo htmlspecialchars($email); ?>" size="30">
        <?php echo $fields->getField('email')->getHTML(); ?><br>

        <label>Password:</label>
        <input type="password" name="password" size="30">
        <?php echo $fields->getField('password')->getHTML(); ?><br>


        <input type="submit" value="Login">
        <?php if (!empty($password_message)) : ?>         
        <span class="error">
            <?php echo htmlspecialchars($password_message); ?>
        </span><br>
        <?php endif; ?>
    </form>
</main>
<?php include('inc/footer.php'); ?>