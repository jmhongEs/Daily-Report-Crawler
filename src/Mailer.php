<?php

namespace App\DailyReportCrawler;

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer extends PHPMailer
{
    public function __construct() {
        parent::__construct();

        $this->SMTPDebug = 2;                      //Enable verbose debug output
        $this->isSMTP();                                            //Send using SMTP
        $this->CharSet    = PHPMailer::CHARSET_UTF8;
        $this->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $this->SMTPAuth   = true;                                   //Enable SMTP authentication
        $this->SMTPSecure = 'ssl';
        $this->Username   = 'jmhong.es@gmail.com';                     //SMTP username
        $this->Password   = 'tdutnngztnrxpcfq';                               //SMTP password    
        $this->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $this->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $this->setFrom('jmhong.es@gmail.com', 'Mailer');
    }

    public function sendEmail(string $toEmail , string $mailBody)
    {
        //Create an instance; passing `true` enables exceptions
        try {
            // $this->addAddress('ellen@example.com');               //Name is optional
            // $this->addReplyTo('info@example.com', 'Information');
            // $this->addCC('cc@example.com');
            // $this->addBCC('bcc@example.com');

            //Attachments
            // $this->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            $this->addAddress($toEmail, 'Joe User');     //Add a recipient
            // $this->addAttachment('assets/dailyReport/daily-report.html', 'daily-report.html');    //Optional name

            //Content
            $this->isHTML(true);                                  //Set email format to HTML
            $this->Subject = 'Here is the subject';
            $this->Body    = "'.$mailBody.'";
            $this->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $this->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$this->ErrorInfo}";
        }
    }
}
