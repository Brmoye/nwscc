<?php
    require_once('../util/main.php');
    require_once('header.php');

    global $conn;
?>
<header>
    <?php if (isset($_SESSION['admin']))
    {
        include('inc/navbar_admin.php');
    }
    else
    {
        include('inc/navbar.php');
    } ?>
</header>
<main>
    <?php
        // Process search form when submitted
        if (isset($_POST['search']))
        {
            // Search
            require "search_2.php";

            // Display results
            if ($results->fetch_assoc())
            {
                foreach ($results as $result)
                {
                    if (isset($_SESSION['admin']))
                    {
                        printf("<div><a href='../admin/participants/?participant_id=%s'>%s</a></div>",
                            $result['id'], $result['lastname'].', '. $result['firstname']);
                    }
                }
            } else
            {
                echo "No results found";
            }
        }
    ?>
</main>
<?php require_once('footer.php'); ?>