<?php

namespace App\Entity;

use App\Repository\InvoiceHeaderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use DateTimeInterface;

/**
 * @ORM\Entity(repositoryClass=InvoiceHeaderRepository::class)
 * @ORM\Table(name="invoice_headers")
 */
class InvoiceHeader
{

    public const STATUS_DRAFT = 'draft';
    public const STATUS_OPEN = 'open';
    public const STATUS_PROCESSED = 'processed';
    public const STATUS_DRAFT_TEXT = 'Draft';
    public const STATUS_OPEN_TEXT = 'Open';
    public const STATUS_PROCESSED_TEXT = 'Processed';

    public const STATUSES_LIST = [
        self::STATUS_DRAFT,
        self::STATUS_OPEN,
        self::STATUS_PROCESSED,
    ];

    public const STATUSES = [
        self::STATUS_DRAFT_TEXT => self::STATUS_DRAFT,
        self::STATUS_OPEN_TEXT => self::STATUS_OPEN,
        self::STATUS_PROCESSED_TEXT => self::STATUS_PROCESSED,
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Location::class, inversedBy="invoiceHeaders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $location;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=InvoiceLine::class, mappedBy="invoiceHeader", orphanRemoval=true, cascade={"persist"})
     */
    private $invoiceLines;

    public function __construct()
    {
        $this->invoiceLines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @param DateTimeInterface|null $date
     * @return $this
     */
    public function setDate(?DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     * @return $this
     */
    public function setStatus(?string $status): self
    {
        if (!in_array($status, self::STATUSES, true)) {
            throw new InvalidArgumentException('Invalid status');
        }
        $this->status = $status;
        return $this;
    }

    /**
     * @return Collection|InvoiceLine[]
     */
    public function getInvoiceLines(): Collection
    {
        return $this->invoiceLines;
    }

    public function getTotal()
    {
        return array_sum(array_map(static function($invoiceLine) {
            return (float)$invoiceLine->getValue();
        }, $this->invoiceLines->toArray()));
    }

    public function addInvoiceLine(InvoiceLine $invoiceLine): self
    {
        if (!$this->invoiceLines->contains($invoiceLine)) {
            $this->invoiceLines[] = $invoiceLine;
            $invoiceLine->setInvoiceHeader($this);
        }
        return $this;
    }

    public function removeInvoiceLine(InvoiceLine $invoiceLine): self
    {
        if ($this->invoiceLines->removeElement($invoiceLine)) {
            // set the owning side to null (unless already changed)
            if ($invoiceLine->getInvoiceHeader() === $this) {
                $invoiceLine->setInvoiceHeader(null);
            }
        }

        return $this;
    }
}
