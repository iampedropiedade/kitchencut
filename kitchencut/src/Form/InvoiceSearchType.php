<?php

namespace App\Form;

use App\Entity\InvoiceHeader;
use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class InvoiceSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateFrom', DateType::Class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('dateTo', DateType::Class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('status', ChoiceType::Class, [
                'choices'  => InvoiceHeader::STATUSES,
                'required' => false,
                'placeholder' => 'All statuses',
                'empty_data' => null,
            ])
            ->add('location', EntityType::class, [
                'class' => Location::class,
                'required' => false,
                'placeholder' => 'All locations',
                'empty_data' => null,
            ])
            ->setMethod('GET');
    }
}
