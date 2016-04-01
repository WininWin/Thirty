<?php
session_start();

include_once __DIR__ . '/php/db_config.php';
include_once __DIR__ . '/php/db_func.php';
$link = db_init($config["host"], $config["duser"], $config["dpw"], $config["dname"]);
// Check connection
if ($link->connect_error) {
  die("Unable to connect: Database\n" . $link->connect_error);
} 


?>


<!DOCTYPE html>
<html lang="en">
<head>
  
  <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="author" content="ezoneid@gmail.com">

    <title>Thirty</title>
    <link rel="shortcut icon" type="image/x-icon" href="http://ezoneid.com/jsgame/favicon.ico" />

    <script src="/Thirdpartyjs/two.min.js"></script>
    <script src="/Thirdpartyjs/events.js"></script>
    <script src="http://underscorejs.org/underscore-min.js"></script>
    <script src="http://backbonejs.org/backbone-min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src = "/Thirdpartyjs/stopwatch.js"></script>

    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/style.css" rel = "stylesheet" />

  </head>
   
  <body>
<script>
/*Facebook javscript Start */
    (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
    // This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {

    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
        window.location.replace('http://thirty.ezoneid.com/fb_callback.php') ;
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.';
    } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into Facebook.';
    }
  }

  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }

  window.fbAsyncInit = function() {
  FB.init({
    appId      : '114126498934499',
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.4' // use version 2.2
  });

  // Now that we've initialized the JavaScript SDK, we call 
  // FB.getLoginStatus().  This function gets the state of the
  // person visiting this page and can return one of three states to
  // the callback you provide.  They can be:
  //
  // 1. Logged into your app ('connected')
  // 2. Logged into Facebook, but not your app ('not_authorized')
  // 3. Not logged into Facebook and can't tell if they are logged into
  //    your app or not.
  //
  // These three cases are handled in the callback function.

  };
</script>


<div class="container" >
      <div class="header" >
        <nav>
          <ul class="nav nav-pills pull-right">
            <li role="presentation" id="Home" class="active"><a href="/index.php?id=main">Home</a></li>
            <li role="presentation" id="About"><a href="/index.php?id=About">About</a></li>
            <li role="presentation" id="Ranking"><a href="/index.php?id=Ranking">Ranking</a></li>
          </ul>
        </nav>
        <h2 id = "title"><a href="index.php?id=main">Thirty</a></h2>
      </div>
<?php
	//session_unset();
  //check user login status 
      if( isset($_SESSION['fb_access_token']) && !empty($_SESSION['fb_access_token']) && $_GET['id'] != "" ) {

             
              include('./docs/'.$_GET['id'].".php");
        }
      else if(isset($_SESSION['fb_access_token']) && !empty($_SESSION['fb_access_token']) && !isset($_GET['id'])){
          echo '<div class="jumbotron"><div class ="start">
          <p id = "first">Loading...</p><script>window.location.replace("http://thirty.ezoneid.com/fb_callback.php");</script></div></div>';
      
      }
      else{
          echo  '<div class="jumbotron" >';

          echo '<div id="login">';
          echo '<p id = "login_p">Login with Facebook </p>';
          echo '<fb:login-button scope="public_profile,email" data-size="xlarge" onlogin="checkLoginState();"></fb:login-button>';      
          echo '</div>';

          echo'<div id="status"></div>';
          echo '</div>';
      }
    
?>
      <footer class="footer">
        <p>&copy; ezoneid@gmail.com</p>
        <p class="version">Currently v1.0.0</p>
      </footer>

    </div> 


    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="bootstrap/js/bootbox.min.js"></script>
     <script>
      
    </script>

  </body>
</html>