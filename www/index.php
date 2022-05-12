<!doctype html>
<html lang="en" class="h-100">

  <head>
    <title>Home</title>
    <?php include("website_head.php"); ?>
    <!-- Custom styles for this template -->
    <link href="./css/index.css" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  </head>

  <body class="d-flex h-100 text-center text-white bg-dark">
    <div class="cover-container-adjust d-flex w-100 h-100 p-3 mx-auto flex-column">
      <header class="mb-auto">
        <div>
          <h3 class="float-md-start mb-0" href="index.php">Home</h3>
          <nav class="nav nav-masthead justify-content-center float-md-end">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
            <a class="nav-link" href="board.php">Board</a>
            <a class="nav-link" href="signup.php">Sign Up</a>
          </nav>
        </div>
      </header>
      
      <main>
        <h1 class="h3 mb-3 h1-adjust">Please login or sign up first.</h1>
        <div class="padding-left-mid signin-part">
          <form method="POST" action="login.php" name="login">
            <div class="text-black" style="text-shadow:0 0 0">
              <div class="form-floating form-floating-adjust">
                <input class="form-control input-adjust" id="floatingInput" placeholder="Username" type="text" name="username" required></input></br></br>
                <label for="floatingInput">Username</label>
              </div>

              <div class="form-floating">

                <input class="form-control input-adjust" id="floatingPassword" placeholder="Password" type="password" name="password" required></input></br></br>
                <label for="floatingPassword">Password</label>
              </div>
            </div>
            <button
              class="g-recaptcha w-100 btn btn-lg btn-primary btn-adjust"
              data-sitekey="6LeS0t0fAAAAAL2MDlvjEdl-VoNh-1mH_frVp0Ks"
              data-action="verify1"
              data-callback="verifyCallback">
              You're robot or not?
            </button>
            </br>
            <button class="none w-100 btn btn-lg btn-primary btn-adjust" id="verify-false-1" type="button">You're robot.</button>
            </br>
            <button class="none w-100 btn btn-lg btn-primary btn-adjust" id="verify-true-1" type="submit" name="login_submit">Login</button>
          </form>
        </div>
      </main>

      <footer class="mt-auto text-white-50">
        <p>NTUST Web Security PA, by <a href="https://github.com/bernie6401" class="text-white">@SBK</a>.</p>
      </footer>
    </div>
  </body>
</html>

<script>
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
  var uriGAS = 'https://script.google.com/macros/s/AKfycbxlVyxovUb-6IIS42K10Ue7zaavCeaf3AabZ-B5Cxy4Y5pGXSsp0POvFNGFZm-rRKI/exec';
  // 把 token 送到後端做驗證
  function verifyCallback(token)
  {
    var formData = new FormData();
    formData.append('token', token);
    formData.append('ip', ip);

    fetch(uriGAS,{method: "POST", body: formData}).then(response => response.json())
    .then(result =>
    {
      if(result.success)
      {
        // 分數大過 0.5，才當作是真實人類操作
        if(result.score > 0.5)
        {
          // 判斷是真人時要做的事
          // document.getElementById('verify-true').classList.remove('btn');
          // document.getElementById('verify-true').classList.remove('w-100');
          // document.getElementById('verify-true').classList.remove('btn-lg');
          // document.getElementById('verify-true').classList.remove('btn-primary');
          // document.getElementById('verify-true').classList.remove('btn-adjust');
          document.getElementById('verify-true-1').classList.remove('none');
        }
        // 分數低於 0.5，當作機器人
        else
        {
          // 判斷是機器人時要做的事
          document.getElementById('verify-false-1').classList.remove('none');
        }
      }
      else
      {
        window.alert(result['error-codes'][0])
      }
    })
    .catch(err => {window.alert(err)})
  }
</script>