<?
session_start();

if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){
$suid = $_SESSION['uid'];

if(isset($_POST['cont'])){
  $cont = mb_substr((string)$_POST['cont'],0,201,'UTF-8');
  unset($_POST['cont']);
  $cont = str_replace(array("\x0A\x0D","\x0D\x0A","\x0A","\x0D"),"\n",$cont);
  //$fcont = str_replace(array("\x0A\x0D","\x0D\x0A","\x0A","\x0D"),"",$cont);
  $lcont = mb_strlen($cont,'UTF-8');
  
  if($lcont <= 200){
    $pmatch = preg_match('/[^\p{N}\p{L}\p{Zs}\@\_\-\$\+\=\.\:\'\ \\r\n\\n\\r]+/u',$cont,$matches);
    if($pmatch != 1){
      $contacts = preg_replace('/[^\p{N}\p{L}\p{Zs}\@\_\-\$\+\=\.\:\'\ \\r\n\\n\\r)]+/u','',$cont);
      $fcontacts = addslashes($contacts);
    }
    else{
      $chcont['msg'] = 'Unacceptable characters: '.$matches[0];
    }
  }
  else{
    $chcont['msg'] = 'Contacts are longer than 200 characters';
  }
}
else{
  $chcont['msg'] = 'Contacts are not set';
}

if(isset($contacts)){

try{
include "db.php";

$uinfo = $db->query("SELECT cont FROM users WHERE uid='$suid';")->fetch(PDO::FETCH_ASSOC);

if($uinfo['cont'] != $contacts){

$updres = $db->exec("UPDATE users SET cont='$fcontacts' WHERE uid='$suid';");
if($updres == 1){
  $chcont['msg'] = 'You have changed your contacts on: '.$contacts;
  $newcontacts = $db->query("SELECT cont FROM users WHERE uid='$suid';")->fetch(PDO::FETCH_ASSOC);
  $chcont['newcontacts'] = $newcontacts['cont'];
}
else{
  $chcont['msg'] = 'Changing contacts failed';
}

}
else{
  $chcont['msg'] = 'Contacts are the same';
  $chcont['newcontacts'] = $contacts;
}

}
catch(Exception $e){
  $chcont['msg'] = $e->getMessage();
}

}

$chcont = json_encode($chcont);
print($chcont);

}

?>