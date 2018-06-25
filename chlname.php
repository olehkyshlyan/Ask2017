<?
session_start();

if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){
$suid = $_SESSION['uid'];

if(isset($_POST['lname'])){
  if($_POST['lname'] != ''){
    $ln = mb_substr((string)$_POST['lname'],0,31,'UTF-8');
    unset($_POST['lname']);
    $lln = mb_strlen($ln,'UTF-8');
    if($lln <= 30){
      $pmatch = preg_match('/[^\p{N}\p{L}\p{Zs}\-\']+/u',$ln,$matches);
      if($pmatch != 1){
        $lname = preg_replace('/[^\p{N}\p{L}\p{Zs}\-\']+/u','',$ln);
        $flname = addslashes($lname);
      }
      else{
        $chlname['msg'] = 'Unacceptable characters: '.$matches[0];
      }
    }
    else{
      $chlname['msg'] = 'Last name is longer than 30 characters';
    }
  }
  else{
    $chlname['msg'] = 'First name is empty';
  }
}
else{
  $chlname['msg'] = 'First name is not set';
}

if(isset($lname)){

try{
include "db.php";

$uinfo = $db->query("SELECT lname FROM users WHERE uid='$suid';")->fetch(PDO::FETCH_ASSOC);

if($uinfo['lname'] != $lname){

$updres = $db->exec("UPDATE users SET lname='$flname' WHERE uid='$suid';");
if($updres == 1){
  $chlname['msg'] = 'You have changed your last name on: '.$lname;
  $newlname = $db->query("SELECT lname FROM users WHERE uid='$suid';")->fetch(PDO::FETCH_ASSOC);
  $chlname['newlname'] = $newlname['lname'];
}
else{
  $chlname['msg'] = 'Changing the first name failed';
}

}
else{
  $chlname['msg'] = 'First name is the same';
}

}
catch(Exception $e){
  $chlname['msg'] = $e->getMessage();
}

}

$chlname = json_encode($chlname);
print($chlname);

}

?>