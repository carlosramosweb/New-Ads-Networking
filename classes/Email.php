<?php 
class Email {

    public $mail;
    public $subject;
    public $body;
    public $from;
    public $toEmail;
    public $toName;

    function __construct(){

        $this->mail = new PHPMailer();
        $this->mail->isSMTP();
        $this->mail->Host = SMTP_HOST;
        $this->mail->SMTPAuth = SMTP_AUTH;
        $this->mail->SMTPSecure = SMTP_SECURE;
        $this->mail->Username = SMTP_USER;
        $this->mail->Password = SMTP_PASS;
        $this->mail->Port = SMTP_PORT;
        $this->mail->isHTML(true);
        $this->mail->SMTPDebug = 1;
        $this->mail->CharSet = 'UTF-8';

    }
    
    function send() {
        $this->mail->setFrom($this->from);
        $this->mail->addReplyTo($this->from);
        $this->mail->addAddress($this->toEmail, $this->toName);
        $this->mail->Subject = $this->subject;
        $this->mail->Body = $this->body;
        if($this->mail->send()) {
            return true;
        } else {
            define('ERROR_EMAIL_MSG',$this->mail->ErrorInfo);
            return false;
        }
    }
}
?>