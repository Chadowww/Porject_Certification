<?php

namespace App\Test\Controller;

use App\Entity\Borrow;
use App\Repository\BorrowRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BorrowControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private BorrowRepository $repository;
    private string $path = '/borrow/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Borrow::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Borrow index');

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
            'borrow[checkin]' => 'Testing',
            'borrow[checkout]' => 'Testing',
            'borrow[user]' => 'Testing',
            'borrow[book]' => 'Testing',
        ]);

        self::assertResponseRedirects('/borrow/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Borrow();
        $fixture->setCheckin('My Title');
        $fixture->setCheckout('My Title');
        $fixture->setUser('My Title');
        $fixture->setBook('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Borrow');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Borrow();
        $fixture->setCheckin('My Title');
        $fixture->setCheckout('My Title');
        $fixture->setUser('My Title');
        $fixture->setBook('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'borrow[checkin]' => 'Something New',
            'borrow[checkout]' => 'Something New',
            'borrow[user]' => 'Something New',
            'borrow[book]' => 'Something New',
        ]);

        self::assertResponseRedirects('/borrow/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getCheckin());
        self::assertSame('Something New', $fixture[0]->getCheckout());
        self::assertSame('Something New', $fixture[0]->getUser());
        self::assertSame('Something New', $fixture[0]->getBook());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Borrow();
        $fixture->setCheckin('My Title');
        $fixture->setCheckout('My Title');
        $fixture->setUser('My Title');
        $fixture->setBook('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/borrow/');
    }
}
