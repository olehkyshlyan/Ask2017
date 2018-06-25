<?
session_start();

if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){
$suid = $_SESSION['uid'];

if(isset($_POST['phone'])){
  $ph = mb_substr((string)$_POST['phone'],0,31,'UTF-8');
  unset($_POST['phone']);
  $lph = mb_strlen($ph,'UTF-8');
  if($lph <= 30){
    $pmatch = preg_match('/[^0-9\+\-\.\ \(\)]/',$ph,$matches);
    if($pmatch != 1){
      $phone = preg_replace('/[^0-9\+\-\.\ \(\)]/','',$ph);
      $phone = addslashes($phone);
    }
    else{
      $chphone['msg'] = 'Unacceptable characters: '.$matches[0];
    }
  }
  else{
    $chphone['msg'] = 'Phone number is longer than 30 characters';
  }
}
else{
  $chphone['msg'] = 'Phone number is not set';
}

if(isset($phone)){

try{
include "db.php";

$uinfo = $db->query("SELECT phone FROM users WHERE uid='$suid';")->fetch(PDO::FETCH_ASSOC);

if($uinfo['phone'] != $phone){

$updres = $db->exec("UPDATE users SET phone='$phone' WHERE uid='$suid';");
if($updres == 1){
  $chphone['msg'] = 'You have changed your phone number on: '.$phone;
  $newphone = $db->query("SELECT phone FROM users WHERE uid='$suid';")->fetch(PDO::FETCH_ASSOC);
  $chphone['newphone'] = $newphone['phone'];
}
else{
  $chphone['msg'] = 'Changing the phone number failed';
}

}
else{
  $chphone['msg'] = 'Phone number is the same';
}

}
catch(Exception $e){
  $chphone['msg'] = $e->getMessage();
}

}

$pchphone = json_encode($chphone);
print($pchphone);

}

?>