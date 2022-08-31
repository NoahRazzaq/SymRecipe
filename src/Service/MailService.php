<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(
        string $from,
        string $subject,
        string $html,
        string $to = 'admin@symrecipe.com'
    ): void
    {
         //Email

         $email = (new Email())
         ->from($from)
         ->to($to)
         ->subject($subject)
         ->html($html) ;

    $this->mailer->send($email);

    }


}