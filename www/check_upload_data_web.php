<?php 
    session_save_path('/var/www/html/session_data/');
    session_start();

    $img_file_addr = $_POST[image_file_web];
    $file_addr_len = strlen($_POST[image_file_web]);
    $verify1 = explode("//", $img_file_addr);

    if(($verify1[0] == 'http:' || $verify1[0] == 'https:') && $file_addr_len <= 2000)
    {
        // Use basename() function to return the base name of file
        $prefix = bin2hex(random_bytes(4));
        $file_name = './upload_data/'.$prefix.'_'.basename($img_file_addr);
        
        // Use file_get_contents() function to get the file
        // from url and use file_put_contents() function to
        // save the file by using base name
        $context = stream_context_create(array("http" => array('user_agent'=>$_SERVER['HTTP_USER_AGENT'])));//"header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:75.0) Gecko/20100101 Firefox/75.0"
        try 
        {
            file_put_contents($file_name, file_get_contents($img_file_addr, false, $context));
        }
        catch (Exception $e)
        {
            echo 'Caught exception: ', $e->getMessage(), '<br>';
            header("refresh:3; url=index.php");
        }

        if(check($file_name) && is_file($file_name) && is_readable($file_name))
        {
            //revise database
            require_once('config.php');
            $db_link = ConnectDB();
            $username = $_POST["name"];
            $sql = "UPDATE `users_info` SET `avatar_id` = '$file_name' WHERE `users_info`.`username` = '$username';";

            if (!mysqli_query($db_link, $sql))
                die(mysqli_error());
            else
            echo '<script>alert("File downloaded successfully")</script>';
        }
    }
    else if($file_addr_len > 2000)
        echo '<script>alert("Upload Failed! Your url is too large!")</script>';
    else
        echo '<script>alert("Upload Failed! Unknown reason!")</script>';

    header("refresh:0.1; url=index.php");


    function check($filename)
    {
        //verify file extension
        // $filename = basename($_FILES['image_file']['name']);
        $extension = strtolower(end(explode(".", $filename)));

        //verify image type
        if(is_file($filename) && is_readable($filename))
        {
            // echo $filename;
            // $type = exif_imagetype($filename);
            list($_, $_, $type) = getimagesize($filename);
            // $type2 = get_image_type($filename);
        }
        else 
            return false;
        
        if (!in_array($extension, ['png', 'jpeg', 'jpg']) !== false)   //verify file extension
        {
            echo "Invalid file extension: $extension.<br>";
            return false;
        }
        // else if (in_array($type2, ["image/png", "image/jpeg", "image/jpg"]) === false)   //verify file type
        // {
        //     echo "Invalid file type: " . $_SERVER["CONTENT_TYPE"]."<br>";
        //     return false;
        // }
        else if (!in_array($type ,[IMAGETYPE_JPEG, IMAGETYPE_PNG]))
        {
            echo "Invalid image type: $type.<br>";
            return false;
        }
        else
            return true;
    }
?>