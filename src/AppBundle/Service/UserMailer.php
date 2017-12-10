<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;

class UserMailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendNewNotification(User $user)
    {
        $message = new \Swift_Message(
            'Inscription sur le site SnowTricks',
            'Votre inscription a bien été enregistrée sur notre site.'
        );

        $message
            ->addTo($user->getEmail())
            ->addFrom('kdu-prog@orange.fr')
        ;

        $this->mailer->send($message);
    }
}
