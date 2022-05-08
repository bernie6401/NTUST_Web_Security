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
                <nav class="navbar-expand-lg navbar navbar-dark bg-primary mt-2 border-adjust" style="background-color: rgba(111, 183, 255, 0.473)!important">
                    <div class="container-fluid">
                        <h3 class="navbar-brand text-white" href="index.php">Edit Avatar</h3>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <li class="nav-item">
                                    <a class="nav-link active" href="signup.php">Register</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" href="logout.php">Logout</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" href="board.php">Board</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>';

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
                    echo '
                    <div class="mb-3">Change title';
                    echo "
                        <form method='POST'>
                            <input class='form-control' type='text' name='change_title'>
                        </form>
                    </div>";
                }
                
                //add post icon
                echo '
                <footer>
                    <a class="add-post-btn" href="post.php?username='.$row_result['username'].'">+</a>
                </footer>';

            }
        ?>

        <script src="./js/bootstrap.bundle.min.js"></script>
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