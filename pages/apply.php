<?php
if(!defined('vendors'))
  die('Direct access not permitted');

require_once 'functions.php';
global $mybb, $user_info, $db, $lang;
$address = "";
if(isset($_POST)) {
  if(!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['contact']) && !empty($_POST['email'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $contact_type = $_POST['contact'];
    $email = $_POST['email'];
    $address = check_register($username, $password, $email);
    var_dump($user_info);
  }
}
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="http://v4-alpha.getbootstrap.com/favicon.ico">
    <title>siph0n - Vendor Application</title>
    <!-- Bootstrap core CSS -->
    <link href="http://v4-alpha.getbootstrap.com/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="http://v4-alpha.getbootstrap.com/examples/cover/cover.css" rel="stylesheet">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="http://v4-alpha.getbootstrap.com/assets/js/ie10-viewport-bug-workaround.js"></script>
  </head>
  <body>
    <div class="site-wrapper">
      <div class="site-wrapper-inner">
        <div class="cover-container">
          <div class="masthead clearfix">
            <div class="inner">
            <div align="left"><img src="https://forum.siph0n.net/images/cleandark/logo.png"/></div>
              <nav class="nav nav-masthead">
                <a class="nav-link active" href="vendor.php">Home</a>
              </nav>
            </div>
          </div>
          <div class="inner cover">
          <?php if(empty($user_info)) { ?>
            <h1 class="cover-heading">Vendor Applicaton:</h1>
            <div class="container" align="center">
              <form class="form-inline" action="" method="POST" id="form" name="form">
               <div class="form-group">
                <label for="username" class="sr-only">Username:</label>
                <input type="text" id="username" name="username" class="form-control" pattern=".{3,}" placeholder="username.." required autofocus>
               </div></br></br>
               <div class="form-group">
                 <label for="password" class="sr-only">Password:</label>
                 <input type="password" id="password" name="password" class="form-control" placeholder="password.." required>
               </div></br></br>
               <div class="form-group">
                 <label for="contact" class="sr-only">Contact:</label>
                 <select class="form-control" id="contact" name="contact" style="width:100px">
                   <option>Email</option>
                   <option>Jabber</option>
                 </select>
               </div>
               <div class="form-group">
                <input type="email" id="email" name="email" class="form-control" placeholder="jabber/email.." required>
               </div></br></br>
               <button class="btn btn-primary btn-block" type="submit">Sign in</button>
             </form>     
            </div>
            <?php } else { ?>
            <h1 class="cover-heading">Payment Address: <?php echo $address; ?></h1>
            <p> Awaiting payment of 0.5btc's from: <?php echo $user_info['username']; ?> </p>
            <?php } ?>
          </div>
          <div class="mastfoot">
            <div class="inner">
              <p>Credits to <a href="http://getbootstrap.com">Bootstrap</a>, by <a href="https://twitter.com/mdo">@mdo</a>.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="http://v4-alpha.getbootstrap.com/assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="http://v4-alpha.getbootstrap.com/dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="http://v4-alpha.getbootstrap.com/assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
