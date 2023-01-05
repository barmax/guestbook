<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ConferenceControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h2', 'Give your feedback');
    }

    public function testCommentSubmission()
    {
        $client = static::createClient();

        $crawler =$client->request('GET', '/conference/2022-prague');
//        dd($crawler);
        $client->submitForm('Submit', [
            'comment_form[author]' => 'Honza',
            'comment_form[text]' => 'Ty kokos!',
            'comment_form[email]' => 'kokos@example.com',
        ]);

        $this->assertResponseRedirects();
//
        $client->followRedirect();
        $this->assertSelectorExists('div:contains("There are 2 comments")');
    }

    public function testConferencePage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertCount(2, $crawler->filter('h4'));

        $client->clickLink('View');

        $this->assertPageTitleContains('Conference Guestbook - 2022 Prague');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h2', '2022 Prague');
        $this->assertSelectorExists('div:contains("There are 1 comments")');
    }
}
