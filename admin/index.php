<?php
    require_once('../util/main.php');
    require_once('../util/secure_conn.php');
    require_once('../util/valid_admin.php');
    include('inc/header.php');
    require_once('inc/pagination.php');
    require_once('../database/database.php');
    require_once('../database/category_db.php');
    require_once('../database/inventory_db.php');
    require_once('../database/admin_db.php');
    //print_r(get_all_admins());
?>
<header>
    <h1>NWSCC CIS Inventory</h1>
    <?php include('inc/navbar_admin.php'); ?>
</header>
<main>
    <table id="all_inventory_table">
        <tr>
            <th>Category</th>
            <th>Name</th>
            <th>Item Condition</th>
            <th>Location</th>
            <th>Description</th>
        </tr>
        <?php for( $i = 0; $i < count( $results->data ); $i++ ) : ?>
        <tr>
        <td><a href="inventory?category_id=<?php echo $results->data[$i]['categoryID']; ?>">
                    <?php echo $results->data[$i]['c_name']; ?></a>
            </td>
            <td><a href="inventory?inventory_id=<?php echo $results->data[$i]['inventoryID']; ?>">
                <?php echo $results->data[$i]['name']; ?></a>
            </td>
            <td><?php echo $results->data[$i]['itemCondition']; ?></td>
            <td><?php echo $results->data[$i]['location']; ?></td>
            <td><?php echo $results->data[$i]['description']; ?></td>
        </tr>
        <?php endfor; ?>
    </table>
    <?php echo $Paginator->createLinks( $links, 'pagination pagination-sm' ); ?>
</main>
<?php include('inc/footer.php'); ?>
