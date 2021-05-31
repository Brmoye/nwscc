<?php
    require_once('../../util/main.php');
    include_once('../../inc/header.php');
    require_once('util/secure_conn.php');
    require_once('util/valid_admin.php');
    require_once('vendor/autoload.php');
?>

<header>
    <h1>Hands Free Work Camp Participant</h1>
    <?php include('../../inc/navbar_admin.php'); ?>
</header>
<main>
<?php
    if (isset($participants_id)) {
        $heading_text = 'Edit Participant';
    } else {
        $heading_text = 'Add Participant';
    }
    ?>
    <h1>Participant Editor - <?php echo $heading_text; ?></h1>
    <form action="." method="post" id="add_participant_form"> 
        <?php if (isset($participant_id)) : ?>
            <input type="hidden" name="action" value="update_particpant">
            <input type="hidden" name="participant_id" value="<?php echo $participants->data[0]['id']; ?>">
        <?php else: ?>
            <input type="hidden" name="action" value="add_participant">
            <?php $participants->data[0] = [
                'categoryID' => '',
                'name' => '',
                'itemCondition' => '',
                'location' => '',
                'description' => ''
                ]; ?>
        <?php endif; ?>
            <input type="hidden" name="category_id" value="<?php echo $participants->data[0]['categoryID']; ?>">
        <div class="add_edit_form">
            <label class="col-25">Category:</label>
            <select class="edit_participant" name="category_id">
            <?php foreach ($categories->data as $category) :
                if ($category['categoryID'] == $participants->data[0]['categoryID'])
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
            <input class="edit_participant" type="text" name="name" value="<?php echo $participants->data[0]['name']; ?>">
            <br>

            <label class="col-25">Item Condition:</label>
            <input class="edit_participant" type="text" name="itemCondition" value="<?php echo $participants->data[0]['itemCondition']; ?>">
            <br>

            <label class="col-25">Location:</label>
            <input class="edit_participant" type="text" size="30" name="location" value="<?php echo $participants->data[0]['location']; ?>">
            <br>

            <label class="col-25">Description:</label>
            <textarea class="edit_participant" type="text" name="description" rows="10" columns="2"><?php echo $participants->data[0]['description']; ?></textarea></td>
            <br>

            <div class="add_edit_buttons">
            <label class="col-15">&nbsp;</label>
            <input class="add_submit_btn" type="submit" name="submit" value="Submit">

            <label class="col-15">&nbsp;</label>
            <input class="add_submit_btn" type="submit" name="submit" value="Delete">
            </div>
        </div>
        
    </form>
</main>
<?php include '../../inc/footer.php'; ?>
