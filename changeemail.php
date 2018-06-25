<?

if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){

if(isset($_GET['changeemail'])){
$tid = preg_replace('/[^0-9]/','',substr((string)$_GET['changeemail'],0,30));
unset($_GET['changeemail']);
//print('$tid: '.$tid.'<br />');

$suid = $_SESSION['uid'];
//print('$suid: '.$suid.'<br />');

if($tid != ''){

try{
include "db.php";

$tmpemail = $db->query("SELECT * FROM tmpemail WHERE tid='$tid';")->fetch(PDO::FETCH_ASSOC);

if($tmpemail != false){

$currdt = gmdate('YmdHis');
$temail = $tmpemail['email'];

$emdt = strtotime($tmpemail['dt']);
$emdt = strtotime('+1 day',$emdt);
$emdt = gmdate('YmdHis',$emdt);
if($emdt > $currdt){

$updemail = $db->exec("UPDATE users SET email='$temail' WHERE uid='$suid';");

if($updemail == 1){
$_SESSION['chemail'] = 'You have changed your e-mail to: '.$temail;
$delres = $db->exec("DELETE FROM tmpemail WHERE uid='$suid';");
}
elseif($updemail == 0){
$_SESSION['chemail'] = 'E-mail was not changed. Try again.';
}

}
else{
  $_SESSION['chemail'] = 'Time to change your e-mail has expired (24 hours)';
}

}
else{
  $_SESSION['chemail'] = 'This letter (to change your e-mail) was already used OR time to use the letter has expired (24 hours)';
}

}
catch(Exception $e){
  $_SESSION['chemail'] = $e->getMessage();
}

}

}

header('Location:http://'.$currenturl); exit();

}

?>