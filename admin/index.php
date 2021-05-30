<?php
    require_once('../util/main.php');
    require_once('../util/secure_conn.php');
    require_once('../util/valid_admin.php');
    include('inc/header.php');
    require_once('inc/pagination.php');
    require_once('../database/database.php');
    //require_once('../database/category_db.php');
    require_once('../database/participant_db.php');
    require_once('../database/admin_db.php');
    //print_r(get_all_admins());
?>
<header>
    <h1>Hands Free Work Camp</h1>
    <?php include('inc/navbar_admin.php'); ?>
</header>
<?php if (isset($_SESSION['admin'])) 
    { ?>
<main>
    <table id="all_inventory_table">
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
            <td><a href="participants?participant_id=<?php echo $results->data[$i]['id']; ?>">
                <?php echo $lastname.', '.$firstname; ?></td>
            <td><a href="group?group_id=<?php echo $results->data[$i]['group']; ?>">
                <?php echo $group; ?></a>
            </td>
            <td><a href="colorteam?colorteam_id=<?php echo $results->data[$i]['colorteam']; ?>">
                    <?php echo $colorteam; ?></a></td>
            <td><?php echo $status; ?></td>
            <td><?php echo $special; ?></td>
        </tr>
        <?php endfor; ?>
    </table>
    <?php echo $Paginator->createLinks( $links, 'pagination pagination-sm' ); ?>
</main>
<?php
    } //isset admin 
    else {
?>
<main>Login</main>
<?php 
    }
include('inc/footer.php'); ?>
