<?
// change small user photo
if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){

if(isset($_POST['smalluplph'])){
unset($_POST['smalluplph']);

$suph = false;
$_SESSION['suph'] = '';

// IMAGE
if(isset($_FILES['smallphoto']['tmp_name'])){
  if($_FILES['smallphoto']['tmp_name'] != ''){
  if(is_uploaded_file($_FILES['smallphoto']['tmp_name'])){
	  $uploadedimg = true;
	}
	else{
	  $suph = true;
	  $_SESSION['suph'] .= "Possible file upload attack<br />";
	  //print("Possible file upload attack<br />");
	}
  }
  else{
	$suph = true;
	$_SESSION['suph'] .= "Image file is empty<br />";
	//print("Image file is empty<br />");
  }
}
else{
  $suph = true;
  $_SESSION['suph'] .= "Image file is not set<br />";
  //print("Image file is not set<br />");
}

if(isset($uploadedimg) && $uploadedimg == true){

// IMAGE TYPE
if(isset($_FILES['smallphoto']['type'])){
  if($_FILES['smallphoto']['type'] != ''){
  $ftype = $_FILES['smallphoto']['type'];
	if($ftype == 'image/jpeg' || $ftype == 'image/pjpeg' || $ftype == 'image/jpg' || $ftype == 'image/pjpg' || $ftype == 'image/png' || $ftype == 'image/x-png' || $ftype == 'image/gif'){
	  $correctimgtype = true;
	}
	else{
	  $suph = true;
	  $_SESSION['suph'] .= "Image file type is wrong. Allowed files: jpeg, jpg, png, gif<br />";
	  //print("Image file type is wrong. Allowed files: jpeg, jpg, png, gif<br />";);
	}
  }
  else{
    $suph = true;
    $_SESSION['suph'] .= "Image file type is empty<br />";
    //print("Image file type is empty<br />");
  }
}
else{
  $suph = true;
  $_SESSION['suph'] .= "Image file type is not set<br />";
  //print("Image file type is not set<br />");
}

// IMAGE SIZE
if(isset($correctimgtype) && $correctimgtype == true){
  $imgparams = getimagesize($_FILES['smallphoto']['tmp_name']);
  if($imgparams[0] > 100){
    $suph = true;
    $_SESSION['suph'] .= "Photo width is more than 100 px<br />";
    //print("Photo width is more than 100 px<br />");
  }
  if($imgparams[1] > 100){
    $suph = true;
    $_SESSION['suph'] .= "Photo height is more than 100 px<br />";
    //print("Photo height is more than 100 px<br />");
  }
  if($imgparams[0] < 50){
    $suph = true;
    $_SESSION['suph'] .= "Photo width is less than 50 px<br />";
    //print("Photo width is less than 50 px<br />");
  }
  if($imgparams[1] < 50){
    $suph = true;
    $_SESSION['suph'] .= "Photo height is less than 50 px<br />";
    //print("Photo height is less than 50 px<br />");
  }
}

if($suph == false){

try{

$usphoto = $db->query("SELECT usphoto FROM users WHERE uid='$suid';")->fetch(PDO::FETCH_ASSOC);
//print('$usphoto: '); var_dump($usphoto); print('<br />');

$uniqueid = gmdate('YmdHis').rand(1000,100000);
$ext = strrchr($_FILES['smallphoto']['name'],".");
$newphoto = $uniqueid.$ext;

$updres = $db->exec("UPDATE users SET usphoto='$newphoto' WHERE uid='$suid';");

if($updres == 1){

// if UPDATE is OK
// copy NEW small photo to the folder and change session element value
if(copy($_FILES['smallphoto']['tmp_name'],'usphotos/'.$newphoto)){
  $_SESSION['usphoto'] = $newphoto;
}
else{
  $_SESSION['suph'] .= 'Photo was not copied to the folder<br />';
}

// delete OLD small photo from the folder
if($usphoto != false && $usphoto['usphoto'] != ''){
unlink('usphotos/'.$usphoto['usphoto']);
}

}

}
catch(Exception $e){
  $_SESSION['suph'] .= $e->getMessage();
}

}

}

if($_SESSION['suph'] == ''){ unset($_SESSION['suph']); }

}

header('Location:http://'.$currenturl); exit();

}

?>