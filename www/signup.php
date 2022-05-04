<?php 
    ob_start();
    session_save_path('/var/www/html/session_data');
    session_start();
    include 'style.html';
?>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>New Member Sign up</title>
    </head>

    <body>
        <form id="formAdd" name="formAdd" method="post" action="">
            <table width="300" border="0" align="center" cellpadding="5" cellspacing="0" bgcolor="#F2F2F2">
                <tr>
                    <div class="content">
                        <td colspan="2" align="center" bgcolor="#CCCCCC"><font color="#000000">Member Data</font></td>
                </tr>
                <tr>
                    <td width="80" align="center" valign="baseline">Username</td>
                    <td valign="baseline">
                        <input type="text" name="username" id="username" value=""></td>
                </tr>
                <tr>
                    <td width="80" align="center" valign="baseline">Password</td>
                    <td valign="baseline">
                        <input type="password" name="password" id="password" value=""></td>
                </tr>
                <tr>
                    <td colspan="2" align="center" bgcolor="#CCCCCC">
                        <input type="reset" name="reset_bt" id="reset_bt" value="Reset">
                        <input type="submit" name="submit_bt" id="submit_bt" value="Create"></td>          
                </tr>
            </table>
        </form>
    </body>
</html>

<?php
    if(isset($_POST['username']) && isset($_POST['password']) && $_POST['username']!="" && $_POST['password']!="")
    {
        //must check the username include sqli words or not
        require_once('sqli_filter.php');
        $sqli_username_detect = sqli_detect_signup($_POST['username']);
        if($sqli_username_detect === true)
        {
            echo 'You can not use the special character.';
        }
        else
        {
            include("check_signup.php");
            try 
            {
                if($row)
                {
                    echo 'Oops, you signed up before, please login at home page...<br><br>';
                    mysqli_close($db_link);
                    header("refresh:3; url=index.php");
                }
                else
                {
                    //get the post parameter and prepare
                    // $password = $_POST["password"];
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $sql_query_insert = "INSERT INTO `users_info` (`id`, `username`, `password`, `avatar_id`) VALUES ('$id', '$username', '$password', './upload_data/default_avatar.jpg')";
            
                    //insert new data to db
                    mysqli_query($db_link, $sql_query_insert);
                    mysqli_close($db_link);

                    //put data to session folder
                    $_SESSION['id'] = $id;
                    $_SESSION['username'] = $username;
                    $_SESSION['password'] = $password;

                    //head to home page
                    echo 'Sign up successful. Please wait 3s for redirection.<br><br>';
                    header("refresh:3; url=index.php");
                }
            }
            catch (Exception $e)
            {
                echo 'Caught exception: ', $e->getMessage(), '<br>';
                echo 'Check credentials in config file at: ', $Mysql_config_location, '\n';
            }
        }
    }

    else if(isset($_POST['username']) && isset($_POST['password']) && ($_POST['username']=="" || $_POST['password']==""))
        echo "You can't keep the field blank...<br><br>";
?>
<?ob_end_flush();?>