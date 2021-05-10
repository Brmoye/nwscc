<?php 
    include('../inc/header.php'); 
?>
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
    <!-- display product -->
    <?php include('../inc/inventory.php'); ?>

</main>
<?php include('../inc/footer.php'); ?>