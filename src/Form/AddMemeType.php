<?php

namespace App\Form;

use App\Entity\Meme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class AddMemeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', FileType::class, ['label' => 'Meme file', 'attr' => ['onchange' => 'loadFile(event)']])
            ->add('caption', TextareaType::class, ['label' => 'Post caption'])
            ->add('submit', SubmitType::class, ['label' => 'Add meme'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Meme::class,
        ]);
    }
}
