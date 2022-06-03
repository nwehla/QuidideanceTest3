<?php

namespace App\Test\Controller;

use App\Entity\Reponse;
use App\Repository\ReponseRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReponseControllerTest extends WebTestCase
{
    /** @var KernelBrowser */
    private $client;
    /** @var ReponseRepository */
    private $repository;
    private $path = '/reponse/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(Reponse::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Reponse index');

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
            'reponse[titre]' => 'Testing',
            'reponse[slug]' => 'Testing',
            'reponse[datecreation]' => 'Testing',
            'reponse[datemiseajour]' => 'Testing',
            'reponse[question]' => 'Testing',
        ]);

        self::assertResponseRedirects('/reponse/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Reponse();
        $fixture->setTitre('My Title');
        $fixture->setSlug('My Title');
        $fixture->setDatecreation('My Title');
        $fixture->setDatemiseajour('My Title');
        $fixture->setQuestion('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Reponse');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Reponse();
        $fixture->setTitre('My Title');
        $fixture->setSlug('My Title');
        $fixture->setDatecreation('My Title');
        $fixture->setDatemiseajour('My Title');
        $fixture->setQuestion('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'reponse[titre]' => 'Something New',
            'reponse[slug]' => 'Something New',
            'reponse[datecreation]' => 'Something New',
            'reponse[datemiseajour]' => 'Something New',
            'reponse[question]' => 'Something New',
        ]);

        self::assertResponseRedirects('/reponse/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitre());
        self::assertSame('Something New', $fixture[0]->getSlug());
        self::assertSame('Something New', $fixture[0]->getDatecreation());
        self::assertSame('Something New', $fixture[0]->getDatemiseajour());
        self::assertSame('Something New', $fixture[0]->getQuestion());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Reponse();
        $fixture->setTitre('My Title');
        $fixture->setSlug('My Title');
        $fixture->setDatecreation('My Title');
        $fixture->setDatemiseajour('My Title');
        $fixture->setQuestion('My Title');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/reponse/');
    }
}
