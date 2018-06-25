<?
session_start();
if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){

if(isset($_POST['qphid'])){
  $phid = preg_replace('/[^0-9-.jpegifn]/','',substr((string)$_POST['qphid'],0,25));
  if(isset($_SESSION['quplphoto'])){
    $pharr = explode("|sp|",$_SESSION['quplphoto']);
    $key = array_search($phid,$pharr);
    unset($pharr[$key]);
    unlink('tmpimg/'.$phid);
    $lpharr = count($pharr);
    if($lpharr != 0){
      $images = implode("|sp|",$pharr);
      $_SESSION['quplphoto'] = $images;
      print($images);
    }
    else{
      unset($_SESSION['quplphoto']);
    }
  }
}

}

?>