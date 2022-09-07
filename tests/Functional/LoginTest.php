<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class LoginTest extends WebTestCase
{
    public function testIfLoginSuccessful(): void
    {
        $client = static::createClient();

        //get route by urlgenerator
        /** @var UrlGeneratorInterface $urlGenerator  */
        $urlGenerator = $client->getContainer()->get("router");

        $crawler = $client->request('GET', $urlGenerator->generate('security.login'));

        //Form
        $form = $crawler->filter("form[name=login]")->form([
            "_username" => "email0@gmail.com",
            "_password" =>"password"
        ]);

        $client->submit($form);
 
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);//la on demande vu que ta soumis on demande ques que ta comme status de response et puis on attend une redeirectoo

          
        $client->followRedirect();

        $this->assertRouteSame('home.index');
        //Redirect + home

       
    }

    public function testIfLoginFailedPasswordIsWrong():void
    {
        $client = static::createClient();

        //get route by urlgenerator
        /** @var UrlGeneratorInterface $urlGenerator  */
        $urlGenerator = $client->getContainer()->get("router");

        $crawler = $client->request('GET', $urlGenerator->generate('security.login'));

        //Form
        $form = $crawler->filter("form[name=login]")->form([
            "_username" => "email0@gmail.com",
            "_password" =>"password_"
        ]);

        $client->submit($form);
 
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);//la on demande vu que ta soumis on demande ques que ta comme status de response et puis on attend une redeirectoo

        $client->followRedirect();

        $this->assertRouteSame('security.login');

        $this->assertSelectorTextContains("div.alert-danger", "Invalid credentials.");
        //Redirect + home

    }
}
