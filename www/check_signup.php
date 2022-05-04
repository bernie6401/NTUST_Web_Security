<?php
    //before insert the data, must verify the user has signed up before
    include("config.php");
    $db_link = ConnectDB();


    //verify username
    $username = $_POST["username"];
    $sql_query_select = "SELECT * FROM `users_info` WHERE `username` = '$username';";
    $select_result = mysqli_query($db_link, $sql_query_select);
    $row = mysqli_fetch_array($select_result);
    

    //verify user id
    $sql_query_select = "SELECT * FROM `users_info`;";
    $select_result = mysqli_query($db_link, $sql_query_select);
    $total_records = mysqli_num_rows($select_result);
    $id = $total_records + 1;   //use (the total member quantity + 1) as its id
    do
    {
        $sql_query_select = "SELECT * FROM `users_info` WHERE `id` = '$id';";
        $select_result = mysqli_query($db_link, $sql_query_select);
        $id_repeat = mysqli_fetch_array($select_result);
        if($id_repeat)  //check if id repeat or not
            $id++;
    }while($id_repeat);
    
?>