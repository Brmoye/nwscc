<?php
    require_once('../../util/main.php');
    include_once('../../inc/header.php');
    require_once('util/secure_conn.php');
    require_once('util/valid_admin.php');
    require_once('vendor/autoload.php');
?>

<header>
    <h1>NWSCC CIS Inventory</h1>
    <?php include('../../inc/navbar_admin.php'); ?>
</header>
<main>
<?php
    if (isset($inventory_id)) {
        $heading_text = 'Edit Inventory';
    } else {
        $heading_text = 'Add Inventory';
    }
    ?>
    <h1>Inventory Manager - <?php echo $heading_text; ?></h1>
    <form action="." method="post" id="add_inventory_form"> 
        <?php if (isset($inventory_id)) : ?>
            <input type="hidden" name="action" value="update_inventory">
            <input type="hidden" name="inventory_id" value="<?php echo $inventory->data[0]['inventoryID']; ?>">
        <?php else: ?>
            <input type="hidden" name="action" value="add_inventory">
            <?php $inventory->data[0] = [
                'categoryID' => '',
                'name' => '',
                'itemCondition' => '',
                'location' => '',
                'description' => ''
                ]; ?>
        <?php endif; ?>
            <input type="hidden" name="category_id" value="<?php echo $inventory->data[0]['categoryID']; ?>">
        <div class="add_edit_form">
            <label class="col-25">Category:</label>
            <select class="edit_inventory" name="category_id">
            <?php foreach ($categories->data as $category) :
                if ($category['categoryID'] == $inventory->data[0]['categoryID'])
                {
                    $selected = 'selected';
                }
                else
                {
                    $selected = '';
                } ?>
                <option value="<?php echo $category['categoryID']; ?>"<?php echo $selected ?>>
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
            <?php endforeach; ?>
            </select>
            <br>
        
            <label class="col-25">Name:</label>
            <input class="edit_inventory" type="text" name="name" value="<?php echo $inventory->data[0]['name']; ?>">
            <br>

            <label class="col-25">Item Condition:</label>
            <input class="edit_inventory" type="text" name="itemCondition" value="<?php echo $inventory->data[0]['itemCondition']; ?>">
            <br>

            <label class="col-25">Location:</label>
            <input class="edit_inventory" type="text" size="30" name="location" value="<?php echo $inventory->data[0]['location']; ?>">
            <br>

            <label class="col-25">Description:</label>
            <textarea class="edit_inventory" type="text" name="description" rows="10" columns="2"><?php echo $inventory->data[0]['description']; ?></textarea></td>
            <br>

            <div class="add_edit_buttons">
            <label class="col-15">&nbsp;</label>
            <input class="add_submit_btn" type="submit" name="submit" value="Submit">

            <label class="col-15">&nbsp;</label>
            <input class="add_submit_btn" type="submit" name="submit" value="Delete">
            </div>
        </div>
        
    </form>
    <?php 
        if (isset($inventory_id))
        {
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
        }
    ?>
</main>
<?php include '../../inc/footer.php'; ?>
