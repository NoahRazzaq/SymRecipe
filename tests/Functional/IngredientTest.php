<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Request;

class IngredientTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();

        //REcup urlgenerator

        $urlgenerator = $client->getContainer()->get('router');



        //recup entity manager

        $entityManager = $client->getContainer()->get('doctrien.orm.entity_manager');

        $user = $entityManager->find(User::class, 1);


        //se rendre sur la page de creation d'un ingredient

        $client->loginUser($user);

        $crawler = $client->request(Request::Method_GET, $urlgenerator)

        //Gerer le formulaire

        //gerer la redirection

        //gerer l'alert box et la route

        

    }
}
