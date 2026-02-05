<?php
namespace PHPMailer\PHPMailer;

class PHPMailer
{
    public $Host;
    public $Port;
    public $Username;
    public $Password;
    public $SMTPSecure;
    public $SMTPAuth = true;
    public $Subject;
    public $Body;
    public $AltBody;
    public $CharSet = 'UTF-8';

    private $fromEmail;
    private $fromName;
    private $to = array();

    public function isSMTP()
    {
        return true;
    }

    public function setFrom($email, $name = '')
    {
        $this->fromEmail = $email;
        $this->fromName = $name;
    }

    public function addAddress($email, $name = '')
    {
        $this->to[] = array($email, $name);
    }

    public function send()
    {
        $to = array();
        foreach ($this->to as $item) {
            $to[] = $item[1] ? $item[1] . ' <' . $item[0] . '>' : $item[0];
        }
        $headers = array();
        if ($this->fromEmail) {
            $from = $this->fromName ? $this->fromName . ' <' . $this->fromEmail . '>' : $this->fromEmail;
            $headers[] = 'From: ' . $from;
        }
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=' . $this->CharSet;
        return mail(implode(', ', $to), $this->Subject, $this->Body, implode("\r\n", $headers));
    }
}
