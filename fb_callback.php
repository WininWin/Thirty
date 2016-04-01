<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/php/db_config.php';
require_once __DIR__ . '/php/db_func.php';

$link = db_init($config["host"], $config["duser"], $config["dpw"], $config["dname"]);
// Check connection
if ($link->connect_error) {
  die("Unable to connect: Database\n" . $link->connect_error);
} 


//check user login status
if(!isset($_SESSION['fb_access_token'])) {
        $fb = new Facebook\Facebook([
          'app_id' => '114126498934499',
          'app_secret' => '532dbf229691c411d6d31675e1f6c88e',
          'default_graph_version' => 'v2.4',
          ]);

        $helper = $fb->getJavaScriptHelper();

        try {
          $accessToken = $helper->getAccessToken();
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
          // When Graph returns an error
          echo 'Graph returned an error: ' . $e->getMessage();
          exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
          // When validation fails or other local issues
          echo 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
        }

        $oAuth2Client = $fb->getOAuth2Client();

        // Get the access token metadata from /debug_token
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);
        echo '<h3>Metadata</h3>';
        var_dump($tokenMetadata);

        // Validation (these will throw FacebookSDKException's when they fail)
        $tokenMetadata->validateAppId('114126498934499');
        // If you know the user ID this access token belongs to, you can validate it here
        //$tokenMetadata->validateUserId('123');
        $tokenMetadata->validateExpiration();

        if (! $accessToken->isLongLived()) {
          // Exchanges a short-lived access token for a long-lived one
          try {
            $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
          } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
            exit;
          }

          echo '<h3>Long-lived</h3>';
          var_dump($accessToken->getValue());
        }

        try {
          // Returns a `Facebook\FacebookResponse` object
          $response = $fb->get('/me?fields=id,name,email,picture', $accessToken->getValue());
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
          echo 'Grap returned an error: ' . $e->getMessage();
          exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
          echo 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
        }

        $user = $response->getGraphUser();
        

        $p_id = $user['email'];
        $p_user = $user['name'];
        $p_url = $user['picture']['url'];
        $p_getid = $user['id'];
        $p_score = 0;
        $p_tscore = 0;
        $p_time = 0;
        $p_toptime = 0;
 	$sql = "SELECT * FROM score WHERE score.id = '$p_id'";
	$result = $link->query($sql);
      //store user data
      if($result -> num_rows === 0){
          $sql =  "INSERT INTO score(id, name, pic_url, max_score, thirty_score, time_score, top_time, get_id)
          VALUES ('$p_id', '$p_user', '$p_url', '$p_score', '$p_tscore', '$p_time', '$p_toptime', '$p_getid')";

          $scores = array($p_score, $p_tscore, $p_time, $p_toptime);
                if ($link->query($sql) === true) {
                      $_SESSION['scores'] = $scores;
                      $_SESSION['id'] = $p_id;
                     $_SESSION['fb_access_token'] = (string) $accessToken;
                    header('Location: http://thirty.ezoneid.com/index.php?id=main');


                } 
                else {
                    echo "Error: " . $sql . "<br>" . $link->error;

                }


    }
    //if users login from antoer device
    else{
    $row = $result->fetch_assoc();
  
    $scores = array($row['max_score'], $row['thirty_score'], $row['time_score'], $row['top_time']);
     $_SESSION['scores'] = $scores;
     $_SESSION['id'] = $row['id'];
          $_SESSION['fb_access_token'] = (string) $accessToken;
      header('Location: http://thirty.ezoneid.com/index.php?id=main');
    }


}


else{

	$fb = new Facebook\Facebook([
          'app_id' => '114126498934499',
          'app_secret' => '532dbf229691c411d6d31675e1f6c88e',
          'default_graph_version' => 'v2.4',
          ]);

        $helper = $fb->getJavaScriptHelper();
  
     try {
          // Returns a `Facebook\FacebookResponse` object
          $response = $fb->get('/me?fields=id,name,email,picture', $_SESSION['fb_access_token']);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
          echo 'Grap returned an error: ' . $e->getMessage();
          exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
          echo 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
        }
        
          $user = $response->getGraphUser();
          
         $p_id = $user['email'];
        
        $p_user = $user['name'];
        $p_url = $user['picture']['url'];
        
 	$sql = "SELECT * FROM score WHERE score.id = '$p_id'";
	$result = $link->query($sql);
	$row = $result->fetch_assoc();
  
  	$scores = array($row['max_score'], $row['thirty_score'], $row['time_score'], $row['top_time']);
   	 $_SESSION['scores'] = $scores;
   	 $_SESSION['id'] = $row['id'];
   	
   	 header('Location: http://thirty.ezoneid.com/index.php?id=main');
}
?>

