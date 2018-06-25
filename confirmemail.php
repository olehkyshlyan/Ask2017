<?

class ConfirmEmail{
  public $email;
  private $subject;
  public $message;
  private $headers;
  private $confirmlink;
  private $sentmail;
  
  function __construct(){
    $this->subject = 'Registration at the site Questions and Answers';
    $this->headers = "MIME-Version: 1.0\r\n";
    $this->headers .= "Content-type: text/html; charset=utf-8\r\n";
    $this->headers .= "From: Web-site <askandanswerhere@mail.com>\r\n";
  }
  
  public function setMessage($host,$uid){
    if($host == 'localhost'){
      $this->confirmlink = 'http://localhost/exp3/registration.php?uid='.$uid;
    }
    else{
      $this->confirmlink = 'http://'.$host.'/registration.php?uid='.$uid;
    }
    
    $this->message = 'You have registered at the site "Questions and Answers".<br />';
    $this->message .= 'Please, go to this link <a href="'.$this->confirmlink.'">'.$this->confirmlink.'</a> to complete your registration.<br />';
    $this->message .= 'If you did not register at the site "Questions and Answers", just delete this letter.<br />';
    $this->message .= 'Thanks :-)';
  }
  
  public function sendMessage(){
    $this->sentmail = mail($this->$email,$this->subject,$this->message,$this->headers);
    //print('$this->sentmail: '); var_dump($this->sentmail); print('<br />');
  }
}

$confirmEmail = new ConfirmEmail();

?>