<?
// getting a new confirmation e-mail
if(isset($_POST['btnewem'])){
unset($_POST['btnewem']);

include_once "confirmemail.php";

$gnemerr = false;

// E-MAIL
if(isset($_POST['email'])){
  if($_POST['email'] != ''){
    $em = mb_substr((string)$_POST['email'],0,51,'UTF-8');
    unset($_POST['email']);
    $em = mb_strtolower($em,'UTF-8');
    $lem = mb_strlen($em,'UTF-8');
    if($lem <= 50){
      $pemmatch = preg_match('/[^\p{N}\p{L}\@\.\_\-\']+/u',$em,$emmatches);
      if($pemmatch != 1){
        $em = preg_replace('/[^\p{N}\p{L}\@\.\_\-\']+/u','',$em);
        $email = $em;
        $_SESSION['gnemail'] = $em;
        //print('$email: '.$email.'<br />');
      }
      else{
        //print('E-mail contains unacceptable characters: '.$emmatches[0].'<br />');
        $gnemerr = true;
        $_SESSION['gnemerr'] = 'E-mail contains unacceptable characters: '.$emmatches[0].'<br />';
        $_SESSION['gnemail'] = $em;
      }
    }
    else{
      //print('E-mail is longer than 50 characters<br />');
      $gnemerr = true;
      $_SESSION['gnemerr'] = "E-mail is longer than 50 characters<br />";
      $_SESSION['gnemail'] = $em;
    }
  }
  else{
    //print('E-mail is empty<br />');
    $gnemerr = true;
    $_SESSION['gnemerr'] = "E-mail field is empty<br />";
  }
}
else{
  //print('E-mail is not set<br />');
  $gnemerr = true;
  $_SESSION['gnemerr'] = "E-mail is not set<br />";
}

if(isset($email)){

$checkusers = $db->query("SELECT id FROM users WHERE lemail='$email'")->fetchAll(PDO::FETCH_ASSOC);
//print('$checkusers: '); var_dump($checkusers); print('<br />');
$usarr = count($checkusers);
//print('$usarr: '.$usarr.'<br />');

if($usarr == 0){

$checktmpusers = $db->query("SELECT uid FROM tmpusers WHERE email='$email'")->fetchAll(PDO::FETCH_ASSOC);
print('$checktmpusers: '); var_dump($checktmpusers); print('<br />');
$tmpusarr = count($chtmpus);
print('$tmpusarr: '.$tmpusarr.'<br />');
$uid = $checktmpusers['uid'];
print('$uid: '.$uid.'<br />');

if($tmpusarr == 1){

if($host == 'localhost'){
  $confirmlink = 'http://localhost/exp3/registration.php?uid='.$uid;
}
else{
  $confirmlink = 'http://'.$host.'/registration.php?uid='.$uid;
}

$confirmEmail->email = $email;
$confirmEmail->setMessage($host,$uid);
$confirmEmail->sendMessage();

}
else{
  $_SESSION['gnemerr'] = 'The period (24 hours) to use this email for registration has expired<br />Register again at the <a href="registration.php">Registation page</a>';
}

}
else{
  $_SESSION['gnemerr'] = 'User with this e-mail is already registered<br />Log in using this e-mail';
}

}

}

?>