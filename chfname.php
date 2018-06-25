<?
session_start();

if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){
$suid = $_SESSION['uid'];

if(isset($_POST['fname'])){
  if($_POST['fname'] != ''){
    $fn = mb_substr((string)$_POST['fname'],0,31,'UTF-8');
    unset($_POST['fname']);
    $lfn = mb_strlen($fn,'UTF-8');
    if($lfn <= 30){
      $pmatch = preg_match('/[^\p{N}\p{L}\p{Zs}\-\']+/u',$fn,$matches);
      if($pmatch != 1){
        $fname = preg_replace('/[^\p{N}\p{L}\p{Zs}\-\']+/u','',$fn);
        $ffname = addslashes($fname);
      }
      else{
        $chfname['msg'] = 'Unacceptable characters: '.$matches[0];
      }
    }
    else{
      $chfname['msg'] = 'First name is longer than 30 characters';
    }
  }
  else{
    $chfname['msg'] = 'First name is empty';
  }
}
else{
  $chfname['msg'] = 'First name is not set';
}

if(isset($fname)){

try{
include "db.php";

$uinfo = $db->query("SELECT fname FROM users WHERE uid='$suid';")->fetch(PDO::FETCH_ASSOC);

if($uinfo['fname'] != $fname){

$updres = $db->exec("UPDATE users SET fname='$ffname' WHERE uid='$suid';");
if($updres == 1){
  $chfname['msg'] = 'You have changed your first name on: '.$fname;
  $newfname = $db->query("SELECT fname FROM users WHERE uid='$suid';")->fetch(PDO::FETCH_ASSOC);
  $chfname['newfname'] = $newfname['fname'];
}
else{
  $chfname['msg'] = 'Changing the first name failed';
}

}
else{
  $chfname['msg'] = 'First name is the same';
}

}
catch(Exception $e){
  $chfname['msg'] = $e->getMessage();
}

}

$chfname = json_encode($chfname);
print($chfname);

}

?>