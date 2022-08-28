<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\Query\Expr\Func;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct()
    {
    
    }

    
    public function load(ObjectManager $manager): void //dependance
    {
        // Ingredients
        $ingredients = [];
        for($i= 1; $i<50 ; $i++){
       $ingredient = new Ingredient();
       $ingredient->setName('Ingridient'. $i)
                ->setPrice(mt_rand(0,100));

                $ingredients[]= $ingredient;

                $manager->persist($ingredient);

               }

        for($j =0; $j<25; $j++){
            $recipe = new Recipe();
            $recipe->setName('Recipe'. $j)
            ->setTime(mt_rand(0, 1) == 1 ? mt_rand(1, 1440) : null)
            ->setNbPeople(mt_rand(0, 1) == 1 ? mt_rand(1, 50) : null)
            ->setDifficulty(mt_rand(0, 1) == 1 ? mt_rand(1, 5) : null)
            ->setDescription('blabla'. $j)
            ->setPrice(mt_rand(0, 1) == 1 ? mt_rand(1, 1000) : null)
            ->setIsFavorite(mt_rand(0, 1) == 1 ? true : false);
           
           
            for ($k=0; $k < mt_rand(5, 15) ; $k++) { 
                $recipe->addIngredient($ingredients[mt_rand(0, count($ingredients) - 1)]);
            }

            $manager->persist($recipe);

        }

        for ($i=0; $i < 10 ; $i++) { 
            $user = new User();
            $user->setFullName('Name' .$i)
            ->setPseudo(mt_rand(0, 1))
            ->setEmail('email'. $i .'@gmail.com')
            ->setRoles(['ROLE_USER'])
            ->setPlainPassword('password');


            $manager->persist($user);
        }
       
        $manager->flush();
    }
}
