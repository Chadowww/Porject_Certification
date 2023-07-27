<?php

namespace App\Tests\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\User;
use App\Repository\AuthorRepository;
use phpDocumentor\Reflection\Types\Self_;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthorControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private AuthorRepository $repository;
    private string $path = '/author/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Author::class);

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
        self::assertPageTitleContains('Author index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Modifier', [
            'author[name]' => 'Testing',
            'author[biography]' => 'Testing',
        ]);

        self::assertResponseRedirects('/author/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $fixture = new Author();
        $fixture->setName('My Title');
        $fixture->setCreatedAt(new \DateTime());
        $fixture->setUpdatedAt(new \DateTime());
        $fixture->setBiography('My Title');
        $fixture->setAvatar('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains($fixture->getName());

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $fixture = new Author();
        $fixture->setName('My Title');
        $fixture->setBiography('My Title');
        $fixture->setAvatar('My Title');
        $fixture->setCreatedAt(new \DateTime());
        $fixture->setUpdatedAt(new \DateTime());

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('/admin/author'));
        self::assertResponseStatusCodeSame(200);


        $this->client->submitForm('modifier' . $fixture->getId(), [
            'name' => 'Something New',
            'biography' => 'Something New',
            'avatar' => 'Something New',
        ]);

        self::assertResponseRedirects('/admin/author');
        self::assertResponseStatusCodeSame(302);

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getBiography());
        self::assertSame('Something New', $fixture[0]->getAvatar());
    }

    public function testRemove(): void
    {

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Author();
        $fixture->setName('My Title');
        $fixture->setCreatedAt(new \DateTime());
        $fixture->setUpdatedAt(new \DateTime());
        $fixture->setBiography('My Title');
        $fixture->setAvatar('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s', 'admin/author'));
        self::assertResponseStatusCodeSame(200);
        $this->client->submitForm('supprimer');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/admin/author');
    }
}
