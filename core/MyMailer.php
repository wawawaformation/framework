<?php

declare(strict_types=1);

namespace Core;

use App\App;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Cette classe permet d'envoyer des mails. Implémente PHPMailer
 */
class MyMailer
{
    protected ?string $head;
    protected string $footer = '</body></html>';



    /**
     * Instance de PHPMailer
     * @var PHPMailer
     */
    protected PHPMailer $mail;



    /**
     * Formate le debut de l'email
     * @param string $title
     * @return void
     */
    protected function setHead(string $title): void
    {
        $this->head = <<<HTML
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
    "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <title>{$title}</title>
    </head>
    HTML;
    }






    /**
     * MyMailer constructor.
     *
     * @param string $from
     * @param integer $debug 0=>no debug, 1=client, 2=server
     * @throws Exception
     */
    public function __construct(string $from, int $debug = 0)
    {

        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->SMTPDebug = $debug;
        $this->mail->Host = App::get('SMTP_HOST');
        $this->mail->SMTPSecure = App::get('SMTP_SECURE');
        $this->mail->Port = App::get('SMTP_PORT');
        $this->mail->SMTPAuth = true;
        $this->mail->Username = App::get('SMTP_USER');
        $this->mail->Password = App::get('SMTP_PASS');
        $this->mail->CharSet = 'UTF-8';
        $this->mail->setFrom($from, 'Chez Christelle');
        $this->mail->isHTML(true);
    }

    /**
     * Ajoute une destinaire
     * @param string $to mail du destinataire
     * @param string $name nom du destinataire (peut etre vide)
     * @throws Exception
     */
    public function addAddress(string $to, string $name = ''): void
    {
        $this->mail->addAddress($to, $name);
    }


    /**
     * Ajoute une adresse de réponse
     *
     * @param string $to mail de la réponse
     * @param string $name nom de la réponse (peut etre vide)
     * @return void
     */
    public function addReplyTo(string $to, string $name = ''): void
    {
        $this->mail->addReplyTo($to, $name);
    }


    /**
     * Ajoute un objet au mail à envoyer
     *
     * @param string $subject objet du mail
     * @return void
     */
    public function addSubject(string $subject): void
    {
        $this->mail->Subject = $subject;
    }



    /**
     * Definit le corps de l'email
     * @param string $msg l'interieur du message
     * @param string $object le titre ddans le corps de l'email
     * @param mixed $style le style de l'email
     * @return void
     */
    public function setBody(string $msg, string $object, ?string $style = null): void
    {
        $this->setHead($object);
        $body = $this->head;
        $body .= "\n" . '<body' . ($style ? ' style="' . $style . '"' : '') . '>';
        $body .= "\n" . $msg;
        $body .= "\n" . $this->footer;
        $this->mail->Body = $body;
    }


    /**
     * Ajoute un message alternatif
     * @param string $msg le corps du message alternatif
     * @return void
     */
    public function setAltBody(string $msg): void
    {
        $this->mail->AltBody = $msg;
    }

    /**
     * Envoi le mail
     * @return bool true si le mail a été envoyé, false sinon
     * @throws Exception
     */
    public function send(): bool
    {
        try {
            return $this->mail->send();
        } catch (Exception $e) {
            throw new \Exception('Erreur lors de l\'envoi du mail : ' . $this->mail->ErrorInfo);
        }
    }


    /**
     * Nettoie le mail avant d'envoyer un autre
     * @return void
     */
    public function reset(): void
    {
        $this->mail->clearAddresses();
        $this->mail->clearReplyTos();
        $this->mail->clearAttachments();
        $this->mail->clearCustomHeaders();
        $this->mail->Body = '';
        $this->mail->AltBody = '';
        $this->mail->Subject = '';
    }
}
