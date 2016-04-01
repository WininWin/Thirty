<?php
session_start();

include_once __DIR__ . '/db_config.php';
include_once __DIR__ . '/db_func.php';
$link = db_init($config["host"], $config["duser"], $config["dpw"], $config["dname"]);
// Check connection
if ($link->connect_error) {
  die("Unable to connect: Database\n" . $link->connect_error);
} 

$temp = $_REQUEST['score'];
$temp_t = $_REQUEST['send_score_t'];
$temp_s = $_REQUEST['send_score_s'];
$temp_toptime = $_REQUEST['top_time'];

$id = $_SESSION['id'];
$sf = $_SESSION['scores'][0];
$st = $_SESSION['scores'][1];
$ss = $_SESSION['scores'][2];
$tt = $_SESSION['scores'][3];
if($temp > $sf){
$_SESSION['scores'][0] = $temp;
 $sql = "UPDATE score SET score.max_score = '$temp' WHERE score.id = '$id'";
 $result = $link -> query($sql);

}

if($st < $temp_t){
$_SESSION['scores'][1] = $temp_t;
 $sql = "UPDATE score SET score.thirty_score = '$temp_t' WHERE score.id = '$id'";
 $result = $link -> query($sql);

}
if($temp_s != 0){
	if($ss == 0){
		$_SESSION['scores'][2] = $temp_s;
		$sql = "UPDATE score SET score.time_score = '$temp_s' WHERE score.id = '$id'";
		$result = $link -> query($sql);
	}
	else if($ss > $temp_s){
		$_SESSION['scores'][2] = $temp_s;
		$sql = "UPDATE score SET score.time_score = '$temp_s' WHERE score.id = '$id'";
		$result = $link -> query($sql);
	
	}
}

if($tt < $temp_toptime && $temp >= 30){
	$_SESSION['scores'][3] = $temp_toptime;
	$sql = "UPDATE score SET score.top_time = '$temp_toptime' WHERE score.id = '$id'";
	$result = $link -> query($sql);

}






echo json_encode(array('result'=>true, 'score'=>$_REQUEST['score'], 'score2'=>$_REQUEST['send_score_t'], 'score3' => $_REQUEST['send_score_s'], 'score4' => $_REQUEST['top_time']));

?>