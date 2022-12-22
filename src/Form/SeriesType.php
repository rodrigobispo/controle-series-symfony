<?php

namespace App\Form;

use App\DTO\SeriesCreateFromInput;
use App\Entity\Series;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SeriesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('seriesName', options: ['label' => 'Nome:'])
            ->add('seasonsQuantity', type: NumberType::class, options: ['label' => 'Qtd Temporadas:'])
            ->add('episodesPerSeason', type: NumberType::class, options: ['label' => 'Ep por Temporada:'])
            ->add(child: 'save', type: SubmitType::class, options: ['label' => $options['is_edit'] ? 'Editar' : 'Adicionar'])
            ->setMethod($options['is_edit'] ? 'PATCH' : 'POST')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SeriesCreateFromInput::class,
            'is_edit' => false,
        ]);

        $resolver->setAllowedTypes(option: 'is_edit', allowedTypes: 'bool');
    }
}
