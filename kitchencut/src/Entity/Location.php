<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LocationRepository::class)
 * @ORM\Table(name="locations")
 */
class Location
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=InvoiceHeader::class, mappedBy="location")
     */
    private $invoiceHeaders;

    public function __construct()
    {
        $this->invoiceHeaders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|InvoiceHeader[]
     */
    public function getInvoiceHeaders(): Collection
    {
        return $this->invoiceHeaders;
    }

    public function addInvoiceHeader(InvoiceHeader $invoiceHeader): self
    {
        if (!$this->invoiceHeaders->contains($invoiceHeader)) {
            $this->invoiceHeaders[] = $invoiceHeader;
            $invoiceHeader->setLocation($this);
        }

        return $this;
    }

    public function removeInvoiceHeader(InvoiceHeader $invoiceHeader): self
    {
        if ($this->invoiceHeaders->removeElement($invoiceHeader)) {
            // set the owning side to null (unless already changed)
            if ($invoiceHeader->getLocation() === $this) {
                $invoiceHeader->setLocation(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

}
