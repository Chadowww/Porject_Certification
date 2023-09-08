<?php

namespace App\Tests\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Editor;
use App\Entity\User;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private BookRepository $repository;
    private string $path = '/book/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Book::class);
        $this->user = static::getContainer()->get('doctrine')->getRepository(User::class);
        $this->author = static::getContainer()->get('doctrine')->getRepository(Author::class);
        $this->category = static::getContainer()->get('doctrine')->getRepository(Category::class);
        $this->editor = static::getContainer()->get('doctrine')->getRepository(Editor::class);
        $this->book = static::getContainer()->get('doctrine')->getRepository(Book::class);
        $admin = $this->user->findOneBy(['email' => 'admin@outlook.fr']);

        if ($admin == null){
            $admin = new User();
            $admin->setRoles(['ROLE_ADMIN']);
            $admin->setFirstname('admin');
            $admin->setLastname('admin');
            $admin->setEmail('admin3@outlook.fr');
            $admin->setPassword('Fw7jzpdr7!');
            $this->user->save($admin, true);
        }

        $this->client->loginUser($admin);

//        foreach ($this->repository->findAll() as $object) {
//            $this->repository->remove($object, true);
//        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Book index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->client->request('GET', sprintf('/admin/book'));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Ajouter', [
            'book[title]' => 'Testing',
            'book[description]' => 'Testing',
            'book[publish]' => 'Testing',
            'book[qteStock]' => 'Testing',
            'book[qteCheckout]' => 'Testing',
            'book[author]' => $this->author->findOneBy(['id' => 10])->getId(),
            'book[category]' => $this->category->findOneBy(['id' => 13])->getId(),
            'book[editor]' => $this->editor->findOneBy(['id' => 13])->getId(),
            'book[cover]' => 'Testing',
        ]);

//        self::assertResponseRedirects('/admin/book');
        self::assertResponseStatusCodeSame(200);

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $book = $this->book->findOneBy(['id' => 1]);
        $this->client->request('GET', sprintf('%s%s', $this->path, $book->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains($book->getTitle());

    }

    public function testEdit(): void
    {
        $book = new Book();
        $book->setTitle('My Title');
        $book->setDescription('My Title');
        $book->setPublish(new \DateTime('now'));
        $book->setQteStock(5);
        $book->setQteCheckout(1);

        $this->repository->save($book, true);

        $this->client->request('GET', sprintf('%s','/admin/book'));

        $this->client->submitForm('modifier', [
            'title' => 'Something New',
            'description' => 'Something New',
            'publish' => 'Something New',
            'qteStock' => 3,
            'qteCheckout' => 2,
        ]);


//        self::assertResponseRedirects('/admin/book');
        $updatedBook = $this->repository->findAll();

        self::assertSame('Something New', $updatedBook[0]->getTitle());
        self::assertSame('Something New', $updatedBook[0]->getDescription());
        self::assertSame('Something New', $updatedBook[0]->getPublish());
        self::assertSame(3, $updatedBook[0]->getQteStock());
        self::assertSame(2, $updatedBook[0]->getQteCheckout());
    }

    public function testRemove(): void
    {

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Book();
        $fixture->setTitle('My Title');
        $fixture->setDescription('My Title');
        $fixture->setPublish(new \DateTime('now'));
        $fixture->setQteStock(5);
        $fixture->setQteCheckout(2);

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s', 'admin/book'));
        self::assertResponseStatusCodeSame(200);
        $this->client->submitForm('supprimer');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
//        self::assertResponseRedirects('/book/');
    }
}
