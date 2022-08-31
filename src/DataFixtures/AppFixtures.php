<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use App\Entity\Ingredient;
use App\Entity\Mark;
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

        $users = [];

        for ($i=0; $i < 10 ; $i++) { 
            $user = new User();
            $user->setFullName('Name' .$i)
            ->setPseudo(mt_rand(0, 1))
            ->setEmail('email'. $i .'@gmail.com')
            ->setRoles(['ROLE_USER'])
            ->setPlainPassword('password');

            $users[] = $user;

            $manager->persist($user);
        }
       
        // Ingredients
        $ingredients = [];
        for ($i = 0; $i < 50; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName('Ingredient'. $i)
                ->setPrice(mt_rand(0, 100))
                ->setUser($users[mt_rand(0, count($users) - 1)]);

            $ingredients[] = $ingredient;
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
            ->setIsFavorite(mt_rand(0, 1) == 1 ? true : false)
            ->setIsPublic(mt_rand(0, 1) == 1 ? true : false)
            ->setUser($users[mt_rand(0, count($users) - 1)]);
           
           
            for ($k=0; $k < mt_rand(5, 15) ; $k++) { 
                $recipe->addIngredient($ingredients[mt_rand(0, count($ingredients) - 1)]);
            }

            $recipes[] = $recipe;
            $manager->persist($recipe);
            
        }

        //Marks

        foreach ($recipes as $recipe) {
            for ($i=0; $i < mt_rand(0,4); $i++) { 
                $mark = new Mark();
                $mark->setMark(mt_rand(1,5))
                ->setUser($users[mt_rand(0, count($users) - 1)])
                ->setRecipe($recipe);

                $manager->persist($mark);
            }
        }

        //Contact
        for ($i=0; $i < 5; $i++) { 
            $contact = new Contact();
            $contact->setFullName('Name' .$i)
                    ->setEmail('email'. $i .'@live.com')
                    ->setSubject('subject'. $i)
                    ->setMessage('messagze'. $i);

                    $manager->persist($contact);
        }



        $manager->flush();
       
}

}