<?php

namespace App\Tests\Controller;

use App\Entity\Editor;
use App\Repository\EditorRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EditorControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EditorRepository $repository;
    private string $path = '/editor/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Editor::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Editor index');

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
            'editor[name]' => 'Testing',
            'editor[createdAt]' => 'Testing',
            'editor[updatedAt]' => 'Testing',
            'editor[books]' => 'Testing',
        ]);

        self::assertResponseRedirects('/editor/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Editor();
        $fixture->setName('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setUpdatedAt('My Title');
        $fixture->setBooks('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Editor');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Editor();
        $fixture->setName('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setUpdatedAt('My Title');
        $fixture->setBooks('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'editor[name]' => 'Something New',
            'editor[createdAt]' => 'Something New',
            'editor[updatedAt]' => 'Something New',
            'editor[books]' => 'Something New',
        ]);

        self::assertResponseRedirects('/editor/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getUpdatedAt());
        self::assertSame('Something New', $fixture[0]->getBooks());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Editor();
        $fixture->setName('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setUpdatedAt('My Title');
        $fixture->setBooks('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/editor/');
    }
}
