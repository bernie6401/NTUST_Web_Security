<?php
    function ConnectDB()
    {
        //this file aim to connet to mysql db

        define('DB_SERVER', 'db');
        define('DB_USERNAME', 'sbk');
        define('DB_PASSWORD', 'TheQueensGambit');
        define('DB_NAME', 'user_info');
        
        $db_link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
        if($db_link === false)
        {
            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
        
        mysqli_query($db_link, "SET NAMES 'utf8'"); //set database encoding as utf8
        return $db_link;
    }


    //sessions are always turned on
    function sess_init()
    {
        session_set_cookie_params(3600, '/');
        // session_start();
    }
    
    //logout and clean session
    function logout($session_name)
    {
        CleanSession();
        setcookie($session_name, '', time()-3600);//clear cookie
        header("refresh:3; url=index.php");
        // exit;
    }

    function ForceLogin()
    {
        if(isset($_SESSION['user_name']))
        {
            //user is allowed to login
            echo "session success";
        }
        else
        {
            //the user is not allowed here
            header("Location: login.php");
            exit;
        }
    }

    function Forceboard()
    {
        if(isset($_SESSION['user_name']))
        {
            header("Location: board.php");
            exit;
        }
        else
        {
            //the user should login
            echo "session success";
        }
    }

    function CleanSession()
    {
		// remove all session variables
		if(isset($_SESSION)){
			session_unset(); 
		}
	}
?>