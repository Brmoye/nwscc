<?php include('../inc/header.php'); ?>

<header>
    <h1>NWSCC CIS Inventory</h1>
    <?php 
        if (isset($_SESSION['admin'])) 
        {
            include('inc/navbar_admin.php');
        }
        else
        {
            include('inc/navbar.php');
        } 
    ?>
</header>
<main>
<?php
    foreach($categories->data as $category) :
        $name = $category['name'];
        $id = $category['categoryID'];
        $url = $app_path . 'catalog?category_id=' . $id;?>
    <p><a href="<?php echo $url; ?>">
        <?php echo htmlspecialchars($name); ?>
    </a></p>
    <?php endforeach; ?>
<?php include('../inc/footer.php'); ?>