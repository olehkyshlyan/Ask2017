<?

if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){
if(isset($_POST['logout'])){
  unset($_POST['logout']);
  $_SESSION = array();
  if(isset($_COOKIE[session_name()])){ unset($_COOKIE[session_name()]); }
  session_destroy();
}
}

header('Location:http://'.$currenturl); exit();

?>