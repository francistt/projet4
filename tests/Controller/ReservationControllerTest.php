<?php

namespace Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ReservationControllerTest extends WebTestCase
{
    public function testHomepageIsUp()
    {
        $expected = Response::HTTP_OK;
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertSame($expected, $client->getResponse()->getStatusCode());

        //echo $client->getResponse()->getContent();
    }

    public function testHomepage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertSame(1, $crawler->filter('html:contains("Le musée du Louvre vous accueille tous les jours")')->count());
    }

    public function testAddNewRecord()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $form = $crawler->selectButton('Suivant')->form();
        $form['reservation[reserver][lastName]'] = 'Dupont';
        $form['reservation[reserver][firstName]'] = 'Jean';
        $form['reservation[reserver][email]'] = 'Jean.dupont@gmail.com';
        $form['reservation[nbTicket]'] = 1;
        $form['reservation[reservation_date]'] = '2021-01-02';
        $form ['reservation[halfDay]'] = 1;

        $client->submit($form);

        
        $crawler = $client->followRedirect();

        echo $client->getResponse()->getContent();


    }
}