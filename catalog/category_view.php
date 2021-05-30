<?php 
    include('../inc/header.php'); 
    require_once('inc/pagination.php');

    if (empty($results->data))
    {
        $results = get_category_name($category_id);
    }
?>
<header>
    <h1>Hands Free Work Camp Participants</h1>
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
    <?php if (count($results->data[0]) == 1) : ?>
        <h1><?php echo $results->data[0]['name']; ?></h1>
        <p>There is no inventory in this category.</p>
    <?php else: ?>
        <h1><?php echo $results->data[0]['c_name']; ?></h1>
        <table id="inventory_table_by_cat">
            <tr>
                <th>Category</th>
                <th>Name</th>
                <th>Item Condition</th>
                <th>Description</th>
                <th>Location</th>
            </tr>
            <?php for( $i = 0; $i < count( $results->data ); $i++ ) : ?>
            <tr>
                <td><?php echo $results->data[$i]['c_name']; ?></td>
                <td><a href="<?php echo $app_path; ?>catalog?inventory_id=<?php echo $results->data[$i]['inventoryID']; ?>">
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
<?php include('../inc/footer.php'); ?>