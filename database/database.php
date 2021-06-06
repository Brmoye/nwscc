<?php
$dsn = 'mysql:host=localhost;dbname=hfworkcampdb';
$username = 'rhkeeton';
$password = 'HFpw+rhk7749';
$options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);

try
{
    //$db = new PDO($dsn, $username, $password, $options);

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = mysqli_connect("p:localhost", $username, $password, "hfworkcampdb");

    /* check connection */
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
}
catch (PDOException $e)
{
    $error_message = $e->getMessage();
    include 'errors/db_error_connect.php';
    exit;
}

function display_db_error($error_message)
{
    global $app_path;
    include 'errors/db_error.php';
    exit;
}
?>
