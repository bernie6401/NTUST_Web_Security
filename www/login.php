<?php 
    ob_start();
    session_save_path('/var/www/html/session_data');

    require("config.php");
    // Forceboard();

    include 'style.html';
?>
<!DOCTYPE html>

<!-- detect login logic, sqli, set up cookies -->
<?php
    if((!isset($_POST['username']) || !isset($_POST['password']) || $_POST['username']=="" || $_POST['password']=="") && isset($_POST['submit']))
        header("Location: index.html");

    $username = $_POST['username'];
    $password = $_POST['password'];

    //detect if sqli or not
    require_once('sqli_filter.php');
    $sqli_user = sqli_detect_signup($username);

    if ($sqli_user === true)
    {
        echo "You such a hacker !!!";
        header("refresh:3; url=index.html");
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
        <meta charset="UTF-8">
        <meta name="description" content="The homework of NTUSTWS lecture." />
        <meta name="author" content="SBK" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <?php 
            $db_link = ConnectDB();
            $sql_title_name = "SELECT * FROM `page_title`;";
            $result_title_name = mysqli_query($db_link, $sql_title_name);
            $row_result_title_name = mysqli_fetch_assoc($result_title_name);
            $title = $row_result_title_name['title_name'];
            echo "<title>".$title."</title>";
        ?>
    </head>
    <body>
        <?php
            if (password_verify($password, $row_result['password']))// || $_COOKIE["user_name"] == $username
            {
                //design User Profile
                echo '
                <h1 align = "center">User Profile</h1>
                <table border="1" align = "center">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Upload(IMG or Web)</th>
                        <th>Avatar</th>';
                        if(password_verify($password, $row_result['password']) && $row_result['username'] == 'sbkadm')
                        echo '<th>Change title</th>';
                    echo '
                    </tr>';
            
                    $result = mysqli_query($db_link, $sql); //u must add this line to execute the function
                    $row_result = mysqli_fetch_assoc($result);
                    echo "<tr>";
                    echo "<td>".$row_result['id']."</td>";
                    echo "<td>".$row_result['username']."</td>";
                    echo "
                        <td>
                            <p>Must less than 2M</p></br>
                            <form action='check_upload_data.php' method='POST' enctype='multipart/form-data'>
                                <input type='hidden' name='name' value=".$username.">
                                <input type='file' name='image_file' value='Browse'>
                                <input type='submit' name='image_file' value='Upload'>
                            </form>
                            <form action='check_upload_data_web.php' method='POST' enctype='multipart/form-data'>
                                </br></br>
                                <a>From Web：</a><input type='text' name='image_file_web'>
                                <input type='hidden' name='name' value=".$username.">
                            </form>
                        </td>";
                    echo "<td><img src=".$row_result[avatar_id]." width='100'></td>";
                    if(password_verify($password, $row_result['password']) && $row_result['username'] == 'sbkadm')
                    echo "
                        <form method='POST'>
                            <td><input type='text' name='change_title'></td>
                        </form>";
                    echo "</tr>";
                echo "</table>";


                //design comment blog
                echo '
                <br/><br/><hr/><br/><br/>

                <div class="top-right home">
                    <a href="logout.php">Logout</a>
                    <a href="signup.php">Register</a>
                    <a href="board.php?username='.$row_result['username'].'">Board</a>
                </div>
            
                <div class="m-b-md content" style="padding-left: 30px; padding-top: 15px; margin-left: 170px; padding-right: 20px;">
                    <form name="form1" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="name" value='.$username.'>
                        <p><strong>Hi, there. Write your post below.</strong></p>
                        <p>Title(Max:200)</p><input type="text" name="subject" class="blog_title">
                        <p>Content(Max:2000)</p><textarea class=textarea type="" name="content"></textarea></br>
                        <input type="file" name="attach_file_submit" value="Attach File">
                        <input type="submit" name="send_submit" value="SEND">
                        <input type="reset" name="Reset" value="RESET">
                    </form>
                </div>';
            }
        ?>
    </body>
</html>

<!-- design submit login about comment blog -->
<?php
    if (isset($_POST['send_submit']) && $_POST['subject']!="" && $_POST['content']!="")
    {
        //upload attach file
        if(isset($_FILES['attach_file_submit']) && is_readable($_FILES['attach_file_submit']['tmp_name']))
        {
            $prefix = bin2hex(random_bytes(4));
            $attach_file_addr = $_FILES['attach_file_submit']["name"];
            $attach_file_addr = str_replace("_", " ", $attach_file_addr);
            $attach_file_addr = "../upload_attach/".$prefix."_".$attach_file_addr;
            if(move_uploaded_file($_FILES['attach_file_submit']['tmp_name'], $attach_file_addr))
                echo '<div class="success">Upload Attachment Success!</br></div>';
        }
        // else if(isset($_FILES['attach_file_submit']) && !is_readable($_FILES['attach_file_submit']['tmp_name']))
        //     echo '<div class="success">Upload Attachment Unsuccess!</br>You can not upload the file size > 2M!</div>';
        else
            $attach_file_addr = '';
        

        $subject = $_POST['subject'];
        $content = $_POST['content'];
        $username = $_POST['name'];

        //sqli detection
        $subject = sqli_detect_blog($subject);
        $content = sqli_detect_blog($content);

        //create post id
        require_once('check_post_id.php');
        $post_id = check_post_id($db_link);

        $sql = "INSERT into `users_blog`(`post_id`, `user_name`, `blog_title`, `blog_content`, `post_time`, `attach_file_addr`) VALUES ('$post_id', '$username', '$subject', '$content', now(), '$attach_file_addr');";

        if (!mysqli_query($db_link, $sql))
        {
            die(mysqli_error());
        }
        else
        {
            //若成功將留言存進資料庫，會自動跳轉到顯示留言的頁面
            // echo"
            //     <script>
            //     setTimeout(function(){window.location.href='board.php?username=".$username."';},500);
            //     </script>";
            echo '<div class="success">Added successfully !</br>Wait for redirection !</div>';
            header("refresh:2; url=board.php?username=$username");
        }
    }
    else if(password_verify($password, $row_result['password']))
    {
        echo '<div class="success">Click <strong>Send</strong> when you\'re done.</div>';
    }
    else if($_POST['subject']!="" || $_POST['content']!="")
    {
        echo '<div class="flex-center">You can not let the field blank!</div>';
        header("refresh:2; url=index.html");
    }

    if(isset($_POST['change_title']))
    {
        $db_link = ConnectDB();
        $title_name = $_POST["change_title"];
        $sql = "UPDATE `page_title` SET `title_name` = '$title_name';";
        mysqli_query($db_link, $sql);
        
        header("refresh:2; url=index.html");
    }
?>
<?ob_end_flush();?>