<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class ZMail
{
    private $PHPMailer;
    public $debug = false;

    public function __construct($isSMTP = true)
    {
        $env = require '../environment.php';
        $this->PHPMailer = new PHPMailer;
        if($isSMTP)
        {
            $this->PHPMailer->isSMTP();
            $this->PHPMailer->Host       = $env['smtp_host'];      // Specify main and backup SMTP servers
            $this->PHPMailer->SMTPAuth   = true;                   // Enable SMTP authentication
            $this->PHPMailer->Username   = $env['smtp_username'];  // SMTP username
            $this->PHPMailer->Password   = $env['smtp_password'];  // SMTP password
            $this->PHPMailer->SMTPSecure = $env['smtp_secure'];    // Enable TLS encryption, `ssl` also accepted
            $this->PHPMailer->Port       = $env['smtp_port'];      // TCP port to connect to   
        }
        $this->PHPMailer->setFrom($env['smtp_username']);
        $this->PHPMailer->isHTML(true);
    }

    public function send($to, $subject, $message, $reply = array())
    {
        if($this->debug)
            $this->PHPMailer->SMTPDebug = SMTP::DEBUG_SERVER; 
        if(is_array($to))
            foreach($to as $val)
                $this->PHPMailer->addAddress($val);
        else
            $this->PHPMailer->addAddress($to);

        if(!empty($reply))
            foreach($reply as $val)
                $this->PHPMailer->addReplyTo($val);

        $this->PHPMailer->Subject = $subject;
        $this->PHPMailer->msgHTML($message);
        if (!$this->PHPMailer->send()) {
            return $this->PHPMailer->ErrorInfo;
        } else {
            return 1;
        }
    }
}