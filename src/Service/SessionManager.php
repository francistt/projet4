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
    public function getTotal()
    {
        return $this->session->get("total");
    }

    public function addTicket($montant)
    {
        $total = $this->session->get("total") + $montant;
        $this->session->set("total", $total);
    }
    public function setOrder($data)
    {
        foreach ($data as $entry => $value) {
            $this->session->set($entry, $value);
        }
        $this->session->set("currentInput", 0);
    }
    public function getData($data)
    {
        return $this->session->get($data);
    }

    public function newInput()
    {
        $current = $this->session->get("currentInput", 0) + 1;
        $this->session->set("currentInput", $current);
        if ($current > $this->session->get("nbTickets")) return false;
        return true;
    }
}
