<?
session_start();
$currenturl = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

if(isset($_POST['sendpsw'])){
  include "sendfpsw.php";
}

//print('SESSION: '); print_r($_SESSION); print('<br />');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="robots" content="noindex,nofollow" />
  
  <title></title>
  
  <link rel="stylesheet" type="text/css" href="css/forgotpsw.css" />
  <style type="text/css">
    
  </style>
  
  <script type='text/javascript' src='js/jquery.1.8.3.js'></script>
  <script type='text/javascript' src='js/jquery-ui.js'></script>
  <script type='text/javascript' src=''></script>
  <script type="text/javascript">
    function authfchc(t,c){
	  if(c == 'b'){ jQuery(t).animate({'border-color':'rgb(25,142,255)'},300); }
	  else if(c == 'g'){ jQuery(t).animate({'border-color':'rgb(215,215,215)'},300); }
    }
  </script>
</head>

<body>

<div id="forgpsw">
  <? if(isset($_SESSION['dberr'])){ ?><div id="dberr"><? print($_SESSION['dberr']); ?></div><? unset($_SESSION['dberr']); } ?>
  <? if(isset($_SESSION['pswemail'])){ ?><div id="sentfpsw"><? print($_SESSION['pswemail']); ?></div><? unset($_SESSION['pswemail']); } ?>
  <div id="wfpsw"><span>Forgot password ?</span></div>
  <div id="sepline1"></div>
  <form method="post" action="forgotpsw.php">
  <div id="entemail">Enter the e-mail you use to log in on this site</div>
	<div id="wemail"><input id="email" name="email" type="text" maxlength="50" placeholder="E-mail" onfocus="authfchc(this,'b');" onblur="authfchc(this,'g');" value="<? if(isset($_SESSION['fpemail'])){ print($_SESSION['fpemail']); unset($_SESSION['fpemail']); } ?>" /></div>
	<div id="wsendpsw"><input id="sendpsw" name="sendpsw" type="submit" value="Send password" /></div>
  </form>
	<div id="sepline2"></div>
	<div id="backto">
	  <div id="prevpage"><a id="lprevpage" href="auth.php">Previous page</a></div>
	  <div id="mainpage"><a id="lmainpage" href="index.php">Main page</a></div>
	</div>
</div>
<?
if(isset($_SESSION['sendpsw'])){ unset($_SESSION['sendpsw']); }
?>
</body>

</html>
