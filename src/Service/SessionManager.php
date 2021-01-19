<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;


class SessionManager
{

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        $total = $this->session->get("total");
        if (is_null($total))  $this->session->set("total", 0);
    }

    /**
     * Permet de récuper le total de la commande
     *
     * @return integer
     */
    public function getTotal()
    {
        return $this->session->get("total");
    }

    /**
     * Permet d'ajouter le prix d'un billet au total
     *
     * @param int $montant
     * @return integer
     */
    public function addTicket($montant)
    {
        $total = $this->session->get("total") + $montant;
        $this->session->set("total", $total);
    }

    /**
     * Permet de donner les données du formulaire à la session
     *
     * @param [array] $data
     * @return void
     */
    public function setOrder($data)
    {
        foreach ($data as $entry => $value) {
            $this->session->set($entry, $value);
        }
        $this->session->set("currentInput", 0);
    }
    
    /**
     * Permet de récuperer les données de la session
     *
     * @param [array] $data
     * @return array
     */
    public function getData($data)
    {
        return $this->session->get($data);
    }
}
