<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;


class SessionManager {

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        $total = $this->session->get("total");
        if (is_null($total))  $this->session->set("total", 0);
    }
    public function getTotal() {
        return $this->session->get("total");
    }

    public function addTicket($montant)
    {
        $total = $this->session->get("total") + $montant;
        $this->session->set("total", $total);
    }
} 