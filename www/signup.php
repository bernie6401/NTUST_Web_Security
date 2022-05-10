<?php 
    session_save_path('/var/www/html/session_data');
    session_start();
?>
<html>
    <head>
        <?php include("website_head.php")?>
        <title>New Member</title>
        <link href="./css/signup.css" rel="stylesheet">
    </head>

    <body class="container container-adjust text-white bg-dark">
        <div class="form-floating form-floating-adjust padding-left-mid">
        <!-- <form>
            <input class="form-control input-adjust" id="floatingInput" type="text" name="username" id="username" placeholder="Email">
            </br>
            <input class="form-control input-adjust" id="floatingInput" type="text" name="username" id="username" placeholder="Full Name">
            </br>
            <input class="form-control input-adjust" id="floatingInput" type="text" name="username" id="username" placeholder="Username">
            </br>
            <input class="form-control input-adjust" id="floatingInput" type="password" name="password" id="password" placeholder="Password">
            </br>
            <fieldset disabled="" aria-label="Disabled fieldset example">
                <input class="form-control input-adjust" type="submit" name="submit_bt" id="submit_bt" value="Sign up" class="btn btn-primary">
            </fieldset>
        </form> -->

        <form class="row g-3 needs-validation" novalidate id="formAdd" name="formAdd" method="post">
            <div class="margin-top-div-adjust">
                <label for="validationCustom01" class="">
                    First name
                    <span class="required-star-adjust">*</span>
                </label>
                <input type="text" class="margin-top-adjust form-control" id="validationCustom01" value="" required>
                <div class="valid-feedback">
                Looks good!
                </div>
            </div>

            <div class="margin-top-div-adjust">
                <label for="validationCustom02" class="">
                    Last name
                    <span class="required-star-adjust">*</span>
                </label>
                <input type="text" class="form-control" id="validationCustom02" value="" required placeholder="Last name">
                <div class="valid-feedback">
                Looks good!
                </div>
            </div>

            <div class="margin-top-div-adjust">
                <label for="validationCustomUsername" class="">
                    Username
                    <span class="required-star-adjust">*</span>
                </label>
                <div class="input-group has-validation">
                    <span class="input-group-text" id="inputGroupPrepend">@</span>
                    <input type="text" class="form-control" id="validationCustomUsername" aria-describedby="inputGroupPrepend" required placeholder="Username">
                    <div class="invalid-feedback">
                        Please choose a username.
                    </div>
                </div>
            </div>

            <div class="margin-top-div-adjust">
                <label for="validationCustomPassword" class="">
                    Password
                    <span class="required-star-adjust">*</span>
                </label>
                <input type="password" class="margin-top-adjust form-control" id="validationCustomPassword" value="" required name="password" id="password">

                <input class="form-check-input" type="checkbox" onclick="myFunction()">
                <label class="form-check-label">
                    <p>Show Password</p>
                </label>
                <div class="valid-feedback">
                    Looks good!
                </div>
            </div>

            <div class="margin-top-div-adjust">
                <label for="validationCustomEmail" class="">
                    Email
                    <span class="required-star-adjust">*</span>
                </label>
                <input type="text" class="margin-top-adjust form-control" id="validationCustomEmail" value="" required name="username" id="username">
                <div class="valid-feedback">
                    Looks good!
                </div>
            </div>

            <div class="margin-top-div-adjust">
                <label for="validationCustom04" class="">
                    State
                    <span class="required-star-adjust">*</span>
                </label>
                <select class="form-select" id="validationCustom04" required placeholder="State">
                    <option selected disabled value="">City Choose...</option>
                    <option>Keelung City</option>
                    <option>Taipei City</option>
                    <option>New Taipei City</option>
                    <option>Taoyuan City</option>
                    <option>Hsinchu County</option>
                    <option>Hsinchu City</option>
                    <option>Miaoli County</option>
                    <option>Taichung City</option>
                    <option>Changhua Country</option>
                    <option>Nantou County</option>
                    <option>Yunlin County</option>
                    <option>Chiayi County</option>
                    <option>Chiayi City</option>
                    <option>Tainan City</option>
                    <option>Kaohsiung City</option>
                    <option>Pingtung County</option>
                    <option>Taitung County</option>
                    <option>Hualien County</option>
                    <option>Yilan County</option>
                    <option>Penghu County</option>
                    <option>Kinmen County</option>
                    <option>Lienchiang County</option>
                </select>
                <div class="invalid-feedback">
                    Please select a valid city.
                </div>
            </div>

            <div class="margin-top-div-adjust">
                <label for="validationCustom05" class="">
                    Zip
                    <span class="required-star-adjust">*</span>
                </label>
                <input type="text" class="form-control" id="validationCustom05" required>
                <div class="invalid-feedback">
                    Please provide a valid zip.
                </div>
            </div>

            <div class="margin-top-div-adjust">
                <div class="form-check">
                <input class="form-check-input" type="checkbox" id="invalidCheck" required>
                <label class="form-check-label" for="invalidCheck">
                    <p>By creating an account you agree to our <a href="./TermsPrivacy/google_terms_of_service_en.pdf" style="color:dodgerblue">Terms & Privacy</a>.</p>
                </label>
                <div class="invalid-feedback">
                    You must agree before submitting.
                </div>
                </div>
            </div>

            <div class="margin-top-div-adjust">
                <button class="float-right-adjust btn btn-primary" type="submit">Submit form</button>
            </div>
        </form>

        </div>

        <script src="./js/bootstrap.bundle.min.js"></script>
        <script src="./js/signup.js"></script>
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
        echo "<script>alert('You can\'t keep the field blank...')</script>";
?>

<script>
    function myFunction()
    {
        var x = document.getElementById("validationCustomPassword");
        if (x.type === "password")
            x.type = "text";
            
        else
            x.type = "password";
    }
</script>