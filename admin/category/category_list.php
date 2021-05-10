<?php 
    include('../../inc/header.php'); 
    require_once('util/secure_conn.php');
    require_once('util/valid_admin.php');
    //$cats = get_categories();
    $category_id = 9;
    $cat_count = get_inventory_count($category_id);
    
?>


<header>
    <h1>NWSCC CIS Inventory</h1>
    <?php include('../../inc/navbar_admin.php'); ?>
</header>
<main>
    <h1>Category Manager</h1>
    <table id="category_table">
        <?php foreach ($categories->data as $category) : ?>
        <tr>
            <form action="." method="post" >
            <td>
                 <input type="text" name="name"
                        value="<?php echo htmlspecialchars($category['name']); ?>">
            </td>
            <td>
                <input type="hidden" name="action" value="update_category">
                <input type="hidden" name="category_id"
                       value="<?php echo $category['categoryID']; ?>">
                <input type="submit" value="Update">
            </td>
            </form>
            <td>
                <form action="." method="post" >
                    <input type="hidden" name="action" value="delete_category">
                    <input type="hidden" name="category_id" value="<?php echo $category['categoryID']; ?>">
                    <input type="submit" value="Delete">
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <h2 class="add_category">Add Category</h2>
    <form action="." method="post" id="add_category_form" >
        <input type="hidden" name="action" value="add_category">
        <input class="add_category_field" type="text" name="name">
        <input class="add_category_btn" type="submit" value="Add">
    </form>
</main>
<?php include('../../inc/footer.php'); ?>
