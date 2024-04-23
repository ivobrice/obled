<?php

namespace App\Test\Controller;

use App\Entity\Trajet;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TrajetControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/trajet/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Trajet::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Trajet index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'trajet[villeDept]' => 'Testing',
            'trajet[villeArrv]' => 'Testing',
            'trajet[paysDept]' => 'Testing',
            'trajet[paysArrv]' => 'Testing',
            'trajet[dateDept]' => 'Testing',
            'trajet[nbrDePlace]' => 'Testing',
            'trajet[prixPlace]' => 'Testing',
            'trajet[rendezVsDept]' => 'Testing',
            'trajet[rendezVsArrv]' => 'Testing',
            'trajet[description]' => 'Testing',
            'trajet[restrictions]' => 'Testing',
            'trajet[marqVoiture]' => 'Testing',
            'trajet[nbrePlaceArr]' => 'Testing',
            'trajet[prenom]' => 'Testing',
            'trajet[email]' => 'Testing',
            'trajet[phone]' => 'Testing',
            'trajet[anneeNaiss]' => 'Testing',
            'trajet[createdAt]' => 'Testing',
            'trajet[updatedAt]' => 'Testing',
            'trajet[publish]' => 'Testing',
            'trajet[codeUser]' => 'Testing',
            'trajet[hashedCode]' => 'Testing',
            'trajet[hashedCode2]' => 'Testing',
            'trajet[user]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Trajet();
        $fixture->setVilleDept('My Title');
        $fixture->setVilleArrv('My Title');
        $fixture->setPaysDept('My Title');
        $fixture->setPaysArrv('My Title');
        $fixture->setDateDept('My Title');
        $fixture->setNbrDePlace('My Title');
        $fixture->setPrixPlace('My Title');
        $fixture->setRendezVsDept('My Title');
        $fixture->setRendezVsArrv('My Title');
        $fixture->setDescription('My Title');
        $fixture->setRestrictions('My Title');
        $fixture->setMarqVoiture('My Title');
        $fixture->setNbrePlaceArr('My Title');
        $fixture->setPrenom('My Title');
        $fixture->setEmail('My Title');
        $fixture->setPhone('My Title');
        $fixture->setAnneeNaiss('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setUpdatedAt('My Title');
        $fixture->setPublish('My Title');
        $fixture->setCodeUser('My Title');
        $fixture->setHashedCode('My Title');
        $fixture->setHashedCode2('My Title');
        $fixture->setUser('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Trajet');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Trajet();
        $fixture->setVilleDept('Value');
        $fixture->setVilleArrv('Value');
        $fixture->setPaysDept('Value');
        $fixture->setPaysArrv('Value');
        $fixture->setDateDept('Value');
        $fixture->setNbrDePlace('Value');
        $fixture->setPrixPlace('Value');
        $fixture->setRendezVsDept('Value');
        $fixture->setRendezVsArrv('Value');
        $fixture->setDescription('Value');
        $fixture->setRestrictions('Value');
        $fixture->setMarqVoiture('Value');
        $fixture->setNbrePlaceArr('Value');
        $fixture->setPrenom('Value');
        $fixture->setEmail('Value');
        $fixture->setPhone('Value');
        $fixture->setAnneeNaiss('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setUpdatedAt('Value');
        $fixture->setPublish('Value');
        $fixture->setCodeUser('Value');
        $fixture->setHashedCode('Value');
        $fixture->setHashedCode2('Value');
        $fixture->setUser('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'trajet[villeDept]' => 'Something New',
            'trajet[villeArrv]' => 'Something New',
            'trajet[paysDept]' => 'Something New',
            'trajet[paysArrv]' => 'Something New',
            'trajet[dateDept]' => 'Something New',
            'trajet[nbrDePlace]' => 'Something New',
            'trajet[prixPlace]' => 'Something New',
            'trajet[rendezVsDept]' => 'Something New',
            'trajet[rendezVsArrv]' => 'Something New',
            'trajet[description]' => 'Something New',
            'trajet[restrictions]' => 'Something New',
            'trajet[marqVoiture]' => 'Something New',
            'trajet[nbrePlaceArr]' => 'Something New',
            'trajet[prenom]' => 'Something New',
            'trajet[email]' => 'Something New',
            'trajet[phone]' => 'Something New',
            'trajet[anneeNaiss]' => 'Something New',
            'trajet[createdAt]' => 'Something New',
            'trajet[updatedAt]' => 'Something New',
            'trajet[publish]' => 'Something New',
            'trajet[codeUser]' => 'Something New',
            'trajet[hashedCode]' => 'Something New',
            'trajet[hashedCode2]' => 'Something New',
            'trajet[user]' => 'Something New',
        ]);

        self::assertResponseRedirects('/trajet/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getVilleDept());
        self::assertSame('Something New', $fixture[0]->getVilleArrv());
        self::assertSame('Something New', $fixture[0]->getPaysDept());
        self::assertSame('Something New', $fixture[0]->getPaysArrv());
        self::assertSame('Something New', $fixture[0]->getDateDept());
        self::assertSame('Something New', $fixture[0]->getNbrDePlace());
        self::assertSame('Something New', $fixture[0]->getPrixPlace());
        self::assertSame('Something New', $fixture[0]->getRendezVsDept());
        self::assertSame('Something New', $fixture[0]->getRendezVsArrv());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getRestrictions());
        self::assertSame('Something New', $fixture[0]->getMarqVoiture());
        self::assertSame('Something New', $fixture[0]->getNbrePlaceArr());
        self::assertSame('Something New', $fixture[0]->getPrenom());
        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getPhone());
        self::assertSame('Something New', $fixture[0]->getAnneeNaiss());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getUpdatedAt());
        self::assertSame('Something New', $fixture[0]->getPublish());
        self::assertSame('Something New', $fixture[0]->getCodeUser());
        self::assertSame('Something New', $fixture[0]->getHashedCode());
        self::assertSame('Something New', $fixture[0]->getHashedCode2());
        self::assertSame('Something New', $fixture[0]->getUser());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Trajet();
        $fixture->setVilleDept('Value');
        $fixture->setVilleArrv('Value');
        $fixture->setPaysDept('Value');
        $fixture->setPaysArrv('Value');
        $fixture->setDateDept('Value');
        $fixture->setNbrDePlace('Value');
        $fixture->setPrixPlace('Value');
        $fixture->setRendezVsDept('Value');
        $fixture->setRendezVsArrv('Value');
        $fixture->setDescription('Value');
        $fixture->setRestrictions('Value');
        $fixture->setMarqVoiture('Value');
        $fixture->setNbrePlaceArr('Value');
        $fixture->setPrenom('Value');
        $fixture->setEmail('Value');
        $fixture->setPhone('Value');
        $fixture->setAnneeNaiss('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setUpdatedAt('Value');
        $fixture->setPublish('Value');
        $fixture->setCodeUser('Value');
        $fixture->setHashedCode('Value');
        $fixture->setHashedCode2('Value');
        $fixture->setUser('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/trajet/');
        self::assertSame(0, $this->repository->count([]));
    }
}
