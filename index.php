<?php
    require_once('util/main.php');
    include_once('inc/header.php');
    require_once('database/category_db.php');
    require_once('database/inventory_db.php');
    require_once('inc/pagination.php');
?>
<header>
    <h1>NWSCC CIS Inventory</h1>
    <?php if (isset($_SESSION['admin'])) 
    {
        include('inc/navbar_admin.php');
    }
    else
    {
        include('inc/navbar.php');
    } ?>
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
            <td><a href="catalog?category_id=<?php echo $results->data[$i]['categoryID']; ?>">
                    <?php echo $results->data[$i]['c_name']; ?></a>
            </td>
            <td><a href="catalog?inventory_id=<?php echo $results->data[$i]['inventoryID']; ?>">
                        <?php echo $results->data[$i]['name']; ?></a></td>
            <td><?php echo $results->data[$i]['itemCondition']; ?></td>
            <td><?php echo $results->data[$i]['location']; ?></td>
            <td><?php echo $results->data[$i]['description']; ?></td>
        </tr>
        <?php endfor; ?>
    </table>
    <?php echo $Paginator->createLinks( $links, 'pagination pagination-sm' ); ?>
</main>
<?php include('inc/footer.php'); ?>
