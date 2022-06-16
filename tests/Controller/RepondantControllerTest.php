<?php

namespace App\Test\Controller;

use App\Entity\Repondant;
use App\Repository\RepondantRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RepondantControllerTest extends WebTestCase
{
    /** @var KernelBrowser */
    private $client;
    /** @var RepondantRepository */
    private $repository;
    private $path = '/repondant/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(Repondant::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Repondant index');

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
            'repondant[repondant]' => 'Testing',
            'repondant[commentaire]' => 'Testing',
            'repondant[email]' => 'Testing',
            'repondant[acceptationpartagedonnee]' => 'Testing',
            'repondant[datecreation]' => 'Testing',
            'repondant[datemiseajour]' => 'Testing',
            'repondant[datefermeture]' => 'Testing',
            'repondant[slug]' => 'Testing',
        ]);

        self::assertResponseRedirects('/repondant/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Repondant();
        $fixture->setRepondant('My Title');
        $fixture->setCommentaire('My Title');
        $fixture->setEmail('My Title');
        $fixture->setAcceptationpartagedonnee('My Title');
        $fixture->setDatecreation('My Title');
        $fixture->setDatemiseajour('My Title');
        $fixture->setDatefermeture('My Title');
        $fixture->setSlug('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Repondant');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Repondant();
        $fixture->setRepondant('My Title');
        $fixture->setCommentaire('My Title');
        $fixture->setEmail('My Title');
        $fixture->setAcceptationpartagedonnee('My Title');
        $fixture->setDatecreation('My Title');
        $fixture->setDatemiseajour('My Title');
        $fixture->setDatefermeture('My Title');
        $fixture->setSlug('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'repondant[repondant]' => 'Something New',
            'repondant[commentaire]' => 'Something New',
            'repondant[email]' => 'Something New',
            'repondant[acceptationpartagedonnee]' => 'Something New',
            'repondant[datecreation]' => 'Something New',
            'repondant[datemiseajour]' => 'Something New',
            'repondant[datefermeture]' => 'Something New',
            'repondant[slug]' => 'Something New',
        ]);

        self::assertResponseRedirects('/repondant/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getRepondant());
        self::assertSame('Something New', $fixture[0]->getCommentaire());
        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getAcceptationpartagedonnee());
        self::assertSame('Something New', $fixture[0]->getDatecreation());
        self::assertSame('Something New', $fixture[0]->getDatemiseajour());
        self::assertSame('Something New', $fixture[0]->getDatefermeture());
        self::assertSame('Something New', $fixture[0]->getSlug());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Repondant();
        $fixture->setRepondant('My Title');
        $fixture->setCommentaire('My Title');
        $fixture->setEmail('My Title');
        $fixture->setAcceptationpartagedonnee('My Title');
        $fixture->setDatecreation('My Title');
        $fixture->setDatemiseajour('My Title');
        $fixture->setDatefermeture('My Title');
        $fixture->setSlug('My Title');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/repondant/');
    }
}
