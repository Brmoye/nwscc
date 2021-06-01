<?php
    require_once('../util/main.php');
    require_once('../util/secure_conn.php');
    require_once('../util/valid_admin.php');
    include('inc/header.php');
    require_once('inc/pagination.php');
    require_once('../database/database.php');
    require_once('../database/participants_db.php');
    require_once('../database/admin_db.php');
?>
<header>
    <?php include('inc/navbar_admin.php'); ?>
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
            $id = $results->data[$i]['id'];
            $group = get_group($results->data[$i]['group']);
            $my_colorteam_id=$results->data[$i]['colorteam'];
            $colorteam = "";
            $status_id = $results->data[$i]['statusID'];
            $status = get_add_status(
                $id,
                $results->data[$i]['group'],
                $results->data[$i]['colorteam']);
            $lastname = $results->data[$i]['lastname'];
            $firstname = $results->data[$i]['firstname'];
            $special = $results->data[$i]['special'];

            if ($special == "Allergies, dietary needs, physical needs, etc.") {
                $special = "";
            }

            $colorteam_link = "";
            if ($my_colorteam_id != NULL && $my_colorteam_id != "") {
                $colorteam = get_colorteam($my_colorteam_id);
                $colorteam_link = '<a href="?colorteam_id='.$my_colorteam_id.'">'.$colorteam.'</a>';
            } else {
                $colorteam = "none";
                $colorteam_link = $colorteam;
            }
        ?>
        <tr>
            <td><a href="participants?action=view_participant&participant_id=<?php echo $id; ?>">
                <?php echo $lastname.', '.$firstname; ?></td>
            <td><a href="participants?group_id=<?php echo $results->data[$i]['group']; ?>&name=<?php echo htmlspecialchars($group); ?>">
                <?php echo $group; ?></a>
            </td>
            <td><?php echo $colorteam_link; ?></td>
            <td <?php echo set_status_color($status_id);?>><?php echo $status; ?></td>
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
