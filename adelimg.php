<?
session_start();
if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){

if(isset($_POST['aphid'])){
  $aphid = preg_replace('/[^0-9-.jpegifn]/','',substr((string)$_POST['aphid'],0,25));
  if(isset($_SESSION['auplphoto'])){
    $apharr = explode("|sp|",$_SESSION['auplphoto']);
	$key = array_search($aphid,$apharr);
	unset($apharr[$key]);
	unlink('tmpimg/'.$aphid);
	$lapharr = count($apharr);
	if($lapharr != 0){
	  $aimages = implode("|sp|",$apharr);
	  $_SESSION['auplphoto'] = $aimages;
	  print($aimages);
	}
	else{
	  unset($_SESSION['auplphoto']);
	}
  }
}

}

?>