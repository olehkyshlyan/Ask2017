<?

if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){

if(isset($_POST['biguplph'])){
unset($_POST['biguplph']);

$buph = false;
$_SESSION['buph'] = '';

// IMAGE
if(isset($_FILES['bigphoto']['tmp_name'])){
  if($_FILES['bigphoto']['tmp_name'] != ''){
  if(is_uploaded_file($_FILES['bigphoto']['tmp_name'])){
	  $uploadedimg = true;
	  //$buph = true; $_SESSION['buph'] .= "Possible file upload attack<br />";
	}
	else{
	  $buph = true;
	  $_SESSION['buph'] .= "Possible file upload attack<br />";
	  //print("Possible file upload attack<br />");
	}
  }
  else{
	$buph = true;
	$_SESSION['buph'] .= "Image file is empty<br />";
	//print("Image file is empty<br />");
  }
}
else{
  $buph = true;
  $_SESSION['buph'] .= "Image file is not set<br />";
  //print("Image file is not set<br />");
}

if(isset($uploadedimg) && $uploadedimg == true){

// IMAGE TYPE
if(isset($_FILES['bigphoto']['type'])){
  if($_FILES['bigphoto']['type'] != ''){
  $ftype = $_FILES['bigphoto']['type'];
	if($ftype == 'image/jpeg' || $ftype == 'image/pjpeg' || $ftype == 'image/jpg' || $ftype == 'image/pjpg' || $ftype == 'image/png' || $ftype == 'image/x-png' || $ftype == 'image/gif'){
	  $correctimgtype = true;
	  //$buph = true; $_SESSION['buph'] .= "Image file type is wrong. Allowed files: jpeg, jpg, png, gif<br />";
	}
	else{
	  $buph = true;
	  $_SESSION['buph'] .= "Image file type is wrong. Allowed files: jpeg, jpg, png, gif<br />";
	  //print("Image file type is wrong. Allowed files: jpeg, jpg, png, gif<br />";);
	}
  }
  else{
    $buph = true;
    $_SESSION['buph'] .= "Image file type is empty<br />";
    //print("Image file type is empty<br />");
  }
}
else{
  $buph = true;
  $_SESSION['buph'] .= "Image file type is not set<br />";
  //print("Image file type is not set<br />");
}

// IMAGE SIZE
if(isset($correctimgtype) && $correctimgtype == true){
  $imgparams = getimagesize($_FILES['bigphoto']['tmp_name']);
  if($imgparams[0] > 300){
    $buph = true;
    $_SESSION['buph'] .= "Photo width is more than 300 px<br />";
    //print("Photo width is more than 300 px<br />");
  }
  if($imgparams[1] > 300){
    $buph = true;
    $_SESSION['buph'] .= "Photo height is more than 300 px<br />";
    //print("Photo height is more than 300 px<br />");
  }
  if($imgparams[0] < 100){
    $buph = true;
    $_SESSION['buph'] .= "Photo width is less than 100 px<br />";
    //print("Photo width is less than 100 px<br />");
  }
  if($imgparams[1] < 100){
    $buph = true;
    $_SESSION['buph'] .= "Photo height is less than 100 px<br />";
    //print("Photo height is less than 100 px<br />");
  }
}

if($buph == false){

try{

$ulphoto = $db->query("SELECT ulphoto FROM users WHERE uid='$suid';")->fetch(PDO::FETCH_ASSOC);
//print('$ulphoto: '); var_dump($ulphoto); print('<br />');

$uniqueid = gmdate('YmdHis').rand(1000,100000);
$ext = strrchr($_FILES['bigphoto']['name'],".");
$newphoto = $uniqueid.$ext;

$updres = $db->exec("UPDATE users SET ulphoto='$newphoto' WHERE uid='$suid';");

if($updres == 1){

if(!copy($_FILES['bigphoto']['tmp_name'],'ulphotos/'.$newphoto)){
  $_SESSION['buph'] .= 'Photo was not copied to the folder<br />';
}

if($ulphoto != false && $ulphoto['ulphoto'] != ''){
unlink('ulphotos/'.$ulphoto['ulphoto']);
}

}

}
catch(Exception $e){
  $_SESSION['buph'] .= $e->getMessage();
}

}

}

if($_SESSION['buph'] == ''){ unset($_SESSION['buph']); }

}

header('Location:http://'.$currenturl); exit();

}

?>