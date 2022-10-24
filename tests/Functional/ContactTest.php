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

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Formulaire de contact');

        //Recuperer le formulaire

        $submitButton = $crawler->selectButton('Soumettre ma demande');
        $form = $submitButton->form();

        $form["contact[fullName]"] = "Jean Dupont";
        $form["contact[email]"] = "j@gmail.com";
        $form["contact[subject]"] = "Super";
        $form["contact[message]"] = "tout vas bien ";

        //Soumettre le formulaire

        $client->submit($form);
    

        //Vérifier le statut HTTP$

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        //Vérifier l'envoie du mail

        $client->followRedirect();

        //Vérifier la présence du message de succès
    }
}
