<?php
    include('../../inc/header.php');
    require_once('util/secure_conn.php');
    require_once('util/valid_admin.php');
    include_once('inc/pagination.php');
    if (empty($results->data))
    {
        $results = get_all_participants();
    }
    if ($group_id != NULL) {
        $full_group = get_full_group($group_id);
        $display_name = get_group($group_id);
    } else if ($colorteam_id != NULL) {
        $display_name = get_colorteam($colorteam_id);
    }
?>
<header>
    <?php include('../../inc/navbar_admin.php'); ?>
</header>
<main>
    <?php if (count($results->data[0]) == 1) : ?>
        <h1><?php echo $display_name; ?></h1>
        <p>There are no participants to display.</p>
    <?php else : ?>
        <h1><?php echo $display_name; ?></h1>
        <form action="" method="post">
        <?php
        if ($group_id != NULL) {
            echo '<input type="hidden" name="group_id" value="'.$group_id.'">
            ';
            echo '<h2>Group Lead: '.
                $full_group->data[0]['contactname'].
                ' '.$full_group->data[0]['contactphone'].'</h2>';
        }
        if ($colorteam_id != NULL) {
            echo '<input type="hidden" name="colorteam_id" value="'.$colorteam_id.'">
            ';
            $team_lead = get_colorteam_lead($colorteam_id);
            echo '<h2>Team Lead: '.
                $team_lead->data[0]['firstname'].' '.$team_lead->data[0]['lastname'].
                ' '.$team_lead->data[0]['phone'].'</h2>';
        }
        if ($limit != NULL) {
            echo '<input type="hidden" name="limit" value="'.$limit.'">
            ';
        }
        if ($page != NULL) {
            echo '<input type="hidden" name="page" value="'.$page.'">
            ';
        }
        if ($group_id != NULL || $colorteam_id != NULL) {
            ?>
            <input type="checkbox" id="selectAll" name="selectAll">
            <label for="selectAll">Configure all Participants in group/team</label>
            <?php
        }
        ?>
        <br>
        <table id="inventory_table_by_cat">
        <tr>
            <th>Select <input type="checkbox" onclick="toggle(this);"></th>
            <th>Name</th>
            <th>Group</th>
            <th>Color Team</th>
            <th>Status</th>
            <th>Special</th>
        </tr>
        <?php for( $i = 0; $i < count( $results->data ); $i++ ) :
            $group = get_group($results->data[$i]['group']);
            $my_colorteam_id = $results->data[$i]['colorteam'];
            $colorteam = "";
            $id = $results->data[$i]['id'];
            $status_id = $results->data[$i]['statusID'];
            $status = get_add_status(
                $id,
                $results->data[$i]['group'],
                $my_colorteam_id);
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
            <td><input type="checkbox" class="chkStatus" name="id<?php echo $i;?>" value="<?php echo $id;?>"></td>
            <td><a href="?action=view_participant&participant_id=<?php echo $id; ?>">
                <?php echo $lastname.', '.$firstname; ?></td>
            <td><a href="?group_id=<?php echo $results->data[$i]['group']; ?>">
                <?php echo $group; ?></a>
            </td>
            <td><?php echo $colorteam_link; ?></td>
            <td <?php echo set_status_color($status_id);?>><?php echo $status; ?></td>
            <td><?php echo $special; ?></td>
        </tr>
        <?php endfor; ?>
        </table>
        <input type="radio" id="status1" name="status" value="1">
        <label <?php echo set_status_color(1);?> for="status1">At Central</label><br>
        <input type="radio" id="status2" name="status" value="3">
        <label <?php echo set_status_color(3);?> for="status2">At UAH</label><br>
        <input type="radio" id="status3" name="status" value="6">
        <label <?php echo set_status_color(6);?> for="status3">At Project</label><br>
        <input type="radio" id="status4" name="status" value="5">
        <label <?php echo set_status_color(5);?> for="status4">Heading to Central</label><br>
        <input type="radio" id="status5" name="status" value="4">
        <label <?php echo set_status_color(4);?> for="status5">Heading to UAH</label><br>
        <input type="radio" id="status6" name="status" value="2">
        <label <?php echo set_status_color(2);?> for="status6">Heading to Project</label><br>
        <input type="radio" id="status7" name="status" value="7">
        <label <?php echo set_status_color(7);?> for="status7">Signed out to Parent</label><br>
        <br><input type="submit" value="Submit">
        </form>
        <?php echo $Paginator->createLinks( $links, 'pagination pagination-sm', $view_id, $view_name ); ?>
    <?php endif; ?>
</main>
<?php include '../../inc/footer.php'; ?>
