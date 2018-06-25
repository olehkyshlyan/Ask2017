<?
// SETS A SESSION ELEMENT TO KEEP THE QUESTION WINDOW OPEN OR CLOSED
session_start();
if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){

require "categories.php";

if(isset($_POST['open'])){
  $op = substr((string)$_POST['open'],0,3);
  unset($_POST['open']);
  if($op == 'yes'){ $_SESSION['qwopen'] = true; }
}

if(isset($_POST['close'])){
  $cl = substr((string)$_POST['close'],0,3);
  unset($_POST['close']);
  if($cl == 'yes'){ unset($_SESSION['qwopen']); }
}

// DELETING THE QUESTION AND ITS IMAGES
if(isset($_POST['del'])){
  $del = substr((string)$_POST['del'],0,3);
  unset($_POST['del']);
  if($del == 'yes'){
    $selCat = '<select id="selectCategory" name="categorylink" onchange="selsub(this);"><option value="chooseCategory" selected="selected">Choose category</option>';
	foreach($categories as $k=>$v){ $selCat .= '<option value="'.$k.'">'.$v.'</option>'; }
	$selCat .= '</select>';
	print($selCat);
	unset($_SESSION['qwopen']);
	if(isset($_SESSION['qtext'])){ unset($_SESSION['qtext']); }
	if(isset($_SESSION['qdetails'])){ unset($_SESSION['qdetails']); }
	if(isset($_SESSION['quplphoto'])){
	  $pharr = explode("|sp|",$_SESSION['quplphoto']);
	  foreach($pharr as $v){ unlink('tmpimg/'.$v); }
	  unset($_SESSION['quplphoto']);
	}
	if(isset($_SESSION['cat'])){ unset($_SESSION['cat']); }
	if(isset($_SESSION['subcat'])){ unset($_SESSION['subcat']); }
  }
}
}

?>