<?php 
    ob_start();
    session_save_path('/var/www/html/session_data');

    require("config.php");
    // include 'style.html';
?>
<!DOCTYPE html>

<!-- detect login logic, sqli, set up cookies -->
<?php
    if((!isset($_POST['username']) || !isset($_POST['password']) || $_POST['username']=="" || $_POST['password']=="") && isset($_POST['submit']))
        header("Location: index.php");

    $username = $_POST['username'];
    $password = $_POST['password'];

    //detect if sqli or not
    require_once('sqli_filter.php');
    $sqli_user = sqli_detect_signup($username);

    if ($sqli_user === true)
    {
        echo "You such a hacker !!!";
        header("refresh:3; url=index.php");
    }
    else
    { 
        $db_link = ConnectDB();
        $sql = "SELECT * FROM `users_info` WHERE `username` = '$username';";
        $result = mysqli_query($db_link, $sql);
        $row_result = mysqli_fetch_assoc($result);

        try 
        {
            if(!password_verify($password, $row_result['password']) && !isset($_POST['send_submit']) && !isset($_POST['change_title']))// && $_COOKIE['user_name'] != $username
            {
                echo '<div class="flex-center">Wrong username or password.</br>Please check it out again</div>';
                logout("user_name");
            }
            //set up cookies
            else if(!isset($_POST['send_submit']) && !isset($_POST['change_title']))// && $_COOKIE['user_name'] == $username
            {
                // echo "\"";
                setcookie("user_name", $username, time()+3600);
                // echo $_COOKIE["user_name"];
                // echo "\"";
            }
            else if(isset($_POST['change_title']))
            {
                echo '<div class="flex-center">Wait for redirection to index page!</div>';
            }
        }
        catch (Exception $e)
        {
            echo 'Caught exception: ', $e->getMessage(), '<br>';
            echo 'Check credentials in config file at: ', $Mysql_config_location, '\n';
        }
    }
    
?>

<!-- design the web including user profile and blog -->
<html>
    <head>
        <?php
            include("website_head.php");
            $db_link = ConnectDB();
            $sql_title_name = "SELECT * FROM `page_title`;";
            $result_title_name = mysqli_query($db_link, $sql_title_name);
            $row_result_title_name = mysqli_fetch_assoc($result_title_name);
            $title = $row_result_title_name['title_name'];
            echo "<title>".$title."</title>";
        ?>
        <link href="./css/login.css" rel="stylesheet">
    </head>
    <body class="container container-adjust text-white bg-dark">
        <?php
            if (password_verify($password, $row_result['password']))
            {
                $result = mysqli_query($db_link, $sql); //u must add this line to execute the function
                $row_result = mysqli_fetch_assoc($result);

                //design header
                echo '
                <header>
                    <div class="mb-bottom-adjust">
                        <h3 class="float-md-start mb-0" href="index.php">Edit</h3>
                        <nav class="nav nav-masthead justify-content-center float-md-end">
                            <a class="nav-link" href="logout.php">Logout</a>
                            <a class="nav-link" href="signup.php">Register</a>
                            <a class="nav-link" href="board.php">Board</a>
                        </nav>
                    </div>
                </header>';

                //design User Profile        
                echo "
                    <img src=".$row_result[avatar_id]." width='100' class='img-circle mb-top-adjust' alt='You should not upload ilegal img' title='Your avatar icon'>
                    <div class='mb-3'>
                        <form action='check_upload_data.php' method='POST' enctype='multipart/form-data'>
                            <a>From Local File</a>
                            <input type='hidden' name='name' value=".$username.">
                            <input class='form-control' type='file' name='image_file' value='Browse'>
                            <input class='btn btn-primary' type='submit' name='image_file' value='Upload'>
                        </form>
                    </div>
                    <div class='mb-3'>
                        <form action='check_upload_data_web.php' method='POST' enctype='multipart/form-data'>
                            <a>From Web</a>
                            <input class='form-control' type='text' name='image_file_web'>
                            <input type='hidden' name='name' value=".$username.">
                        </form>
                    </div>";
                    
                if(password_verify($password, $row_result['password']) && $row_result['username'] == 'sbkadm')
                {
                    echo '<div class="mb-3">Change title</div>';
                    echo "
                    <form method='POST'>
                        <input class='form-control' type='text' name='change_title'>
                    </form>";
                }
                
                //add post icon
                echo '
                <footer>
                    <a class="add-post-btn" href="post.php?username='.$row_result['username'].'">+</a>
                </footer>';
                
            }
        ?>
    </body>
</html>

<!-- design submit login about comment blog -->
<?php
    if(isset($_POST['change_title']))
    {
        $db_link = ConnectDB();
        $title_name = $_POST["change_title"];
        $sql = "UPDATE `page_title` SET `title_name` = '$title_name';";
        mysqli_query($db_link, $sql);
        
        header("refresh:2; url=index.php");
    }
?>
<?ob_end_flush();?>