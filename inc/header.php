<!DOCTYPE html>
<html>
<head>
    <title>HF Check-in</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="<?php echo $app_path ?>css/style.css" />
    <link rel="icon" type="image" href="<?php echo $app_path ?>images/rel_icon.png" />
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $app_path ?>images/apple-touch-icon3.png">
    <link rel="apple-touch-icon" href="<?php echo $app_path ?>images/apple-touch-icon2.png">
    <link rel="shortcut icon" href="<?php echo $app_path ?>images/apple-touch-icon2.ico" type="image/x-icon"/>
    <a href="<?php echo $app_path ?>index.php">
        <img class="header_img" src="<?php echo $app_path ?>images/WEBworkcampbanner-2.png" alt="Hands Free Logo">
    </a>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

    <script type="text/javascript">
    function toggle(source) {
        var checkboxes = document.querySelectorAll('input[class="chkStatus"]');
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i] != source)
                checkboxes[i].checked = source.checked;
    }
}
</script>
</head>
<body>
