<?php

namespace Event\EventBundle\Mailer;

class Mailer
{
    protected $mailer;

    protected $from;

    /** @var  \Twig_Environment */
    protected $twig;

    public function __construct(\Swift_Mailer $mailer, $twig, $from)
    {
        $this->mailer = $mailer;
        $this->from = $from;
        $this->twig = $twig;
    }

    public function send($to, $subject, $body, $from = null, $cc = null, $attachmentData = [])
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from ?: $this->from)
            ->setTo($to)
            ->setBody($body)
            ->setContentType('text/html')
        ;

        if (isset($attachmentData['filename']) && isset($attachmentData['contentType']) && isset($attachmentData['data'])) {
            $attachment = \Swift_Attachment::newInstance(
                $attachmentData['data'],
                $attachmentData['filename'],
                $attachmentData['contentType']
            );

            $message->attach($attachment);
        }

        // set carbon copy
        if ($cc) {
            $message->setCc($cc);
        }

        $this->mailer->send($message);
    }

    public function sendWithPdfPathAttach($to, $subject, $body, $from = null, $cc = null, $attachmentData = [])
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from ?: $this->from)
            ->setTo($to)
            ->setBody($body)
            ->setContentType('text/html')
        ;

        if (isset($attachmentData['filename']) && isset($attachmentData['contentType']) && isset($attachmentData['data'])) {
            $attachment = \Swift_Attachment::fromPath($attachmentData['data'])
                ->setFilename($attachmentData['filename'])
                ->setContentType($attachmentData['contentType'])
            ;
            $message->attach($attachment);
        }

        // set carbon copy
        if ($cc) {
            $message->setCc($cc);
        }

        $this->mailer->send($message);
    }
}
