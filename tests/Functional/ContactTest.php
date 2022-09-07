<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ContactTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/contact');

  

        //REcup le form
        $submitButton  = $crawler->selectButton('Soumettre ma demande');
        $form = $submitButton->form();

        $form["contact[fullName]"] = "Jean Dupont";
        $form["contact[email]"] = "email@gmail.com";
        $form["contact[subject]"] = "lol";
        $form["contact[message]"] = "super c'esr drole";


        //soumettre le form

        $client->submit($form);

        //verifier le status http

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        //veridfier la presece du messagz de success

        $this->assertSelectorTextContains('div.alert.alert-success.mt-4',
                                        'Votre demande a été envoyé avec succès !');
    }
}
