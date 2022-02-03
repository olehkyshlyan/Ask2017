<?
if(isset($_GET['uid'])){

$uid = strtolower(substr((string)$_GET['uid'],0,20));
//unset($_GET['uid']);
$uid = preg_replace('/[^0-9]/i','',$uid);

try
{

$tmpuinfo = $db->query("SELECT * FROM tmpusers WHERE uid='$uid'")->fetchAll(PDO::FETCH_ASSOC);
$tuiarr = count($tmpuinfo);
//print('$tuiarr: '); var_dump($tuiarr); print('<br />');

if($tuiarr == 1){

$utype = 's';
$uid = $tmpuinfo[0]['uid'];
$lemail = $tmpuinfo[0]['email'];
$fname = $tmpuinfo[0]['fname'];
$lname = $tmpuinfo[0]['lname'];
$psw = $tmpuinfo[0]['psw'];
$dt = gmdate('Y-m-d H:i:s');
$blocked = 'no';
$lrec = gmdate('Y-m-d H:i:s');

$insres = $db->exec("INSERT INTO users (utype,uid,lemail,fname,lname,psw,dt,blocked,lrec) VALUES ('$utype','$uid','$lemail','$fname','$lname','$psw','$dt','$blocked','$lrec')");
//print('$insres: '); var_dump($insres); print('<br />');

if($insres == 1){
$del = $db->exec("DELETE FROM tmpusers WHERE uid='$uid'");
//print('$del: '); var_dump($del); print('<br />');
$_SESSION['conferr'] = 'Registration was completed';
}
else{
$_SESSION['conferr'] = 'Registration was not successful<br />Press once again the link you got in the mail';
}

}
else{
$_SESSION['conferr'] = 'The link to confirm the registration has expired or has already been used<br />Fill in the form to register and get a new link';
}

}
catch(Exception $e){
$_SESSION['conferr'] = "Error: ".$e->getMessage()."<br />";
}

header('Location:http://'.$host.'/register.php'); exit();

}

?>