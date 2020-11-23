<?php

namespace App\Service;

use Mailjet\Client;
use Mailjet\Resources;
use App\Entity\Reservation;
use App\Service\SessionManager;

class Mail
{
    private $api_key = '482cdf4b45dc2edc3ab1900ea08bfa00';
    private $api_key_secret = '4051cac764d580c3ccd8de552fbafa23';

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
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 19550085,
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
        $response->success() && dd($response->getData());
    }
}