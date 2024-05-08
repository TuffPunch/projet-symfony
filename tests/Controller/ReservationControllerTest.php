<?php

namespace App\Test\Controller;

use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReservationControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/reservation/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Reservation::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Reservation index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'reservation[reservation_date]' => 'Testing',
            'reservation[return_date]' => 'Testing',
            'reservation[book]' => 'Testing',
            'reservation[student]' => 'Testing',
            'reservation[validator]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Reservation();
        $fixture->setReservation_date('My Title');
        $fixture->setReturn_date('My Title');
        $fixture->setBook('My Title');
        $fixture->setStudent('My Title');
        $fixture->setValidator('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Reservation');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Reservation();
        $fixture->setReservation_date('Value');
        $fixture->setReturn_date('Value');
        $fixture->setBook('Value');
        $fixture->setStudent('Value');
        $fixture->setValidator('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'reservation[reservation_date]' => 'Something New',
            'reservation[return_date]' => 'Something New',
            'reservation[book]' => 'Something New',
            'reservation[student]' => 'Something New',
            'reservation[validator]' => 'Something New',
        ]);

        self::assertResponseRedirects('/reservation/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getReservation_date());
        self::assertSame('Something New', $fixture[0]->getReturn_date());
        self::assertSame('Something New', $fixture[0]->getBook());
        self::assertSame('Something New', $fixture[0]->getStudent());
        self::assertSame('Something New', $fixture[0]->getValidator());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Reservation();
        $fixture->setReservation_date('Value');
        $fixture->setReturn_date('Value');
        $fixture->setBook('Value');
        $fixture->setStudent('Value');
        $fixture->setValidator('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/reservation/');
        self::assertSame(0, $this->repository->count([]));
    }
}
