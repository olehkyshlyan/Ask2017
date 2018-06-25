<?
// change user login email
session_start();

if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){
$suid = $_SESSION['uid'];

if(isset($_POST['lemail'])){
  if($_POST['lemail'] != ''){
    $lem = mb_substr((string)$_POST['lemail'],0,51,'UTF-8');
    unset($_POST['lemail']);
    $lem = mb_strtolower($lem,'UTF-8');
    if(mb_strlen($lem,'UTF-8') <= 50){
      $pmatch = preg_match('/[^\p{N}\p{L}\@\.\_\-\'\&]+/u',$lem,$matches);
      if($pmatch != 1){
        $lemail = preg_replace('/[^\p{N}\p{L}\@\.\_\-\'\&]+/u','',$lem);
      }
      else{
        $chlemail['msg'] = 'Unacceptable characters: '.$matches[0];
      }
    }
    else{
      $chlemail['msg'] = 'Login e-mail is longer than 50 characters';
    }
  }
  else{
    $chlemail['msg'] = 'Login e-mail is empty';
  }
}
else{
  $chlemail['msg'] = 'Login e-mail is not set';
}


if(isset($lemail)){
$savedlemail = false;

try{
include "db.php";

$uinfo = $db->query("SELECT lemail FROM users WHERE uid='$suid';")->fetch(PDO::FETCH_ASSOC);
//print('$uinfo: '); var_dump($uinfo); print('<br />');

if($uinfo['lemail'] != $lemail){

$dt = gmdate('YmdHis');
$tid = gmdate('YmdHis').rand(1000,100000);

$delres = $db->exec("DELETE FROM tmpemail WHERE uid='$suid';");
//print('$delres: '); var_dump($delres); print('<br />');

$checkemail = $db->query("SELECT * FROM tmpemail WHERE uid='$suid';")->fetch(PDO::FETCH_ASSOC);
//print('$checkemail: '); var_dump($checkemail); print('<br />');

if($checkemail == false){

$insres = $db->exec("INSERT INTO tmpemail (tid,uid,lemail,dt) VALUES ('$tid','$suid','$lemail','$dt')");
//print('$insres: '); var_dump($insres); print('<br />');

if($insres == 1){
  $savedlemail = true;
}
else{
  $chlemail['msg'] = 'Your new login e-mail was not saved. Try again.';
}

}
else{
  $chlemail['msg'] = 'Your previous login email was not deleted. Try again.';
}

}
else{
  $chlemail['msg'] = 'Login e-mail is the same: '.$uinfo['lemail'];
}

}
catch(Exception $e){
  $chlemail['msg'] = $e->getMessage();
}

}

if($savedlemail == true){

$confirmlink = 'http://localhost/exp3/up.php?uid='.$suid.'&changeemail='.$tid;
//$confirmlink = 'http://ask.olehkyshlyan.name/up.php?uid='.$suid.'&changeemail='.$tid;

$to = $lemail;
$subject = 'Changing e-mail';

$message = '
You have changed your e-mail at the site "Questions and Answers" to: '.$lemail.'<br />
Please, press this link <a href="'.$confirmlink.'">'.
$confirmlink
.'</a> to complete changing your e-mail.<br />
This link will be active during next 24 hours.<br />
If you did not change your e-mail at the site "Questions and Answers", just delete this letter.<br />
Thanks :-)
';

$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";
$headers .= "From: Dating web-site <questionsandanswers@mail.com>\r\n";

$sentmail = mail($to,$subject,$message,$headers);
//print('$sentmail: '); var_dump($sentmail); print('<br />');
if($sentmail == true){
  $chlemail['msg'] = 'Confirmation letter has been sent to: '.$lemail.'<br />Press the link in the letter to complete changing your e-mail';
  $chlemail['newemail'] = $lemail;
}
else{
  $chlemail['msg'] = 'Sending e-mail with confirmation link failed. Try to change e-mail agian.';
}

}

$chlemail = json_encode($chlemail);
print($chlemail);

}

?>