<?

if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){

if(!isset($_SESSION['qsent']) && $_SESSION['qsent'] != true){
$_SESSION['qsent'] = true;

$aqerr = false;
$_SESSION['aqerr'] = '';

$addquestion = false;
if(isset($_POST['addquestion'])){
unset($_POST['addquestion']);
$addquestion = true;

if(isset($_SESSION['uid'])){
  $uid = $_SESSION['uid'];
  //$aqerr = true; $_SESSION['aqerr'] .= "User's 'id' is undefined<br />";
}
else{
  $aqerr = true;
  $_SESSION['aqerr'] .= "User's 'id' is undefined<br />";
}

if(isset($_SESSION['utype'])){
  $utype = $_SESSION['utype'];
  //print('$utype: '.$utype .'<br />');
  //$aqerr = true; $_SESSION['aqerr'] .= "User's type is undefined<br />";
}
else{
  //print('Type of user\'s type is undefined<br />');
  $aqerr = true;
  $_SESSION['aqerr'] .= "User's type is undefined<br />";
}

if(isset($_SESSION['fname'])){
  $fname = $_SESSION['fname'];
  //print('$fname: '.$fname .'<br />');
  //$aqerr = true; $_SESSION['aqerr'] .= "User's first name is undefined<br />";
}
else{
  //print('User\'s first name is undefined<br />');
  $aqerr = true;
  $_SESSION['aqerr'] .= "User's first name is undefined<br />";
}

if(isset($_SESSION['lname'])){
  $lname = $_SESSION['lname'];
  //print('$lname: '.$lname .'<br />');
  //$aqerr = true; $_SESSION['aqerr'] .= "User's last name is undefined<br />";
}
else{
  //print('User\'s last name is undefined<br />');
  $aqerr = true;
  $_SESSION['aqerr'] .= "User's last name is undefined<br />";
}

// QUESTION TEXT
if(isset($_POST['questiontext'])){
  $qt = mb_substr((string)$_POST['questiontext'],0,141,'UTF-8');
  unset($_POST['questiontext']);
  if(is_string($qt)){
    $qt = str_replace(array("\x0A\x0D","\x0D\x0A","\x0A","\x0D"),"\n",$qt);
    $lqt = mb_strlen($qt,'UTF-8');
    if($lqt <= 140){
      if($qt != ''){
        preg_match_all('/[\\n]/u',$qt,$qtmatches,PREG_OFFSET_CAPTURE);
        $qtnlnum = count($qtmatches[0]);
        if($qtnlnum > 0){
          $qtmaxnl = 9;
          $qtmonl = 0;
          // new line chars number minus one (because of counting from zero)
          if($qtnlnum <= $qtmaxnl){ $qtmonl = $qtnlnum - 1; }
          else{ $qtmonl = $qtmaxnl - 1; }
          // last position of new line char in question text
          $qtlnlp = $qtmatches[0][$qtmonl][1];
          // difference between max number of new line chars and real number of new line chars (minus one)
          $qtdiff = $qtmaxnl - $qtmonl;
          $qtrest = $qtdiff * 70;
          
          // first part of question text from zero till last new line char position
          $qtfp = mb_strcut($qt,0,$qtlnlp,'UTF-8');
          // second part of question text from last new line char position till the end
          $qtsp = mb_strcut($qt,$qtlnlp);
          $qtsp = str_replace("\n",'',$qtsp);
          $qtsp = mb_substr($qtsp,0,$qtrest,'UTF-8');
          $qtsp = "\n".$qtsp;
          $qt = $qtfp.$qtsp;
        }
        
        $qtext = addslashes($qt);
        $qtext = htmlentities($qtext,ENT_QUOTES,'UTF-8');
        $_SESSION['qtext'] = $qt;
        //$aqerr = true;
        //$_SESSION['aqerr'] .= "QUESTION TEXT ERROR<br />";
        //print('$questiontext: '.$questiontext.'<br />');
      }
      else{
        $aqerr = true;
        $_SESSION['aqerr'] .= "Question text is empty<br />";
      }
    }
    else{
      $aqerr = true;
      $_SESSION['aqerr'] .= "Question text is longer than 140 characters<br />";
    }
  }
  else{
    $aqerr = true;
    $_SESSION['aqerr'] .= "Question text is not a string<br />";
  }
}
else{
  $aqerr = true;
  $_SESSION['aqerr'] .= "Question text is undefined<br />";
}

// QUESTION DETAILS
$questiondetails = '';
if(isset($_POST['questiondetails'])){
  $qd = mb_substr((string)$_POST['questiondetails'],0,1001,'UTF-8');
  unset($_POST['questiondetails']);
  if($qd != ''){
  if(is_string($qd)){
    $qd = str_replace(array("\x0A\x0D","\x0D\x0A","\x0A","\x0D"),"\n",$qd);
    $lqd = mb_strlen($qd,'UTF-8');
    if($lqd <= 1000){
      if($qd != ''){
        preg_match_all('/[\\n]/u',$qd,$qdmatches,PREG_OFFSET_CAPTURE);
        $qdnlnum = count($qdmatches[0]);
        if($qdnlnum > 0){
          $qdmaxnl = 19;
          $qdmonl = 0;
          if($qdnlnum <= $qdmaxnl){ $qdmonl = $qdnlnum - 1; }
          else{ $qdmonl = $qdmaxnl - 1; }
          $qdlnlp = $qdmatches[0][$qdmonl][1];
          $qddiff = $qdmaxnl - $qdmonl;
          $qdrest = $qddiff * 70;
          $qdfp = mb_strcut($qd,0,$qdlnlp,'UTF-8');
          $qdsp = mb_strcut($qd,$qdlnlp);
          $qdsp = str_replace("\n",'',$qdsp);
          $qdsp = mb_substr($qdsp,0,$qdrest,'UTF-8');
          $qdsp = "\n".$qdsp;
          $qd = $qdfp.$qdsp;
        }
        $qdetails = addslashes($qd);
        $qdetails = htmlentities($qdetails,ENT_QUOTES,'UTF-8');
        $_SESSION['qdetails'] = $qd;
        //$aqerr = true;
        //$_SESSION['aqerr'] .= "QUESTION DETAILS ERROR<br />";
      }
    }
    else{
      $aqerr = true;
      $_SESSION['aqerr'] .= "Question details are longer than 1000 characters<br />";
      $_SESSION['qdetails'] = $qd;
    }
  }
  else{
    $aqerr = true;
    $_SESSION['aqerr'] .= "Question details are not a string<br />";
  }
  }
}

// создание папки 'imgfolder' для постоянного хранения фото. Название папки - 'год + месяц'
// присвоение переменной 'qimages' массива c названиями фото из элемента сессии 'quplphoto'
// фото временно хранятся в папке временного хранения 'tmpimg' до момента успешного добавления ответа
$qimages = '';
$imgfolder = '';
if(isset($_SESSION['quplphoto'])){
  $imgfolder = gmdate('Ym');
  if(!is_dir('images/'.$imgfolder)){
    $crfolder = mkdir('images/'.$imgfolder);
	if($crfolder == false){
	  $aqerr = true;
	  $_SESSION['aqerr'] .= "Folder for images was not created<br />";
	}
  }
  if(is_dir('images/'.$imgfolder)){
    $qimages = $_SESSION['quplphoto'];
  }
  else{
    $aqerr = true;
    $_SESSION['aqerr'] .= "Folder for images does not exist<br />";
  }
}

// сканируется папка временного хранения фото-файлов для формирования соответствующего массива
// из массива удалаются названия файлов, которые не имеют расширения '.jpg','.jpeg','.png','.gif'
// например (в Windows XP): 'Thumbs.db' и файлы с точками, количество которых указывает на уровень вложенности папки
$tmpimages = scandir('tmpimg');
if($tmpimages != false){
foreach($tmpimages as $k=>$v){
  $tiext = strrchr($v,'.');
  if(!in_array($tiext,array('.jpg','.jpeg','.png','.gif'))){
	unset($tmpimages[$k]);
  }
}

// при каждом добавлении вопроса из папки временного хранения фото-файлов удаляются фото-файлы, дата которых меньше текущей даты на 1 день
// дата фото-файла определяется по первым восьми символам названия файла
// название фото-файла формируется при загрузке файла функцией даты до уровня 'секунды' + случайное число
$currdate = (int)gmdate('Ymd');
foreach($tmpimages as $k=>$v){
  $imgdate = strtotime(substr($v,0,8));
  $imgdate = strtotime('+1 day',$imgdate);
  $imgdate = (int)gmdate('Ymd',$imgdate);
  if($imgdate < $currdate){
	unlink('tmpimg/'.$v);
  }
}
}

if(isset($_SESSION['usphoto'])){
if($_SESSION['usphoto'] != ''){
  $usphoto = basename($_SESSION['usphoto']);
  $newfile = 'uphotos/'.$usphoto;
  if(!file_exists($newfile)){
  if(!copy('usphotos/'.$usphoto,$newfile)){
    $aqerr = true;
    $_SESSION['aqerr'] .= "User's photo was not copied<br />";
  }
  }
}
}

// CATEGORY
if(isset($_POST['categorylink'])){
  $catlink = preg_replace('/[^a-z]/i','',substr((string)$_POST['categorylink'],0,17));
  if(is_string($catlink)){
	if(isset($categories[$catlink])){
	  $categorylink = $catlink;
	  //print('$categorylink: '.$categorylink.'<br />');
	  $categoryname = $categories[$catlink];
	  //print('$categoryname: '.$categoryname.'<br />');
	}
	else{
	  $aqerr = true;
	  $_SESSION['aqerr'] .= "Categorylink is wrong<br />";
	}
  }
  else{
    $aqerr = true;
	$_SESSION['aqerr'] .= "Categorylink is not a string<br />";
  }
  //$aqerr = true; $_SESSION['aqerr'] .= "Categorylink is undefined<br />";
}
else{
  //print('Categorylink is undefined<br />');
  $aqerr = true;
  $_SESSION['aqerr'] .= "Categorylink is undefined<br />";
}

// SUBCATEGORY
if(isset($categorylink))
if(isset($_POST['subcategorylink'])){
  $subcatlink = preg_replace('/[^a-z-]/i','',substr((string)$_POST['subcategorylink'],0,35));
  if(is_string($subcatlink)){
	if(isset($subcategories[$categorylink][$subcatlink])){
	  $subcategorylink = $subcatlink;
	  //print('$subcategorylink: '.$subcategorylink.'<br />');
	  $subcategoryname = $subcategories[$categorylink][$subcatlink];
    $subcategoryname = addslashes($subcategoryname);
	  //print('$subcategoryname: '.$subcategoryname.'<br />');
	}
	else{
	  $aqerr = true;
	  $_SESSION['aqerr'] .= "Subcategorylink is wrong<br />";
	}
  }
  else{
    $aqerr = true;
	$_SESSION['aqerr'] .= "Subcategorylink is not a string<br />";
  }
  //$aqerr = true; $_SESSION['aqerr'] .= "Subcategorylink is undefined<br />";
}
else{
  //print('Subcategorylink is undefined<br />');
  $aqerr = true;
  $_SESSION['aqerr'] .= "Subcategorylink is undefined<br />";
}

}


if($addquestion == true && $aqerr == false){

$dt = gmdate('Y-m-d H:i:s');
$answers = 0;
$complaint = 'no';
$checked = 'no';

try{

$qinsert = $db->exec("INSERT INTO questions (uid,utype,fname,lname,qtext,qdetails,imgf,qimages,uphoto,categorylink,categoryname,subcategorylink,subcategoryname,dt,answers,complaint,checked) VALUES ('$uid','$utype','$fname','$lname','$qtext','$qdetails','$imgfolder','$qimages','$usphoto','$categorylink','$categoryname','$subcategorylink','$subcategoryname','$dt',$answers,'$complaint','$checked')");
//print('$qinsert: '); var_dump($qinsert); print('<br />');

$db->exec("UPDATE users SET lrec='$dt' WHERE uid='$uid';");

if($qinsert == 1){

// копирование фото из папки временного хранения фото 'tmpimg' в папку 'images' и удаление фото из папки 'tmpimg'
$qphotoarray = explode("|sp|",$_SESSION['quplphoto']);
foreach($qphotoarray as $v){
  copy('tmpimg/'.$v,'images/'.$imgfolder.'/'.$v);
  unlink('tmpimg/'.$v);
}

}
else{
  $_SESSION['aqerr'] .= "The question was not added<br />";
}

}
catch(Exception $e){
  $_SESSION['aqerr'] .= $e->getMessage();
}

}

if($_SESSION['aqerr'] == ''){
  unset($_SESSION['aqerr']);
  unset($_SESSION['qtext']);
  unset($_SESSION['qdetails']);
  unset($_SESSION['quplphoto']);
  unset($_SESSION['cat']);
  unset($_SESSION['subcat']);
  unset($_SESSION['qwopen']);
}


if(isset($subcategorylink)){
  if($host == 'localhost'){
    header('Location:http://'.$host.'/exp3/index.php?category='.$categorylink.'&subcategory='.$subcategorylink);
  }
  else{
    header('Location:http://'.$host.'/index.php?category='.$categorylink.'&subcategory='.$subcategorylink);
  }
  exit();
}
else{
  header('Location:http://'.$currenturl); exit();
}


}

}

?>
