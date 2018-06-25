<?
session_start();

if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){
$suid = $_SESSION['uid'];

if(isset($_POST['city'])){
  $ct = mb_substr((string)$_POST['city'],0,51,'UTF-8');
  unset($_POST['city']);
  $lct = mb_strlen($ct,'UTF-8');
  if($lct <= 50){
    $pmatch = preg_match('/[^\p{N}\p{L}\p{Zs}\-\']+/u',$ct,$matches);
    if($pmatch != 1){
      $city = preg_replace('/[^\p{N}\p{L}\p{Zs}\-\']+/u','',$ct);
      $fcity = addslashes($city);
    }
    else{
      $chcity['msg'] = 'Unacceptable characters: '.$matches[0];
    }
  }
  else{
    $chcity['msg'] = 'City name is longer than 50 characters';
  }
}
else{
  $chcity['msg'] = 'City name is not set';
}

if(isset($city)){

try{
include "db.php";

$uinfo = $db->query("SELECT city FROM users WHERE uid='$suid';")->fetch(PDO::FETCH_ASSOC);

if($uinfo['city'] != $city){

$updres = $db->exec("UPDATE users SET city='$fcity' WHERE uid='$suid';");
if($updres == 1){
  $chcity['msg'] = 'You have changed city name on: '.$city;
  $newcity = $db->query("SELECT city FROM users WHERE uid='$suid';")->fetch(PDO::FETCH_ASSOC);
  $chcity['newcity'] = $newcity['city'];
}
else{
  $chcity['msg'] = 'Changing the city name failed';
}

}
else{
  $chcity['msg'] = 'City name is the same';
}

}
catch(Exception $e){
  $chcity['msg'] = $e->getMessage();
}

}

$chcity = json_encode($chcity);
print($chcity);

}

?>