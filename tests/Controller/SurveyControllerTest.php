<?php

namespace App\Test\Controller;

use App\Entity\Survey;
use App\Repository\SurveyRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SurveyControllerTest extends WebTestCase
{
    /** @var KernelBrowser */
    private $client;
    /** @var SurveyRepository */
    private $repository;
    private $path = '/survey/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(Survey::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Survey index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'survey[titre]' => 'Testing',
            'survey[slug]' => 'Testing',
            'survey[datecreation]' => 'Testing',
            'survey[categorie]' => 'Testing',
        ]);

        self::assertResponseRedirects('/survey/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Survey();
        $fixture->setTitre('My Title');
        $fixture->setSlug('My Title');
        $fixture->setDatecreation('My Title');
        $fixture->setCategorie('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Survey');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Survey();
        $fixture->setTitre('My Title');
        $fixture->setSlug('My Title');
        $fixture->setDatecreation('My Title');
        $fixture->setCategorie('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'survey[titre]' => 'Something New',
            'survey[slug]' => 'Something New',
            'survey[datecreation]' => 'Something New',
            'survey[categorie]' => 'Something New',
        ]);

        self::assertResponseRedirects('/survey/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitre());
        self::assertSame('Something New', $fixture[0]->getSlug());
        self::assertSame('Something New', $fixture[0]->getDatecreation());
        self::assertSame('Something New', $fixture[0]->getCategorie());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Survey();
        $fixture->setTitre('My Title');
        $fixture->setSlug('My Title');
        $fixture->setDatecreation('My Title');
        $fixture->setCategorie('My Title');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/survey/');
    }
}
