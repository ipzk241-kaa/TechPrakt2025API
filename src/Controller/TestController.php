<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/books')]
class TestController extends AbstractController
{
    private $books = [['id' => 1, 'title' => '1984', 'author' => 'George Orwell'],
            ['id' => 2, 'title' => 'Brave New World', 'author' => 'Aldous Huxley'],
            ['id' => 3, 'title' => 'Fahrenheit 451', 'author' => 'Ray Bradbury']];

    #[Route('/', name:'list', methods:['GET'])]
    public function list(): JsonResponse
    {
        return $this->json($this->books);
    }

    
     #[Route("/", name:"create", methods:["POST"])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $book = [
            'id' => count($this->books) + 1,
            'title' => $data['title'] ?? 'Unknown',
            'author' => $data['author'] ?? 'Unknown'
        ];
        $this->books[] = $book;
        return $this->json($book, JsonResponse::HTTP_CREATED);
    }

    
     #[Route("/{id}", name:"show", methods:["GET"])]
    public function show(int $id): JsonResponse
    {
        foreach ($this->books as $book) {
            if ($book['id'] === $id) {
                return $this->json($book);
            }
        }
        return $this->json(['message' => 'Book not found'], JsonResponse::HTTP_NOT_FOUND);
    }

    #[Route("/{id}", name:"update", methods:["PATCH"])]
    public function update(int $id, Request $request): JsonResponse
    {
        foreach ($this->books as &$book) {
            if ($book['id'] === $id) {
                $data = json_decode($request->getContent(), true);
                $book['title'] = $data['title'] ?? $book['title'];
                $book['author'] = $data['author'] ?? $book['author'];
                return $this->json($book);
            }
        }
        return $this->json(['message' => 'Book not found'], JsonResponse::HTTP_NOT_FOUND);
    }

    #[Route("/{id}", name:"delete", methods:["DELETE"])]
    public function delete(int $id): JsonResponse
    {
        foreach ($this->books as $key => $book) {
            if ($book['id'] === $id) {
                unset($this->books[$key]);
                return $this->json(['message' => 'Book deleted']);
            }
        }
        return $this->json(['message' => 'Book not found'], JsonResponse::HTTP_NOT_FOUND);
    }
}
