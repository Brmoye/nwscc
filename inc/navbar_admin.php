<?php
    require_once('util/main.php');
    require_once('database/database.php');
    require_once('database/participants_db.php');
    require_once('util/valid_admin.php');

    function set_status_color($status_id) {
        // At Central - silver
        // At UAH - aqua
        // At Project - red
        // Heading to Project - maroon
        // Heading to UAH - teal
        // Heading to Central - gray
        // Signed out to Parent - none
        if ($status_id == 7) {
            // Signed out to Parent - no concern
            return "";
        } else if ($status_id == 6) {
            // At Project
            return 'class="status_red"';
        } else if ($status_id == 5) {
            // Heading to Central
            return 'class="status_gray"';
        } else if ($status_id == 4) {
            // Heading to UAH
            return 'class="status_teal"';
        } else if ($status_id == 3) {
            // At UAH
            return 'class="status_aqua"';
        } else if ($status_id == 2) {
            // Heading to Project
            return 'class="status_maroon"';
        } else if ($status_id == 1) {
            // At Central
            return 'class="status_silver"';
        }
    }

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

    <div class="dropdown">
        <button class="dropbtn">
            <a href='<?php echo $app_path; ?>admin/participants'>Groups</a>
        </button>
        <div class="dropdown-content">
            <!-- display links for all categories -->
            <?php
            $groups = get_groups();
            foreach($groups->data as $pt) : ?>
                <?php
                  $name = $pt['groupname'];
                  $id = $pt['id'];
                  $url = $app_path . 'admin/participants?group_id=' . $id;
                ?>
            <a href="<?php echo $url; ?>">
                <?php echo htmlspecialchars($name); ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="dropdown">
        <button class="dropbtn">
            <a href='<?php echo $app_path; ?>admin/participants'>Color Teams</a>
        </button>
        <div class="dropdown-content">
            <!-- display links for all categories -->
            <?php
            $teams = get_colorteams();
            foreach($teams->data as $pt) : ?>
                <?php
                  $name = $pt['displayColor'];
                  $id = $pt['displayID'];
                  $url = $app_path . 'admin/participants?colorteam_id=' . $id;
                  if ($name == "") {
                      $name = "Default";
                  }
                ?>
            <a href="<?php echo $url; ?>">
                <?php echo htmlspecialchars($name); ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <a href="<?php echo $app_path . 'admin/participants/?action=view_participant'; ?>">Add Participant</a>
    <a href="<?php echo $app_path . "admin/account"; ?>">Admin</a>

    <div class="search-container">
        <form action="<?php echo $app_path; ?>inc/search.php" method="post">
            <input type="text" placeholder="Search Participants.." name="search" autofocus>
            <button type="submit"><i class="fa fa-search"></i></button>
        </form>
    </div>
</nav>
