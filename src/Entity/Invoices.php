<?php

namespace App\Entity;

use App\Repository\InvoicesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InvoicesRepository::class)
 */
class Invoices
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $custom_id;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="date")
     */
    private $due_date;

    /**
     * @ORM\ManyToOne(targetEntity=Uploads::class, inversedBy="invoices")
     */
    private $uploads;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $sellingprice;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomId(): ?string
    {
        return $this->custom_id;
    }

    public function setCustomId(string $custom_id): self
    {
        $this->custom_id = $custom_id;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->due_date;
    }

    public function setDueDate(\DateTimeInterface $due_date): self
    {
        $this->due_date = $due_date;

        return $this;
    }

    public function getUploads(): ?Uploads
    {
        return $this->uploads;
    }

    public function setUploads(?Uploads $uploads): self
    {
        $this->uploads = $uploads;

        return $this;
    }

    public function getSellingprice(): ?float
    {
        return $this->sellingprice;
    }

    public function setSellingprice(?float $sellingprice): self
    {
        $this->sellingprice = $sellingprice;

        return $this;
    }
}
