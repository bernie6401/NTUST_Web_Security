<?php 
    session_save_path('/var/www/html/session_data');
    session_start();
?>
<html>
    <head>
        <?php include("website_head.php")?>
        <title>New Member</title>
        <link href="./css/signup.css" rel="stylesheet">

        <!-- import recaptcha JS -->
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <!-- <script src="https://www.google.com/recaptcha/api.js?render=6LeS0t0fAAAAAL2MDlvjEdl-VoNh-1mH_frVp0Ks" async="async" defer="defer"></script> -->
    </head>

    <body class="container container-adjust text-white bg-dark">
        <!-- Normal js file import -->
        <script src="./js/bootstrap.bundle.min.js"></script>
        <script src="./js/signup.js"></script>

        <!-- main signup part -->
        <div class="form-floating form-floating-adjust padding-left-mid">
            <form class="row g-3 needs-validation" novalidate id="formAdd" name="formAdd" method="post">
                <div class="margin-top-div-adjust">
                    <label for="validationCustom01">
                        First name
                        <span class="required-star-adjust">*</span>
                    </label>
                    <input type="text" class="margin-top-adjust form-control" id="validationCustom01" required>
                    <div class="valid-feedback">
                    Looks good!
                    </div>
                </div> 

                <div class="margin-top-div-adjust">
                    <label for="validationCustom02">
                        Last name
                        <span class="required-star-adjust">*</span>
                    </label>
                    <input type="text" class="form-control" id="validationCustom02" required>
                    <div class="valid-feedback">
                    Looks good!
                    </div>
                </div>

                <div class="margin-top-div-adjust">
                    <label for="validationCustomUsername">
                        Username
                        <span class="required-star-adjust">*</span>
                    </label>
                    <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                        <input class="form-control" id="validationCustomUsername" type="text" name="username" aria-describedby="inputGroupPrepend" required>
                        <div class="invalid-feedback">
                            Please choose a username.
                        </div>
                    </div>
                </div>

                <div class="margin-top-div-adjust">
                    <label for="validationCustomPassword">
                        Password
                        <span class="required-star-adjust">*</span>
                    </label>
                    <input class="margin-top-adjust form-control" id="validationCustomPassword" type="password" name="password" required>
                    
                    <input class="form-check-input" type="checkbox" onclick="myFunction()">
                    <label class="form-check-label">
                        <p>Show Password</p>
                    </label>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                </div>

                <div class="margin-top-div-adjust">
                    <label for="validationCustomEmail">
                        Email
                        <span class="required-star-adjust">*</span>
                    </label>
                    <input type="text" class="margin-top-adjust form-control" id="validationCustomEmail" required name="email">
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                </div>

                <div class="margin-top-div-adjust">
                    <label for="validationCustom04">
                        State
                        <span class="required-star-adjust">*</span>
                    </label>
                    <select class="form-select" id="validationCustom04" required placeholder="State">
                        <option selected disabled>City Choose...</option>
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


                <!-- submit button -->
                <div class="margin-top-div-adjust">
                    <div
                        class="g-recaptcha"
                        data-sitekey="6LeHJ9ofAAAAADeUSMkMaReVjurI3nxSzGL6iWZy"
                        data-theme="light" data-size="normal"
                        data-callback="verifyCallback"
                        data-expired-callback="expired"
                        data-error-callback="error">
                    </div>
                    <button class="none float-right-adjust btn btn-primary" id="verify-false" type="button">You're robot.</button>
                    <button class="none float-right-adjust btn btn-primary" id="verify-true" type="submit" name="submit_bt">Submit form</button>
                </div>
            </form>
        </div>
    </body>
</html>

<?php
    if(isset($_POST['username']) && $_POST['username']!="" && isset($_POST['password']) && $_POST['password']!="")
    {
        //must check the username include sqli words or not
        require_once('sqli_filter.php');
        $sqli_username_detect = sqli_detect_signup($_POST['username']);
        if($sqli_username_detect === true)
            echo '<script>alert("You can not use the special character.")</script>';
        else
        {
            include("check_signup.php");
            try 
            {
                if($row)
                {
                    echo '<script>alert("Oops, you signed up before, please login at home page...")</script>';
                    mysqli_close($db_link);
                    header("refresh:1; url=index.php");
                }
                else
                {
                    //get the post parameter and prepare
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
                    echo '<script>alert("Sign up successful. Please wait 1s for redirection.")</script>';
                    header("refresh:1; url=index.php");
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

    function onClick(e)
    {
        e.preventDefault();
        grecaptcha.ready(function()
        {
            grecaptcha.execute('6LeS0t0fAAAAAL2MDlvjEdl-VoNh-1mH_frVp0Ks', {action: 'submit'}).then(function(token)
            {
                // Add your logic to submit to your backend server here.
            });
        });
    }

    // function onSubmit(token)
    // {
    //     document.getElementById("formAdd").submit();
    // }


    // 取 ip
    var uriIP = 'https://www.cloudflare.com/cdn-cgi/trace';
    var ip;
    fetch(uriIP)
    .then(response => response.text())
    .then(result =>
    {
        var resultArr = result.split('\n');
        for(var i = 0, len = resultArr.length; i < len; i++)
        {
            var tempArr = resultArr[i].split('=');
            if(tempArr[0] == 'ip')
            {
                ip = tempArr[1];
                break;
            }
        }
    })
    .catch(err => {window.alert(err)});

    function verifyCallback(token)
    {
        var formData = new FormData();
        formData.append('token', token);
        formData.append('ip', ip);
            
        // Google Apps Script 部署為網路應用程式後取得的 URL
        var uriGAS = 'https://script.google.com/macros/s/AKfycbzeosH-DyfyS1CzVlrgoiV11CAM6a9Zy_TpCqDlrjQ5y4yL6qW1RfYoaowVMXH3rxk/exec';
            
        fetch(uriGAS,
        {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(result =>
        {
            if(result.success)
            {
                // 後端驗證成功，success 會是 true
                // 這邊寫驗證成功後要做的事
                document.getElementById('verify-true').classList.remove('none');
            }
            else
            {
                // success 為 false 時，代表驗證失敗，error-codes 會告知原因
                window.alert(result['error-codes'][0])
                document.getElementById('verify-false').classList.remove('none');
            }
        })
        .catch(err => {window.alert(err)})
    }
</script>