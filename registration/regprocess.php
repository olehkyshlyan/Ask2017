<?
// user registration
if(isset($_POST['btcracc'])){
unset($_POST['btcracc']);

$regerr = false;
$_SESSION['fnlnerr'] = '';

// FIRST NAME
if(isset($_POST['fn'])){
  if($_POST['fn'] != ''){
    $fn = mb_substr((string)$_POST['fn'],0,31,'UTF-8');
    unset($_POST['fn']);
    $lfn = mb_strlen($fn,'UTF-8');
    if($lfn <= 30){
      $pfnmatch = preg_match('/[^\p{N}\p{L}\p{Zs}\-\']+/u',$fn,$fnmatches);
      if($pfnmatch != 1){
        $fn = preg_replace('/[^\p{N}\p{L}\p{Zs}\-\']+/u','',$fn);
        $fname = $fn;
        $_SESSION['regfn'] = $fn;
        //print('$fname: '.$fname.'<br />');
      }
      else{
        //print('First name contains unacceptable characters<br />');
        $_SESSION['regfn'] = $fn;
        $regerr = true;
        $_SESSION['fnlnerr'] .= 'First name contains unacceptable characters: '.$fnmatches[0].'<br />';
      }
    }
    else{
      //print('First name is longer than 30 characters<br />');
      $regerr = true;
      $_SESSION['fnlnerr'] .= 'First name is longer than 30 characters<br />';
    }
  }
  else{
  //print('First name is empty<br />');
	$regerr = true;
	$_SESSION['fnlnerr'] .= "First name field is empty<br />";
  }
}
else{
  //print('First name is not set<br />');
  $regerr = true;
  $_SESSION['fnlnerr'] .= "First name is not set<br />";
}

// LAST NAME
if(isset($_POST['ln'])){
  if($_POST['ln'] != ''){
    $ln = mb_substr((string)$_POST['ln'],0,31,'UTF-8');
    unset($_POST['ln']);
    $lln = mb_strlen($ln,'UTF-8');
    if($lln <= 30){
      $plnmatch = preg_match('/[^\p{N}\p{L}\p{Zs}\-\']+/u',$ln,$lnmatches);
      if($plnmatch != 1){
        $ln = preg_replace('/[^\p{N}\p{L}\p{Zs}\-\']+/u','',$ln);
        $lname = $ln;
        $_SESSION['regln'] = $ln;
        //print('$lname: '.$lname.'<br />');
      }
      else{
        //print('Last name contains unacceptable characters<br />');
        $regerr = true;
        $_SESSION['fnlnerr'] .= 'Last name contains unacceptable characters: '.$lnmatches[0].'<br />';
      }
    }
    else{
      //print('Last name is longer than 30 characters<br />');
      $regerr = true;
      $_SESSION['fnlnerr'] .= 'Last name is longer than 30 characters<br />';
    }
  }
  else{
    //print('Last name is empty<br />');
	$regerr = true;
	$_SESSION['fnlnerr'] .= "Last name field is empty<br />";
  }
}
else{
  //print('Last name is not set<br />');
  $regerr = true;
  $_SESSION['fnlnerr'] .= "Last name is not set<br />";
}

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
        $_SESSION['regemail'] = $em;
        //print('$email: '.$email.'<br />');
      }
      else{
        //print('E-mail contains unacceptable characters: '.$emmatches[0].'<br />');
        $regerr = true;
        $_SESSION['emailerr'] = 'E-mail contains unacceptable characters: '.$emmatches[0].'<br />';
        $_SESSION['regemail'] = $em;
      }
    }
    else{
      //print('E-mail is longer than 50 characters<br />');
      $regerr = true;
      $_SESSION['emailerr'] = "E-mail is longer than 50 characters<br />";
      $_SESSION['regemail'] = $em;
    }
  }
  else{
    //print('E-mail is empty<br />');
    $regerr = true;
    $_SESSION['emailerr'] = "E-mail field is empty<br />";
  }
}
else{
  //print('E-mail is not set<br />');
  $regerr = true;
  $_SESSION['emailerr'] = "E-mail is not set<br />";
}

