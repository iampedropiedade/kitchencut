<?php

namespace App\Entity;

use App\Repository\InvoiceLineRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InvoiceLineRepository::class)
 * @ORM\Table(name="invoice_lines")
 */
class InvoiceLine
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=InvoiceHeader::class, inversedBy="invoiceLines")
     * @ORM\JoinColumn(nullable=false)
     */
    private $invoiceHeader;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $value;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInvoiceHeader(): ?InvoiceHeader
    {
        return $this->invoiceHeader;
    }

    public function setInvoiceHeader(?InvoiceHeader $invoiceHeader): self
    {
        $this->invoiceHeader = $invoiceHeader;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
