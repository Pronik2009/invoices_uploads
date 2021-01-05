<?php

namespace App\Entity;

use App\Repository\UploadsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity(repositoryClass=UploadsRepository::class)
 */
class Uploads
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Invoices::class, mappedBy="uploads")
     */
    private $invoices;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $filename;

    public function __construct()
    {
        $this->invoices = new ArrayCollection();
        $this->date = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }
    public function getDateStr(): ?string
    {
        return $this->date->format('Y-m-d H-i-s');
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getUser(): ?int
    {
        return $this->user;
    }

    public function setUser(?int $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Invoices[]
     */
    public function getInvoices(): Collection
    {
        return $this->Invoices;
    }

    public function addInvoice(Invoices $invoice): self
    {
        if (!$this->Invoices->contains($invoice)) {
            $this->Invoices[] = $invoice;
            $invoice->setUploads($this);
        }

        return $this;
    }

    public function removeInvoice(invoices $invoice): self
    {
        if ($this->Iinvoices->removeElement($invoice)) {
            // set the owning side to null (unless already changed)
            if ($invoice->getUploads() === $this) {
                $invoice->setUploads(null);
            }
        }

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }
}
