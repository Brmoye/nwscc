<?php
    include('../../inc/header.php');
    require_once('util/secure_conn.php');
    require_once('util/valid_admin.php');
    include_once('inc/pagination.php');
    if (empty($results->data))
    {
        $results = get_category_name($category_id);
    }
?>
<header>
    <h1>NWSCC CIS Inventory</h1>
    <?php include('../../inc/navbar_admin.php'); ?>
</header>
<main>
    <?php if (count($results->data[0]) == 1) : ?>
        <h1><?php echo $results->data[0]['name']; ?></h1>
        <p>There is no inventory for this category.</p>
    <?php else : ?>
        <h1><?php echo $results->data[0]['c_name']; ?></h1>
        <table id="inventory_table_by_cat">
            <tr>
                <th>Category</th>
                <th>Name</th>
                <th>Item Condition</th>
                <th>Location</th>
                <th>Description</th>
            </tr>
            <?php for( $i = 0; $i < count( $results->data ); $i++ ) : ?>
            <tr>
                <td><?php echo $results->data[$i]['c_name']; ?></a></td>
                <td><a href=".?inventory_id=<?php echo $results->data[$i]['inventoryID']; ?>">
                    <?php echo $results->data[$i]['name']; ?></a>
                </td>
                <td><?php echo $results->data[$i]['itemCondition']; ?></td>
                <td><?php echo $results->data[$i]['location']; ?></td>
                <td><?php echo $results->data[$i]['description']; ?></td>
            </tr>
            <?php endfor; ?>
        </table>
        <?php echo $Paginator->createLinks( $links, 'pagination pagination-sm', $category_id ); ?>
    <?php endif; ?>
</main>
<?php include '../../inc/footer.php'; ?>
