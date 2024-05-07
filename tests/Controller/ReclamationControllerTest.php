<?php

namespace App\Test\Controller;

use App\Entity\Reclamation;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReclamationControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ReclamationRepository $repository;
    private string $path = '/reclamation/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Reclamation::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Reclamation index');

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
            'reclamation[title]' => 'Testing',
            'reclamation[dateCreation]' => 'Testing',
            'reclamation[Description]' => 'Testing',
            'reclamation[Etat]' => 'Testing',
            'reclamation[Reponse]' => 'Testing',
            'reclamation[userid]' => 'Testing',
        ]);

        self::assertResponseRedirects('/reclamation/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Reclamation();
        $fixture->setTitle('My Title');
        $fixture->setDateCreation('My Title');
        $fixture->setDescription('My Title');
        $fixture->setEtat('My Title');
        $fixture->setReponse('My Title');
        $fixture->setUserid('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Reclamation');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Reclamation();
        $fixture->setTitle('My Title');
        $fixture->setDateCreation('My Title');
        $fixture->setDescription('My Title');
        $fixture->setEtat('My Title');
        $fixture->setReponse('My Title');
        $fixture->setUserid('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'reclamation[title]' => 'Something New',
            'reclamation[dateCreation]' => 'Something New',
            'reclamation[Description]' => 'Something New',
            'reclamation[Etat]' => 'Something New',
            'reclamation[Reponse]' => 'Something New',
            'reclamation[userid]' => 'Something New',
        ]);

        self::assertResponseRedirects('/reclamation/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getDateCreation());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getEtat());
        self::assertSame('Something New', $fixture[0]->getReponse());
        self::assertSame('Something New', $fixture[0]->getUserid());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Reclamation();
        $fixture->setTitle('My Title');
        $fixture->setDateCreation('My Title');
        $fixture->setDescription('My Title');
        $fixture->setEtat('My Title');
        $fixture->setReponse('My Title');
        $fixture->setUserid('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/reclamation/');
    }
}
