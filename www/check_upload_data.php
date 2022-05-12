<?php 
    session_save_path('/var/www/html/session_data/');
    session_start();
    
    $redirect_addr = "index.php";
    //verify file extension
    $filename = basename($_FILES['image_file']['name']);
    $extension = strtolower(end(explode(".", $filename)));
    $prefix = bin2hex(random_bytes(4));
    $filename = str_replace(" ", "_", $filename);
    $filename = './upload_data/'.$prefix.'_'.$filename;

    //verify image type
    if(isset($_FILES['image_file']) && !is_readable($_FILES['image_file']['tmp_name']))
    {
        echo '<script>alert("You can not upload files which size over 1M!")</script>';
        header("refresh:1; url=$redirect_addr");
    }
    else
        list($_, $_, $type) = getimagesize($_FILES['image_file']['tmp_name']);

    
    if (!isset($_FILES['image_file']))
    {
        echo '<script>alert("Give me a file!")</script>';
        header("refresh:0.1; url=$redirect_addr");
    }
    else if (!in_array($extension, ['png', 'jpeg', 'jpg']) !== false)   //verify file extension
    {
        echo '<script>alert("Invalid file type1")</script>';
        header("refresh:0.1; url=$redirect_addr");
    }
    else if (in_array($_FILES['image_file']['type'], ["image/png", "image/jpeg", "image/jpg"]) === false)   //verify file type
    {
        echo '<script>alert("Invalid file type2")</script>';
        header("refresh:0.1; url=$redirect_addr");
    }
    else if (in_array(mime_content_type($_FILES["image_file"]["tmp_name"]), ["image/png", "image/jpeg", "image/jpg"]) === false)   //verify file type
    {
        echo '<script>alert("Invalid file type3")</script>';
        header("refresh:0.1; url=$redirect_addr");
    }
    else if ($type !== IMAGETYPE_JPEG && $type !== IMAGETYPE_PNG)
    {
        echo '<script>alert("Invalid file type4")</script>';
        header("refresh:0.1; url=$redirect_addr");
    }
    else if(($type == IMAGETYPE_PNG || 
            mime_content_type($_FILES["image_file"]["tmp_name"]) == "image/png" || 
            $_FILES['image_file']['type'] == "image/png") && 
            !imagecreatefrompng($_FILES['image_file']['tmp_name']))
    {
        echo '<script>alert("Invalid file type5")</script>';
        header("refresh:0.1; url=$redirect_addr");
    }
    else if(($type == IMAGETYPE_JPEG || 
            in_array(mime_content_type($_FILES["image_file"]["tmp_name"]), ["image/jpeg", "image/jpg"]) === true || 
            in_array($_FILES['image_file']['type'], ["image/jpeg", "image/jpg"]) === true) && 
            !imagecreatefromjpeg($_FILES['image_file']['tmp_name']))
    {
        echo '<script>alert("Invalid file type6")</script>';
        header("refresh:0.1; url=$redirect_addr");
    }
    else if ($_FILES['image_file']['error'] === UPLOAD_ERR_OK)
    {
        // echo 'File Name: ' . $_FILES['image_file']['name'] . '<br/>';
        // echo 'File Type: ' . $_FILES['image_file']['type'] . '<br/>';
        // echo 'File Size: ' . ($_FILES['image_file']['size'] / 1024) . ' KB<br/>';
        // echo 'Tmp Name: ' . $_FILES['image_file']['tmp_name'] . '<br/>';
        // echo mime_content_type($_FILES["image_file"]["tmp_name"]);

        
        if(move_uploaded_file($_FILES['image_file']['tmp_name'], $filename))
        {
            echo '<script>alert("Upload Success.")</script>';

            //revise database
            require_once('config.php');
            $db_link = ConnectDB();
            $username = $_POST["name"];
            $sql = "UPDATE `users_info` SET `avatar_id` = '$filename' WHERE `users_info`.`username` = '$username';";
            mysqli_query($db_link, $sql);
            header("refresh:0.1; url=board.php");
        }
        else
            echo '<script>alert("Upload Failed!")</script>';
        header("refresh:0.1; url=$redirect_addr");
    }
    else echo 'Error code：' . $_FILES['my_file']['error'] . '<br/>';
?>