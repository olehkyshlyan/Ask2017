<?
session_start();

if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){
$suid = $_SESSION['uid'];

if(isset($_POST['cemail'])){
  $cem = mb_substr((string)$_POST['cemail'],0,51,'UTF-8');
  unset($_POST['cemail']);
  $cem = mb_strtolower($cem,'UTF-8');
  $lcem = mb_strlen($cem,'UTF-8');
  if($lcem <= 50){
    $pmatch = preg_match('/[^\p{N}\p{L}\@\.\_\-\'\&]+/u',$cem,$matches);
    if($pmatch != 1){
      $cemail = preg_replace('/[^\p{N}\p{L}\@\.\_\-\'\&]+/u','',$cem);
    }
    else{
      $chcemail['msg'] = 'Unacceptable characters: '.$matches[0];
    }
  }
  else{
    $chcemail['msg'] = 'Contact e-mail is longer than 50 characters';
  }
}
else{
  $chcemail['msg'] = 'Contact e-mail is not set';
}

if(isset($cemail)){

try{
include "db.php";

$uinfo = $db->query("SELECT cemail FROM users WHERE uid='$suid';")->fetch(PDO::FETCH_ASSOC);

if($uinfo['cemail'] != $cemail){

$updres = $db->exec("UPDATE users SET cemail='$cemail' WHERE uid='$suid';");
if($updres == 1){
  $chcemail['msg'] = 'You have changed your contact e-mail on: '.$cemail;
  $newcemail = $db->query("SELECT cemail FROM users WHERE uid='$suid';")->fetch(PDO::FETCH_ASSOC);
  $chcemail['newcemail'] = $newcemail['cemail'];
}
else{
  $chcemail['msg'] = 'Changing the contact e-mail failed';
}

}
else{
  $chcemail['msg'] = 'Contact e-mail is the same';
}

}
catch(Exception $e){
  $chcemail['msg'] = $e->getMessage();
}

}

$chcemail = json_encode($chcemail);
print($chcemail);

}

?>