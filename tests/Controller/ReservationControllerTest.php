<?php

namespace Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ReservationControllerTest extends WebTestCase
{
    /**
     * Test la réponse HTTP
     *
     * @return void
     */
    public function testHomepageIsUp()
    {
        $expected = Response::HTTP_OK;
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertSame($expected, $client->getResponse()->getStatusCode());

        //echo $client->getResponse()->getContent();
    }

    /**
     * Test le contenu de la page Homepage
     *
     * @return void
     */
    public function testHomepage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertSame(1, $crawler->filter('html:contains("Le musée du Louvre vous accueille tous les jours")')->count());
    }
}