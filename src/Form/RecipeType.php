<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Repository\IngredientRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Webmozart\Assert\Assert as AssertAssert;
use Symfony\Component\Form\FormTypeInterface;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'attr' => [  //attribut
                'class' => 'form-control',
                'minlength' => '2',
                'maxlength'=> '50'
            ],
            'label' => 'Nom',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
            'constraints' => [
                new Assert\Length(['min' => 2, 'max' => 50]),
                new Assert\NotBlank()
            ]
        ])
            ->add('time', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 1440,

                ],
                'required' => false,
                'label' => 'Temps (en minutes)',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],

                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThan(1441)
                ]
            ])
            ->add('nbPeople', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 50

                ],
                'required' => false,
                'label' => 'Nombre de personnes',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],

                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThan(51)
                ]
            ])
            ->add('difficulty', IntegerType::class, [
                'attr' => [
                    'class' => 'form-range',
                    'min' => 1,
                    'max' => 5

                ],
                'label' => 'Difficulté',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],

                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThan(5)
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 1440

                ],
                'label' => 'Description',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],

                'constraints' => [
                    new Assert\NotBlank(),
                    
                ]
            ])

            ->add('price', MoneyType::class, [
                'attr' => [  //attribut
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength'=> '50'
                ],
                'required' => false,
                'label' => 'Prix',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThan(200)
                ]
                ])

            ->add('isFavorite', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check-input',
                    
                
                ],
                'required' => false,
                'label' => 'Favoris ?',
                'label_attr' => [
                    'class' => 'form-label'
                ],

                'constraints' => [
                    new Assert\NotNull(),
                    
                ]
            ])

            ->add('ingredients', EntityType::class, [

                'class' => Ingredient::class,
                'query_builder' => function (IngredientRepository $r){
                    return $r->createQueryBuilder('i')
                    ->orderBy('i.name', 'ASC');
                },
                'label' => 'Les ingrédients',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'choice_label' => 'name',
                'multiple' => 'true',
                'expanded' =>'true'

            ])
            ->add('submit', SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}