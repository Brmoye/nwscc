<?php
    require_once('vendor/autoload.php');
    // Parse data
    $group = get_group($participants->data[0]['group']);
    $colorteam = get_colorteam($participants->data[0]['colorteam']);
    $status = get_add_status(
        $participants->data[0]['id'], 
        $participants->data[0]['group'], 
        $participants->data[0]['colorteam']);
    $lastname = $participants->data[0]['lastname'];
    $firstname = $participants->data[0]['firstname'];
    $adult = $participants->data[0]['adult'];
    $phone = $participants->data[0]['phone'];
    $email = $participants->data[0]['email'];
    $assignment = $participants->data[0]['assignment'];
    $gender = $participants->data[0]['gender'];
    $grade = $participants->data[0]['grade'];
    $age = $participants->data[0]['age'];
    $shirtsize = $participants->data[0]['shirtsize'];
    $special = $participants->data[0]['special'];
?>

<h1><?php echo htmlspecialchars($firstname.' '.$lastname); ?></h1>
<div id=participant_info>
    <label class="inv_label"><b>Group: </b></label>
    <p class="col-75"><?php echo $group; ?></p>
    <br />

    <label class="inv_label"><b>Color Team:</b></label>
    <p class="col-75"><?php echo $colorteam; ?></p>
    <br />

    <label class="inv_label"><b>Name:</b></label>
    <p class="col-75"><?php echo $firstname.' '.$lastname; ?></p>
    <br />

    <label class="inv_label"><b>Status:</b></label>
    <p class="col-75"><?php echo $status; ?></p>
    <br />

    <label class="inv_label"><b>Adult:</b></label>
    <p class="col-75"><?php echo $adult; ?></p>
    <br />

    <label class="inv_label"><b>Phone:</b></label>
    <p class="col-75"><?php echo $phone; ?></p>
    <br />

    <label class="inv_label"><b>E-Mail:</b></label>
    <p class="col-75"><?php echo $email; ?></p>
    <br />

    <label class="inv_label"><b>Assignment:</b></label>
    <p class="col-75"><?php echo $assignment; ?></p>
    <br />

    <label class="inv_label"><b>Gender:</b></label>
    <p class="col-75"><?php echo $gender; ?></p>
    <br />

    <label class="inv_label"><b>Grade:</b></label>
    <p class="col-75"><?php echo $grade; ?></p>
    <br />

    <label class="inv_label"><b>Age:</b></label>
    <p class="col-75"><?php echo $age; ?></p>
    <br />

    <label class="inv_label"><b>Shirt Size:</b></label>
    <p class="col-75"><?php echo $shirtsize; ?></p>
    <br />

    <label class="inv_label"><b>Special:</b></label>
    <p class="col-75"><?php echo $special; ?></p>
    <br />
</div>
