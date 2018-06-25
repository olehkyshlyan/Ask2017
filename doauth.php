<?

if(isset($_POST['btlogin'])){
unset($_POST['btlogin']);

$autherr = false;

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
        $lemail = $em;
        $_SESSION['authemail'] = $em;
        //print('$lemail: '.$lemail.'<br />');
      }
      else{
        //print('E-mail contains unacceptable characters: '.$emmatches[0].'<br />');
        $autherr = true;
        $_SESSION['emautherr'] = 'E-mail contains unacceptable characters: '.$emmatches[0].'<br />';
        $_SESSION['authemail'] = $em;
      }
    }
    else{
      //print('E-mail is longer than 50 characters<br />');
      $autherr = true;
      $_SESSION['emautherr'] = "E-mail is longer than 50 characters<br />";
      $_SESSION['authemail'] = $em;
    }
  }
  else{
    //print('E-mail is empty<br />');
    $autherr = true;
	$_SESSION['emautherr'] = "E-mail field is empty<br />";
  }
}
else{
  //print('E-mail is not set<br />');
  $autherr = true;
  $_SESSION['emautherr'] = "E-mail is not set<br />";
}

// PASSWORD
if(isset($_POST['psw'])){
  if($_POST['psw'] != ''){
  $psw1 = mb_strtolower(mb_substr((string)$_POST['psw'],0,21,'UTF-8'),'UTF-8');
	$lpsw1 = mb_strlen($psw1,'UTF-8');
	$psw2 = strtolower(substr($psw1,0,21));
	$psw2 = preg_replace('/[^a-z0-9]/i','',$psw2);
	$lpsw2 = strlen($psw2);
	if($lpsw1 == $lpsw2){
	  if($lpsw2 >= 10 && $lpsw2 <= 20){
	    $password = $psw2;
      //print('$password: '.$password.'<br />');
	  }
	  elseif($lpsw2 < 10){
	    //print('Password is shorter than 10 characters<br />');
      $autherr = true;
      $_SESSION['pswautherr'] = "Password is shorter than 10 characters<br />";
	  }
	  elseif($lpsw2 > 20){
	    //print('Password is longer than 20 characters<br />');
      $autherr = true;
      $_SESSION['pswautherr'] = "Password is longer than 20 characters<br />";
	  }
	}
	else{
	  //print('Password must contain only numbers and latin letters<br />');
	  $autherr = true;
	  $_SESSION['pswautherr'] = "Password must contain only numbers and latin letters<br />";
	}
  }
  else{
    //print('Password field is empty<br />');
    $autherr = true;
    $_SESSION['pswautherr'] = "Password field is empty<br />";
  }
}
else{
  //print('Password is not set<br />');
  $autherr = true;
  $_SESSION['pswautherr'] = "Password is not set<br />";
}

if(isset($lemail) && isset($password)){

try
{

$checkauth = $db->query("SELECT * FROM users WHERE lemail = '$lemail' AND utype != 'admin'")->fetchAll(PDO::FETCH_ASSOC);
print('$checkauth: '); var_dump($checkauth); print('<br />');
$arrauth = count($checkauth);

if($arrauth == 1){

if($checkauth[0]['psw'] == $password){
$auth = true;
$_SESSION['euser'] = true;
$_SESSION['utype'] = $checkauth[0]['utype'];
$_SESSION['uid'] = $checkauth[0]['uid'];
$_SESSION['fname'] = $checkauth[0]['fname'];
$_SESSION['lname'] = $checkauth[0]['lname'];
$_SESSION['usphoto'] = $checkauth[0]['usphoto'];
$_SESSION['blocked'] = $checkauth[0]['blocked'];
}
else{
$_SESSION['pswautherr'] = "Password is wrong<br />";
}

}
else{
$_SESSION['emautherr'] = "E-mail is wrong<br />";
}

}
catch(Exception $e){
$_SESSION['autherr'] = "Error: ".$e->getMessage()."<br />";
}

}

if(isset($auth) && $auth == true){

unset($_SESSION['authemail']);

if(isset($_SESSION['currenturl'])){
  header('Location:http://'.$_SESSION['currenturl']); exit();
}
else{
  if($host == 'localhost'){
    header('Location:http://'.$host.'/exp3/index.php'); exit();
  }
  else{
    header('Location:http://'.$host); exit();
  }
}

}

header('Location:http://'.$currenturl); exit();

}

?>