<?php
    require_once('../util/main.php');
    require_once('vendor/autoload.php');
    require_once('database/inventory_db.php');
    include_once('inc/header.php');
    global $conn;
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
    <h1>Barcodes</h1>
    <?php
        $results = $conn->query('SELECT * FROM inventory');
        $data1 = array();
        $count = 0;

        $generator = new Picqer\Barcode\BarcodeGeneratorHTML();

        echo '<ul class="barcode">';
        while($row = $results->fetch_assoc()) 
        {
            $col_name = $row['name'];
            $data1[$count][$col_name] = $row['inventoryID'];

            $code = str_pad($data1[$count][$col_name], 8, '0', STR_PAD_LEFT);
            $label = $col_name;

            echo '<li><div>';
            echo $generator->getBarcode($code, $generator::TYPE_CODE_128, 2, 50);
            echo "<div>$code</div>";
            echo "<div>$label</div>";
            echo '</div></li>';

            $count++;

        }
        echo '</ul>';

        include_once('inc/footer.php');
    ?>
</main>