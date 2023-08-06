<?php

namespace App\Tests\Controller;

use App\Entity\Borrow;
use App\Entity\User;
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
        $this->user = static::getContainer()->get('doctrine')->getRepository(User::class);

        $admin =$this->user->findOneBy(['email' => 'admin@outlook.fr']);

        if ($admin == null) {
            $admin = new User();
            $admin->setRoles(['ROLE_ADMIN']);
            $admin->setFirstname('admin');
            $admin->setLastname('admin');
            $admin->setEmail('admin@outlook.fr');
            $admin->setPassword('Fw7jzpdr7!');
            $this->user->save($admin, true);
        }


        $this->client->loginUser($admin);
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

        $this->client->request('GET', sprintf('/admin/borrow'));

        self::assertResponseStatusCodeSame(200);

        $crawler = $this->client->submitForm('submit', [
            'borrow[checkin]' => '2023-08-09 08:20:00.0 UTC (+00:00)',
            'borrow[checkout]' => '2023-08-09 08:20:00.0 UTC (+00:00)',
            'borrow[user]' => 7,
            'borrow[book]' => 313,
        ]);
// Get the FormInterface object
        $form = $crawler->filter('form[name=borrow]')->form();

        // Get the errors from the FormInterface object
        $errors = $this->client->getContainer()->get('validator')->validate($form->getData());

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
