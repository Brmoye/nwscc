<?php 
    include('../inc/header.php'); 
?>
<header>
    <h1>Hands Free Work Camp Participant</h1>
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
    <?php include('../inc/participant.php'); ?>

</main>
<?php include('../inc/footer.php'); ?>