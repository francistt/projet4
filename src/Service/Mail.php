<?php

namespace App\Service;

use Mailjet\Client;
use Mailjet\Resources;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Mail
{
    private $parameterBagInterface; 
    private $api_key;
    private $api_key_secret;
    public function __construct(ParameterBagInterface $parameterBagInterface)
    {
            $this->parameterBagInterface = $parameterBagInterface;
            $this->api_key = $parameterBagInterface->get('app.api.key.mail');
            $this->api_key_secret = $parameterBagInterface->get('app.api.key.mail.secret');
    }

    /**
     * Permet d'envoyer un email
     *
     * @param [type] $to_email
     * @param [type] $to_name
     * @param [type] $subject
     * @param [type] $content
     * @return Object
     */
    public function send($to_email, $to_name, $subject, $content)
    {
        $mj = new Client($this->api_key, $this->api_key_secret,true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "musee-du-louvre@nevertoolate.fr",
                        'Name' => "Musée du Louvre"
                    ],
                    'To' => [
                        [
                            'Email' => "$to_email",
                            'Name' => "$to_name"
                        ]
                    ],
                    'TemplateID' => 1965729,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        //'reservation' => $reservation,
                        //'session' => $session,
                        //'reservationDate' => $session->getData('reservation_date'),
                        //'firstName' => $session->getData('firstName'),
                        'content' => $content,
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}