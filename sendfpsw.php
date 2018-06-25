<?
// SEND FORGOTTEN PASSWORD

if(isset($_POST['sendpsw'])){
unset($_POST['sendpsw']);

if(!isset($_SESSION['sendpsw'])){
$_SESSION['sendpsw'] = true;

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
        $_SESSION['fpemail'] = $em;
        //print('$email: '.$email.'<br />');
      }
      else{
        //print('E-mail contains unacceptable characters: '.$emmatches[0].'<br />');
        $_SESSION['pswemail'] = 'E-mail contains unacceptable characters: '.$emmatches[0].'<br />';
        $_SESSION['fpemail'] = $em;
      }
    }
    else{
      //print('E-mail is longer than 50 characters<br />');
      $_SESSION['pswemail'] = "E-mail is longer than 50 characters<br />";
      $_SESSION['fpemail'] = $em;
    }
  }
  else{
    //print('E-mail is empty<br />');
    $_SESSION['pswemail'] = "E-mail field is empty<br />";
  }
}
else{
  //print('E-mail is not set<br />');
  $_SESSION['pswemail'] = "E-mail is not set<br />";
}

if(isset($email)){

try{
include "db.php";

$qpsw = $db->query("SELECT psw FROM users WHERE email='$email';");
$ipsw = $qpsw->fetchAll(PDO::FETCH_ASSOC);
//print('$ipsw: '); var_dump($ipsw); print('<br />');
$сipsw = count($ipsw);
//print('$сipsw: '.$сipsw.'<br />');

if($сipsw == 1){
  $psw = (string)$ipsw[0]['psw'];
  $lpsw = strlen($psw);
  $flpsw = $lpsw-2;
  $fppsw = substr($psw,0,1);
  $sppsw = substr($psw,1,$flpsw);
  $sppsw = preg_replace('/[a-z0-9]/i','*',$sppsw);
  $thppsw = substr($psw,-1,1);
  $password = $fppsw.$sppsw.$thppsw;
}
else{
  $_SESSION['pswemail'] = 'This e-mail is not registered<br />';
}

}
catch(Exception $e){
  $_SESSION['dberr'] = $e->getMessage()."<br />";
}

}

if(isset($password)){

$to = $email;
$subject = 'Request for a password at the site Questions and Answers';

$message = '
Your password at the site "Questions and Answers" is:<br />'.$password.'<br />
If you did not request for your password at the site "Questions and Answers", just delete this mail';

$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";
$headers .= "From: Web-site <questionsandanswers@mail.com>\r\n";

$sentmail = mail($to,$subject,$message,$headers);
//print('$sentmail: '); var_dump($sentmail); print('<br />');

if($sentmail == true){
  $_SESSION['pswemail'] = 'Password was sent to:<br />'.$email;
}
else{
  $_SESSION['pswemail'] = 'Password was not sent<br />Try again';
}

}

header('Location:http://'.$currenturl); exit();

}
}

?>