// PASSWORD
if(isset($_POST['psw'])){
  if($_POST['psw'] != ''){
    $psw = mb_substr((string)$_POST['psw'],0,21,'UTF-8');
    $psw = mb_strtolower($psw,'UTF-8');
    $lpsw = mb_strlen($psw,'UTF-8');
    if($lpsw >= 10 && $lpsw <= 20){
      $ppmatch = preg_match('/[^a-z0-9]/i',$psw,$pswmatches);
      if($ppmatch != 1){
        $password = preg_replace('/[^a-z0-9]/i','',$psw);
      }
      else{
        //print('Password must contain only numbers and latin letters<br />');
        $regerr = true;
        $_SESSION['pswerr'] = 'Password must contain only numbers and latin letters<br />';
      }
    }
    elseif($lpsw < 10){
      //print('Password is shorter than 10 characters<br />');
      $regerr = true;
      $_SESSION['pswerr'] = 'Password is shorter than 10 characters<br />';
    }
    elseif($lpsw > 20){
      //print('Password is longer than 20 characters<br />');
      $regerr = true;
      $_SESSION['pswerr'] = 'Password is longer than 20 characters<br />';
    }
  }
  else{
    //print('Password field is empty<br />');
    $regerr = true;
    $_SESSION['pswerr'] = "Password field is empty<br />";
  }
}
else{
  //print('Password is not set<br />');
  $regerr = true;
  $_SESSION['pswerr'] = "Password is not set<br />";
}

if($_SESSION['fnlnerr'] == ''){
  unset($_SESSION['fnlnerr']);
}

if($regerr == false){
$dt = gmdate('Ymd');
$uid = gmdate('YmdHis').rand(1000,100000);
//print('$uid: '.$uid.'<br />');

try{

$checkusers = $db->query("SELECT id FROM users WHERE lemail='$email'");
$chusers = $checkusers->fetchAll(PDO::FETCH_ASSOC);
//print('$chusers: '); var_dump($chusers); print('<br />');
$usarr = count($chusers);
//print('$usarr: '.$usarr.'<br />');

if($usarr == 0){

$checktmpusers = $db->query("SELECT id FROM tmpusers WHERE email='$email'");
$chtmpus = $checktmpusers->fetchAll(PDO::FETCH_ASSOC);
//print('$chtmpus: '); var_dump($chtmpus); print('<br />');
$tmpusarr = count($chtmpus);

if($tmpusarr == 0){

$insres = $db->exec("INSERT INTO tmpusers (uid,email,fname,lname,psw,dt) VALUES ('$uid','$email','$fname','$lname','$password','$dt')");
//print('$insres: '); var_dump($insres); print('<br />');
if($insres == 1){
$regdone = true;
}
else{
$_SESSION['tmpregerr'] = 'Registration error<br />';
}

}
else{
$_SESSION['tmpregerr'] = 'This e-mail was already used for registration<br />Click <a href="getemail.php">here</a> to get another confirmation e-mail and complete the registration';
}

}
else{
$_SESSION['tmpregerr'] = 'User with this e-mail is already registered<br />';
}

}
catch(Exception $e){
$_SESSION['tmpregerr'] = "Error: ".$e->getMessage()."<br />";
}
}

if(isset($regdone) && $regdone == true){

if($host == 'localhost'){
  $confirmlink = 'http://localhost/exp3/registration.php?uid='.$uid;
}
else{
  $confirmlink = 'http://'.$host.'/registration.php?uid='.$uid;
}

$to = $email;
$subject = 'Registration at the site Questions and Answers';

$message = '
You have registered at the site "Questions and Answers".<br />
Please, go to this link <a href="'.$confirmlink.'">'.
$confirmlink
.'</a> to complete your registration.<br />
This link will be active during next 24 hours.<br />
If you did not register at the site "Questions and Answers", just delete this letter.<br />
Thanks :-)
';

$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";
$headers .= "From: Web-site <askandanswerhere@mail.com>\r\n";

$sentmail = mail($to,$subject,$message,$headers);
//print('$sentmail: '); var_dump($sentmail); print('<br />');

if($sentmail == true){
$_SESSION['sentemail'] = 'Confirmation e-mail has been sent to: '.$email.'<br />Follow the instructions in the e-mail to complete the registration<br/ >If you did not receive the e-mail click <a href="getemail.php">here</a> to get another one';
unset($_SESSION['regfn'],$_SESSION['regln'],$_SESSION['regemail']);
}
else{
$_SESSION['tmpregerr'] = 'The e-mail to complete the registration has NOT been sent.<br />Go to <a href="getemail.php">this</a> page to get a new mail.';
}

}

header('Location:http://'.$currenturl); exit();

}

?>