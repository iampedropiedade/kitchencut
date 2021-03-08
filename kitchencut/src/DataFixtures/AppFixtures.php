<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Location;
use App\Entity\InvoiceHeader;
use App\Entity\InvoiceLine;
use Faker\Factory;

class AppFixtures extends Fixture
{
    protected const MAX_LOCATIONS = 10;
    protected const MAX_INVOICES = 50;
    protected const MAX_INVOICE_LINES = 10;

    protected $faker;

    // Adding some test data to the DB
    public function load(ObjectManager $manager)
    {
        $this->faker = Factory::create();

        $locations = [];
        for ($i = 0; $i < self::MAX_LOCATIONS; $i++) {
            $location = new Location();
            $location->setName($this->faker->city);
            $manager->persist($location);
            $locations[] = $location;
        }

        for ($i = 0; $i < self::MAX_INVOICES; $i++) {
            $invoiceHeader = new InvoiceHeader();
            $invoiceHeader->setDate($this->faker->dateTimeBetween(sprintf('- %s weeks', (self::MAX_INVOICES - $i)), sprintf('- %s weeks', (self::MAX_INVOICES - $i))));
            $invoiceHeader->setStatus(InvoiceHeader::STATUSES_LIST[$this->faker->numberBetween(0, count(InvoiceHeader::STATUSES_LIST)-1)]);
            $invoiceHeader->setLocation($locations[$this->faker->numberBetween(0, count($locations)-1)]);
            $numberOfLines = $this->faker->numberBetween(1, self::MAX_INVOICE_LINES);
            for ($j = 0; $j < $numberOfLines; $j++) {
                $invoiceLine = new InvoiceLine();
                $invoiceLine->setDescription($this->faker->text(255));
                $invoiceLine->setValue($this->faker->randomFloat(2, 1, 1000));
                $invoiceLine->setInvoiceHeader($invoiceHeader);
                $invoiceHeader->addInvoiceLine($invoiceLine);
            }
            $manager->persist($invoiceHeader);
        }

        $manager->flush();
    }
}
