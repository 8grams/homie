<?php

namespace App\Libs;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\DataPart;
use App\Libs\ViewEngine;

/**
 * Mailer class for handling email operations
 */
class Mailer
{
    /**
     * Constructor for Mailer class
     * 
     * @param MailerInterface $mailer Symfony mailer service
     * @param ViewEngine $viewEngine Template engine for email content
     * @param array $config Configuration array containing email settings
     */
    public function __construct(
        private MailerInterface $mailer,
        private ViewEngine $viewEngine,
        private array $config
    ) {
    }

    /**
     * Send an email using a template
     * 
     * @param array $options Email options including from, to, subject, etc.
     * @param array $data Data to be passed to the template
     * @param string $template Template name without extension
     * @return void
     */
    public function send(array $options, array $data, $template): void
    {
        $templatePath = $this->config['email_template']['path'] . '/' . $template;
        if (file_exists($templatePath . '.php')) {
            $view = $this->viewEngine->setDirectory($this->config['email_template']['path'])->make($template, $data);
            $body = $view->render();
            $options['body_html'] = $body;
        }
        
        if (file_exists($templatePath . '.plain.php')) {
            $template = $template . '.plain';
            $view = $this->viewEngine->setDirectory($this->config['email_template']['path'])->make($template, $data);
            $body = $view->render();
            $options['body_plain'] = $body;
        }

        $this->sendMail($options);
    }

    /**
     * Internal method to send the email using Symfony Mailer
     * 
     * @param array $options Email options including body, attachments, cc, bcc
     * @return void
     */
    private function sendMail(array $options): void
    {
        $email = new Email();
        $email->from($options['from']);
        $email->to($options['to']);
        $email->subject($options['subject']);

        if (isset($options['body_plain'])) {
            $email->text($options['body_plain']);
        }

        if (isset($options['body_html'])) {
            $email->html($options['body_html']);
        }

        if (isset($options['attachments'])) {
            foreach ($options['attachments'] as $attachment) {
                $email->addPart(new DataPart(fopen($attachment, 'r')));
            }
        }

        // set cc & bcc
        if (isset($options['cc'])) {
            $email->cc($options['cc']);
        }

        if (isset($options['bcc'])) {
            $email->bcc($options['bcc']);
        }

        $this->mailer->send($email);
    }
}