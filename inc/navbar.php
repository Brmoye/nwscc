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

<!--
    <div class="dropdown">
        <button class="dropbtn">
            <a href='<?php //echo $app_path; ?>catalog?action=list_categories'>Categories</a>
        </button>
        <div class="dropdown-content">
-->
            <!-- display links for all categories -->
            <?php
            /*
              $categories = get_categories();
              foreach($categories->data as $category) :
                  $name = $category['name'];
                  $id = $category['categoryID'];
                  $url = $app_path . 'catalog?category_id=' . $id;
                  */
            ?>
<!--
            <a href="<?php //echo $url; ?>">
                <?php //echo htmlspecialchars($name); ?>
            </a>
            <?php //endforeach; ?>
        </div>
    </div>

    <a href="<?php //echo $app_path . 'inc/barcodes.php'; ?>">Barcodes</a>
        <div class="search-container">
        <form action="<?php //echo $app_path; ?>inc/search.php" method="post">
            <input type="text" placeholder="Search.." name="search" autofocus>
            <button type="submit"><i class="fa fa-search"></i></button>
        </form>
    </div>
-->
</nav>
