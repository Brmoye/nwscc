<?php
    require_once('util/main.php');
    include_once('inc/header.php');
    //require_once('database/category_db.php');
    require_once('database/participants_db.php');
    require_once('inc/pagination.php');
?>
<header>
    <h1>Hands Free Work Camp Participants</h1>
    <?php if (isset($_SESSION['admin'])) 
    {
        include('inc/navbar_admin.php');
    }
    else
    {
        include('inc/navbar.php');
    } ?>
</header>
<?php if (isset($_SESSION['admin'])) 
    { ?>
<main>
    <table id="all_participants_table">
        <tr>
            <th>Name</th>
            <th>Group</th>
            <th>Color Team</th>
            <th>Status</th>
            <th>Special</th>
        </tr>
        <?php for( $i = 0; $i < count( $results->data ); $i++ ) : 
            $group = get_group($results->data[$i]['group']);
            $colorteam = get_colorteam($results->data[$i]['colorteam']);
            $status = get_add_status(
                $results->data[$i]['id'], 
                $results->data[$i]['group'], 
                $results->data[$i]['colorteam']);
            $lastname = $results->data[$i]['lastname'];
            $firstname = $results->data[$i]['firstname'];
            $special = $results->data[$i]['special'];

            if ($special == "Allergies, dietary needs, physical needs, etc.") {
                $special = "";
            }
        ?>

        <tr>
            <td><?php echo $lastname.', '.$firstname; ?></td>
            <td><a href="catalog?group_id=<?php echo $results->data[$i]['group']; ?>">
                    <?php echo $group; ?></a>
            </td>
            <td><a href="catalog?colorteam_id=<?php echo $results->data[$i]['colorteam']; ?>">
                        <?php echo $colorteam; ?></a></td>
            <td><?php echo $status; ?></td>
            <td><?php echo $special; ?></td>
        </tr>
        <?php endfor; ?>
    </table>
    <?php echo $Paginator->createLinks( $links, 'pagination pagination-sm' ); ?>
</main>
<?php
    } //isset admin ?>
<?php include('inc/footer.php'); ?>
