<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table("reservation")
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */ 
    private $id;
    
    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="reservation", cascade={"persist"})
     */
    private $clients;

     /**
     * @ORM\ManyToOne(targetEntity="User", cascade={"persist"})
     */
    private $reserver;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

   /** 
     * @ORM\Column(type="date")
     * @Assert\Date(message="Attention, la date doit être au bon format")
     * @Assert\GreaterThanOrEqual("today", message="La date de réservation doit être ultérieure ou égale à la date d'aujourd'hui !")
     */
    private $reservation_date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $uuid;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive(message="il faut un minimum de 1 billet")
     */
    private $nbTicket;

    /**
     * @ORM\Column(type="boolean")
     */
    private $halfDay=false;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $idStripe;

    /**
     * @ORM\Column(type="boolean")
     */
    private $state=false;

    public function __construct()
    {
        $this->clients = new ArrayCollection();
        $this->createdAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getReservationDate(): ?\DateTimeInterface
    {
        return $this->reservation_date;
    }

    public function setReservationDate(\DateTimeInterface $reservation_date): self
    {
        $this->reservation_date = $reservation_date;

        return $this;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getNbTicket(): ?int
    {
        return $this->nbTicket;
    }

    public function setNbTicket(int $nbTicket): self
    {
        $this->nbTicket = $nbTicket;

        return $this;
    }

    public function getHalfDay(): ?bool
    {
        return $this->halfDay;
    }

    public function setHalfDay(bool $halfDay): self
    {
        $this->halfDay = $halfDay;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(?int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getIdStripe(): ?string
    {
        return $this->idStripe;
    }

    public function setIdStripe(?string $idStripe): self
    {
        $this->idStripe = $idStripe;

        return $this;
    }

    public function getState(): ?bool
    {
        return $this->state;
    }

    public function setState(bool $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(User $client): self
    {
        if (!$this->clients->contains($client)) {
            $this->clients[] = $client;
            $client->setReservation($this);
        }

        return $this;
    }

    public function removeClient(User $client): self
    {
        if ($this->clients->contains($client)) {
            $this->clients->removeElement($client);
            // set the owning side to null (unless already changed)
            if ($client->getReservation() === $this) {
                $client->setReservation(null);
            }
        }

        return $this;
    }

    public function getReserver(): ?User
    {
        return $this->reserver;
    }

    public function setReserver(?User $reserver): self
    {
        $this->reserver = $reserver;

        return $this;
    }
}
