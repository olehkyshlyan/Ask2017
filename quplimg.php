<?

if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){

if(!isset($_SESSION['qsent']) && $_SESSION['qsent'] != true){
$_SESSION['qsent'] = true;

$aqerr = false;
$_SESSION['aqerr'] = '';

if(isset($_POST['quplimg'])){
unset($_POST['quplimg']);

// IMAGE
if(isset($_FILES['qimg']['tmp_name'])){
  //if($_FILES['photoupload']['tmp_name'] != ''){
  if($_FILES['qimg']['tmp_name'] != ''){
    if(is_uploaded_file($_FILES['qimg']['tmp_name'])){
	  $uploadedimg = true;
	}
	else{
	  $aqerr = true;
	  $_SESSION['aqerr'] .= "Possible file upload attack<br />";
	  //print("Possible file upload attack<br />");
	}
  }
  else{
	$aqerr = true;
	$_SESSION['aqerr'] .= "Image file is empty<br />";
	//print("Image file is empty<br />");
  }
}
else{
  $aqerr = true;
  $_SESSION['aqerr'] .= "Image file is not set<br />";
  //print("Image file is not set<br />");
}

if(isset($uploadedimg) && $uploadedimg == true){

// IMAGE TYPE
if(isset($_FILES['qimg']['type'])){
  if($_FILES['qimg']['type'] != ''){
    $ftype = $_FILES['qimg']['type'];
	if($ftype == 'image/jpeg' || $ftype == 'image/pjpeg' || $ftype == 'image/jpg' || $ftype == 'image/pjpg' || $ftype == 'image/png' || $ftype == 'image/x-png' || $ftype == 'image/gif'){
	  $correctimgtype = true;
	}
	else{
	  $aqerr = true;
	  $_SESSION['aqerr'] .= "Image file type is wrong. Allowed files: jpeg, jpg, png, gif<br />";
	  //print("Image file type is wrong. Allowed files: jpeg, jpg, png, gif<br />");
	}
  }
  else{
    $aqerr = true;
	$_SESSION['aqerr'] .= "Image file type is empty<br />";
	//print("Image file type is empty<br />");
  }
}
else{
  $aqerr = true;
  $_SESSION['aqerr'] .= "Image file type is not set<br />";
  //print("Image file type is not set<br />");
}

// IMAGE SIZE
if(isset($correctimgtype) && $correctimgtype == true){
  $imgparams = getimagesize($_FILES['qimg']['tmp_name']);
  if($imgparams[0] > 2000){
    $aqerr = true;
    $_SESSION['aqerr'] .= "Photo width is bigger than 2000 px<br />";
    //print("Photo width is bigger than 2000 px<br />");
  }
  if($imgparams[1] > 2000){
    $aqerr = true;
    $_SESSION['aqerr'] .= "Photo height is bigger than 2000 px<br />";
    //print("Photo height is bigger than 2000 px<br />");
  }
  if($imgparams[0] < 200){
    $aqerr = true;
    $_SESSION['aqerr'] .= "Photo width is less than 200 px<br />";
    //print("Photo width is less than 200 px<br />");
  }
  if($imgparams[1] < 200){
    $aqerr = true;
    $_SESSION['aqerr'] .= "Photo height is less than 200 px<br />";
    //print("Photo height is less than 200 px<br />");
  }
}

if($aqerr == false){
  if(isset($_SESSION['quplphoto'])){ $qpharrpieces = explode("|sp|",$_SESSION['quplphoto']); }
  else{ $qpharrpieces = array(); }
  $qpharrlen = count($qpharrpieces) + 1;
  if($qpharrlen <= 10){
    $uniqueid = gmdate('YmdHis').rand(1000,100000);
    $ext = strrchr($_FILES['qimg']['name'],".");
    if(copy($_FILES['qimg']['tmp_name'],'tmpimg/'.$uniqueid.$ext)){
      $qpharrpieces[] = $uniqueid.$ext;
      $_SESSION['quplphoto'] = implode("|sp|",$qpharrpieces);
    }
    else{
      $aqerr = true;
      $_SESSION['aqerr'] .= "Image was not saves into the temporary folder<br />";
    }
  }
  else{
    $aqerr = true;
    $_SESSION['aqerr'] .= "Only 10 images are allowed<br />";
  }
}

}


}

if($_SESSION['aqerr'] == ''){ unset($_SESSION['aqerr']); }

header('Location:http://'.$currenturl); exit();
}
}

?>