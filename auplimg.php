<?

if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){

if(!isset($_SESSION['asent']) && $_SESSION['asent'] != true){
$_SESSION['asent'] = true;

$aaerr = false;
$_SESSION['aaerr'] = '';

if(isset($_POST['auplimg'])){
unset($_POST['auplimg']);

// IMAGE
if(isset($_FILES['aimg']['tmp_name'])){
  if($_FILES['aimg']['tmp_name'] != ''){
    if(is_uploaded_file($_FILES['aimg']['tmp_name'])){
	  $uploadedimg = true;
	}
	else{
	  $aaerr = true;
	  $_SESSION['aaerr'] .= "Possible file upload attack<br />";
	  //print("Possible file upload attack<br />");
	}
  }
  else{
	$aaerr = true;
	$_SESSION['aaerr'] .= "Image file is empty<br />";
	//print("Image file is empty<br />");
  }
}
else{
  $aaerr = true;
  $_SESSION['aaerr'] .= "Image file is not set<br />";
  //print("Image file is not set<br />");
}

if(isset($uploadedimg) && $uploadedimg == true){

// IMAGE TYPE
if(isset($_FILES['aimg']['type'])){
  if($_FILES['aimg']['type'] != ''){
    $ftype = $_FILES['aimg']['type'];
	if($ftype == 'image/jpeg' || $ftype == 'image/pjpeg' || $ftype == 'image/jpg' || $ftype == 'image/pjpg' || $ftype == 'image/png' || $ftype == 'image/x-png' || $ftype == 'image/gif'){
	  $correctimgtype = true;
	}
	else{
	  $aaerr = true;
	  $_SESSION['aaerr'] .= "Image file type is wrong. Allowed files: jpeg, jpg, png, gif<br />";
	  //print("Image file type is wrong. Allowed files: jpeg, jpg, png, gif<br />");
	}
  }
  else{
    $aaerr = true;
	$_SESSION['aaerr'] .= "Image file type is empty<br />";
	//print("Image file type is empty<br />");
  }
}
else{
  $aaerr = true;
  $_SESSION['aaerr'] .= "Image file type is not set<br />";
  //print("Image file type is not set<br />");
}

// IMAGE SIZE
if(isset($correctimgtype) && $correctimgtype == true){
  $imgparams = getimagesize($_FILES['aimg']['tmp_name']);
  if($imgparams[0] > 2000){
    $aaerr = true;
	$_SESSION['aaerr'] .= "Photo width is more than 2000 px<br />";
	//print("Photo width is more than 2000 px<br />");
  }
  if($imgparams[1] > 2000){
    $aaerr = true;
	$_SESSION['aaerr'] .= "Photo height is more than 2000 px<br />";
	//print("Photo height is more than 2000 px<br />");
  }
  if($imgparams[0] < 200){
    $aaerr = true;
	$_SESSION['aaerr'] .= "Photo width is less than 200 px<br />";
	//print("Photo width is less than 200 px<br />");
  }
  if($imgparams[1] < 200){
    $aaerr = true;
	$_SESSION['aaerr'] .= "Photo height is less than 200 px<br />";
	//print("Photo height is less than 200 px<br />");
  }
}

if($aaerr == false){
  if(isset($_SESSION['auplphoto'])){ $apharrpieces = explode("|sp|",$_SESSION['auplphoto']); }
  else{ $apharrpieces = array(); }
  $apharrlen = count($apharrpieces) + 1;
  if($apharrlen <= 10){
  $uniqueid = gmdate('YmdHis').rand(1000,100000);
	$ext = strrchr($_FILES['aimg']['name'],".");
	if(copy($_FILES['aimg']['tmp_name'],'tmpimg/'.$uniqueid.$ext)){
	  $apharrpieces[] = $uniqueid.$ext;
	  $_SESSION['auplphoto'] = implode("|sp|",$apharrpieces);
	}
	else{ $aaerr = true; $_SESSION['aaerr'] .= "Image was not saves into the temporary folder<br />"; }
  }
  else{
    $aaerr = true;
    $_SESSION['aaerr'] .= "Only 10 images are allowed<br />";
  }
}

}

}
if($_SESSION['aaerr'] == ''){
  unset($_SESSION['aaerr']);
}

header('Location:http://'.$currenturl); exit();
}

}

?>