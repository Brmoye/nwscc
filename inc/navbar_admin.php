<?php
    require_once('database/database.php');
    require_once('database/participants_db.php');
?>
<nav class="navbar">
    <a href="<?php echo $app_path . 'admin'; ?>">Home</a>
    <?php
    // Check if user is logged in and
    // display appropriate account links
    $account_url = $app_path . 'admin/account';
    $logout_url = $account_url . '?action=logout';
    if (isset($_SESSION['admin'])) :
    ?>
        <a href="<?php echo $logout_url; ?>">Logout</a>
    <?php else: ?>
        <a href="<?php echo $account_url; ?>">Login</a>
    <?php endif; ?>
    <a href="<?php echo $app_path . "admin/account"; ?>">Admin</a>

    <div class="dropdown">
        <button class="dropbtn">
            <a href='<?php echo $app_path; ?>admin/participants?action=list_participants'>Edit Participants</a>
        </button>
        <div class="dropdown-content">
            <!-- display links for all categories -->
            <?php
            $participants = get_participants();
            foreach($participants->data as $category) : ?>
                <?php 
                  $name = $category['name'];
                  $id = $category['categoryID'];
                  $url = $app_path . 'admin/participants?category_id=' . $id;
                ?>
            <a href="<?php echo $url; ?>">
                <?php echo htmlspecialchars($name); ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <a href="<?php echo $app_path . 'admin/participants/?action=view_participants'; ?>">Add Participant</a>

    <div class="search-container">
        <form action="<?php echo $app_path; ?>inc/search.php" method="post">
            <input type="text" placeholder="Search.." name="search" autofocus>
            <button type="submit"><i class="fa fa-search"></i></button>
        </form>
    </div>
</nav>
