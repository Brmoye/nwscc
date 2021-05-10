<?php
    require_once('vendor/autoload.php');
    // Parse data
    $category = get_category($inventory->data[0]['categoryID']);
    $categoryName = $category->data[0]['name'];
    $name = $inventory->data[0]['name'];
    $itemCondition = $inventory->data[0]['itemCondition'];
    $description = $inventory->data[0]['description'];
    $location = $inventory->data[0]['location'];
?>

<h1><?php echo htmlspecialchars($name); ?></h1>
<div id=inventory_info>
    <label class="inv_label"><b>Category: </b></label>
    <p class="col-75"><?php echo $categoryName; ?></p>
    <br />

    <label class="inv_label"><b>Name:</b></label>
    <p class="col-75"><?php echo $name; ?></p>
    <br />

    <label class="inv_label"><b>Condition:</b></label>
    <p class="col-75"><?php echo $itemCondition; ?></p>
    <br />

    <label class="inv_label"><b>Location:</b></label>
    <p class="col-75"><?php echo $location; ?></p>
    <br />

    <label class="inv_label"><b>Description:</b></label>
    <p class="col-75"><?php echo $description; ?></p>
    <br />
</div>
<?php 
    $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
    echo '<ul class="barcode_ind">';
    $code = str_pad($inventory_id, 8, '0', STR_PAD_LEFT);
    $label = $inventory->data[0]['name'];
    echo '<li><div>';
    echo $generator->getBarcode($code, $generator::TYPE_CODE_128, 2, 50);
    echo "<div>$code</div>";
    echo "<div>$label</div>";
    echo '</div></li>';
    echo '</ul>';
?>