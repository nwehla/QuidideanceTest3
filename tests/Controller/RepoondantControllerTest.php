<?php

namespace App\Test\Controller;

use App\Entity\Repoondant;
use App\Repository\RepoondantRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RepoondantControllerTest extends WebTestCase
{
    /** @var KernelBrowser */
    private $client;
    /** @var RepoondantRepository */
    private $repository;
    private $path = '/repoondant/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(Repoondant::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Repoondant index');

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
            'repoondant[repondant]' => 'Testing',
            'repoondant[commentaire]' => 'Testing',
            'repoondant[email]' => 'Testing',
            'repoondant[acceptationpartagedonnee]' => 'Testing',
            'repoondant[datecreation]' => 'Testing',
            'repoondant[datemiseajour]' => 'Testing',
            'repoondant[datefermeture]' => 'Testing',
            'repoondant[slug]' => 'Testing',
        ]);

        self::assertResponseRedirects('/repoondant/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Repoondant();
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
        self::assertPageTitleContains('Repoondant');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Repoondant();
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
            'repoondant[repondant]' => 'Something New',
            'repoondant[commentaire]' => 'Something New',
            'repoondant[email]' => 'Something New',
            'repoondant[acceptationpartagedonnee]' => 'Something New',
            'repoondant[datecreation]' => 'Something New',
            'repoondant[datemiseajour]' => 'Something New',
            'repoondant[datefermeture]' => 'Something New',
            'repoondant[slug]' => 'Something New',
        ]);

        self::assertResponseRedirects('/repoondant/');

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

        $fixture = new Repoondant();
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
        self::assertResponseRedirects('/repoondant/');
    }
}
