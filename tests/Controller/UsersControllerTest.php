<?php

namespace App\Test\Controller;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UsersControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UsersRepository $repository;
    private string $path = '/users1/controllers/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Users::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('User index');

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
            'user[nom]' => 'Testing',
            'user[prenom]' => 'Testing',
            'user[email]' => 'Testing',
            'user[password]' => 'Testing',
            'user[date_creation]' => 'Testing',
            'user[adresse]' => 'Testing',
            'user[Raison_Sociale]' => 'Testing',
            'user[telephone]' => 'Testing',
            'user[dateDeNaissance]' => 'Testing',
            'user[statut]' => 'Testing',
            'user[cin]' => 'Testing',
            'user[photo]' => 'Testing',
            'user[role]' => 'Testing',
            'user[banned]' => 'Testing',
        ]);

        self::assertResponseRedirects('/users1/controllers/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Users();
        $fixture->setNom('My Title');
        $fixture->setPrenom('My Title');
        $fixture->setEmail('My Title');
        $fixture->setPassword('My Title');
        $fixture->setDate_creation('My Title');
        $fixture->setAdresse('My Title');
        $fixture->setRaison_Sociale('My Title');
        $fixture->setTelephone('My Title');
        $fixture->setDateDeNaissance('My Title');
        $fixture->setStatut('My Title');
        $fixture->setCin('My Title');
        $fixture->setPhoto('My Title');
        $fixture->setRole('My Title');
        $fixture->setBanned('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('User');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Users();
        $fixture->setNom('My Title');
        $fixture->setPrenom('My Title');
        $fixture->setEmail('My Title');
        $fixture->setPassword('My Title');
        $fixture->setDate_creation('My Title');
        $fixture->setAdresse('My Title');
        $fixture->setRaison_Sociale('My Title');
        $fixture->setTelephone('My Title');
        $fixture->setDateDeNaissance('My Title');
        $fixture->setStatut('My Title');
        $fixture->setCin('My Title');
        $fixture->setPhoto('My Title');
        $fixture->setRole('My Title');
        $fixture->setBanned('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'user[nom]' => 'Something New',
            'user[prenom]' => 'Something New',
            'user[email]' => 'Something New',
            'user[password]' => 'Something New',
            'user[date_creation]' => 'Something New',
            'user[adresse]' => 'Something New',
            'user[Raison_Sociale]' => 'Something New',
            'user[telephone]' => 'Something New',
            'user[dateDeNaissance]' => 'Something New',
            'user[statut]' => 'Something New',
            'user[cin]' => 'Something New',
            'user[photo]' => 'Something New',
            'user[role]' => 'Something New',
            'user[banned]' => 'Something New',
        ]);

        self::assertResponseRedirects('/users1/controllers/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNom());
        self::assertSame('Something New', $fixture[0]->getPrenom());
        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getPassword());
        self::assertSame('Something New', $fixture[0]->getDate_creation());
        self::assertSame('Something New', $fixture[0]->getAdresse());
        self::assertSame('Something New', $fixture[0]->getRaison_Sociale());
        self::assertSame('Something New', $fixture[0]->getTelephone());
        self::assertSame('Something New', $fixture[0]->getDateDeNaissance());
        self::assertSame('Something New', $fixture[0]->getStatut());
        self::assertSame('Something New', $fixture[0]->getCin());
        self::assertSame('Something New', $fixture[0]->getPhoto());
        self::assertSame('Something New', $fixture[0]->getRole());
        self::assertSame('Something New', $fixture[0]->getBanned());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Users();
        $fixture->setNom('My Title');
        $fixture->setPrenom('My Title');
        $fixture->setEmail('My Title');
        $fixture->setPassword('My Title');
        $fixture->setDate_creation('My Title');
        $fixture->setAdresse('My Title');
        $fixture->setRaison_Sociale('My Title');
        $fixture->setTelephone('My Title');
        $fixture->setDateDeNaissance('My Title');
        $fixture->setStatut('My Title');
        $fixture->setCin('My Title');
        $fixture->setPhoto('My Title');
        $fixture->setRole('My Title');
        $fixture->setBanned('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/users1/controllers/');
    }
}
