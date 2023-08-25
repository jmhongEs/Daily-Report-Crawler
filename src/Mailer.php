<?php

namespace App\DailyReportCrawler;

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
        $this->Username   = $_ENV['REPORT_FROM_EMAIL_USERNAME'];                     //SMTP username
        $this->Password   = $_ENV['REPORT_FROM_EMAIL_PASSWORD'];                              //SMTP password    
        $this->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $this->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        // 발송자
        // todo : 발송 계정 바꾸기
        $this->setFrom($_ENV['REPORT_FROM_EMAIL_USERNAME'], 'es_stocks');
    }

    /**
     * 메일 발송 메서드
     * @param string $subject 제목
     * @param array $toEmails 메일 수신자 배열
     * @param string $mailBody html 문자열
     */
    public function sendEmail(string $subject, array $toEmails , string $mailBody) {
        try {
            // $this->addCC('cc@example.com'); // CC : 참조

            // todo : 개발 끝나면 팀장님, 이사님, 함이레 매니저님 참조
            // $this->addBCC(explode(';', $_ENV['REPORT_BCC_EMAIL'])); // BCC : 숨은 참조

            // 한 번에 여러 명에게 발송
            foreach ($toEmails as $toEmail) {
                $this->addAddress($toEmail);    
            }

            $this->isHTML(true);                                 
            $this->Subject = $subject; 
            $this->Body    = $mailBody;
            $this->AltBody = '';

            $this->send();

            // 메일 발송 성공 시 html 문서로 저장하기 위해 bool 값 반환
            return true;

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$this->ErrorInfo}";
            
            return false;
        } finally {
            $this->clearAddresses();
            $this->clearAttachments();
        }
    }

}
