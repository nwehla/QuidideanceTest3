<?php

namespace App\Test\Controller;

use App\Entity\Reponsequestion;
use App\Repository\ReponsequestionRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class ReponsequestionControllerTest extends WebTestCase
{
    /** @var KernelBrowser */
    private $client;
    /** @var ReponsequestionRepository */
    private $repository;
    private $path = '/reponsequestion/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(Reponsequestion::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Reponsequestion index');

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
            'reponsequestion[datecreation]' => 'Testing',
            'reponsequestion[datemiseajour]' => 'Testing',
            'reponsequestion[slug]' => 'Testing',
            'reponsequestion[titre]' => 'Testing',
            'reponsequestion[question]' => 'Testing',
        ]);

        self::assertResponseRedirects('/reponsequestion/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Reponsequestion();
        $fixture->setDatecreation('My Title');
        $fixture->setDatemiseajour('My Title');
        $fixture->setSlug('My Title');
        $fixture->setTitre('My Title');
        $fixture->setQuestion('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Reponsequestion');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Reponsequestion();
        $fixture->setDatecreation('My Title');
        $fixture->setDatemiseajour('My Title');
        $fixture->setSlug('My Title');
        $fixture->setTitre('My Title');
        $fixture->setQuestion('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'reponsequestion[datecreation]' => 'Something New',
            'reponsequestion[datemiseajour]' => 'Something New',
            'reponsequestion[slug]' => 'Something New',
            'reponsequestion[titre]' => 'Something New',
            'reponsequestion[question]' => 'Something New',
        ]);

        self::assertResponseRedirects('/reponsequestion/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getDatecreation());
        self::assertSame('Something New', $fixture[0]->getDatemiseajour());
        self::assertSame('Something New', $fixture[0]->getSlug());
        self::assertSame('Something New', $fixture[0]->getTitre());
        self::assertSame('Something New', $fixture[0]->getQuestion());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Reponsequestion();
        $fixture->setDatecreation('My Title');
        $fixture->setDatemiseajour('My Title');
        $fixture->setSlug('My Title');
        $fixture->setTitre('My Title');
        $fixture->setQuestion('My Title');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/reponsequestion/');
    }
}
