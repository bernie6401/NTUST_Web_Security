<!doctype html>
<html lang="en" class="h-100">

  <head>
    <title>Home</title>
    <?php include("website_head.php"); ?>
    <!-- Custom styles for this template -->
    <link href="./css/index.css" rel="stylesheet">
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
                <input class="form-control input-adjust" id="floatingInput" placeholder="Username" type="text" name="username"><br><br>
                <label for="floatingInput">Username</label>
              </div>

              <div class="form-floating">
                <input class="form-control input-adjust" id="floatingPassword" placeholder="Password" type="password" name="password"></br></br>
                <label for="floatingPassword">Password</label>
              </div>
            </div>
            <button  class="w-100 btn btn-lg btn-primary btn-adjust" type="submit" name="login_submit">Login</button>
          </form>
        </div>
      </main>

      <footer class="mt-auto text-white-50">
        <p>NTUST Web Security PA, by <a href="https://github.com/bernie6401" class="text-white">@SBK</a>.</p>
      </footer>
    </div>
  </body>
</html>