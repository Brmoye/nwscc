<?php
    include('../../inc/header.php');
    require_once('util/secure_conn.php');
    require_once('util/valid_admin.php');
    include_once('inc/pagination.php');
    if (empty($results->data))
    {
        $results = get_all_participants();
    }
?>
<header>
    <h1>Hands Free Work Camp Participants</h1>
    <?php include('../../inc/navbar_admin.php'); ?>
</header>
<main>
    <?php if (count($results->data[0]) == 1) : ?>
        <h1><?php echo $name; ?></h1>
        <p>There are no participants to display.</p>
    <?php else : ?>
        <h1><?php echo $name; ?></h1>
        <form action="" method="post">
        <?php
        if ($group_id != NULL) {
            echo '<input type="hidden" name="group_id" value="'.$group_id.'">';
        }
        if ($colorteam_id != NULL) {
            echo '<input type="hidden" name="colorteam_id" value="'.$colorteam_id.'">';
        }
        if ($limit != NULL) {
            echo '<input type="hidden" name="limit" value="'.$limit.'">';
        }
        if ($page != NULL) {
            echo '<input type="hidden" name="page" value="'.$page.'">';
        }
        ?>
        <table id="inventory_table_by_cat">
        <tr>
            <th>Select</th>
            <th>Name</th>
            <th>Group</th>
            <th>Color Team</th>
            <th>Status</th>
            <th>Special</th>
        </tr>
        <?php for( $i = 0; $i < count( $results->data ); $i++ ) : 
            $group = get_group($results->data[$i]['group']);
            $colorteam = get_colorteam($results->data[$i]['colorteam']);
            $id = $results->data[$i]['id'];
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
        ?>
        <tr>
            <td><input type="checkbox" name="id<?php echo $i;?>" value="<?php echo $id;?>"></td>
            <td><a href="participants?action=view_participant&participant_id=<?php echo $id; ?>">
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
        <input type="radio" id="status1" name="status" value="1">
        <label for="status1">At Central</label><br>
        <input type="radio" id="status2" name="status" value="3">
        <label for="status2">At UAH</label><br>
        <input type="radio" id="status3" name="status" value="6">
        <label for="status3">At Project</label><br>
        <input type="radio" id="status4" name="status" value="5">
        <label for="status4">Heading to Central</label><br>
        <input type="radio" id="status5" name="status" value="4">
        <label for="status5">Heading to UAH</label><br>
        <input type="radio" id="status6" name="status" value="2">
        <label for="status6">Heading to Project</label><br>
        <input type="radio" id="status7" name="status" value="7">
        <label for="status7">Signed out to Parent</label><br>
        <input type="submit" value="Submit">
        </form>
        <?php echo $Paginator->createLinks( $links, 'pagination pagination-sm', $participant_id ); ?>
    <?php endif; ?>
</main>
<?php include '../../inc/footer.php'; ?>
