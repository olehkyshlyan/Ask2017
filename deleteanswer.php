<?
// delete answer
session_start();
if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){

if(isset($_POST['del'])){
  $del = substr((string)$_POST['del'],0,3);
  unset($_POST['del']);
  if($del == 'yes'){
  if(isset($_SESSION['atext'])){ unset($_SESSION['atext']); }
	if(isset($_SESSION['auplphoto'])){
	  $apharr = explode("|sp|",$_SESSION['auplphoto']);
	  foreach($apharr as $v){ unlink('tmpimg/'.$v); }
	  unset($_SESSION['auplphoto']);
	}
  }
}

}
?>