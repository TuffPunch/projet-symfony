<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Reservation;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/book')]
class BookController extends AbstractController
{
    #[IsGranted("ROLE_STUDENT")]
    #[Route('/list', name: 'student_book_index', methods: ['GET'])]
    public function student_index(BookRepository $bookRepository): Response
    {
        return $this->render('book/student_index.html.twig', [
            'books' => $bookRepository->findAll(),
        ]);
    }

    #[IsGranted("ROLE_ADMIN")]
    #[Route('/', name: 'app_book_index', methods: ['GET'])]
    public function index(BookRepository $bookRepository): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $bookRepository->findAll(),
        ]);
    }
    #[IsGranted("ROLE_ADMIN")]
    #[Route('/new', name: 'app_book_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book/new.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    #[IsGranted("ROLE_ADMIN")]
    #[Route('/{id}', name: 'app_book_show', methods: ['GET'])]
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }

    #[IsGranted("ROLE_STUDENT")]
    #[Route('/list/{id}', name: 'student_book_show', methods: ['GET'])]
    public function student_show(Book $book): Response
    {
        return $this->render('book/student_show.html.twig', [
            'book' => $book,
        ]);
    }


    #[IsGranted("ROLE_ADMIN")]
    #[Route('/{id}/edit', name: 'app_book_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Book $book, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book/edit.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }
    #[IsGranted("ROLE_ADMIN")]
    #[Route('/{id}', name: 'app_book_delete', methods: ['POST'])]
    public function delete(Request $request, Book $book, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($book);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
    }

    #[IsGranted("ROLE_STUDENT")]
    #[Route('/newReservation/{idb}', name: 'student_book_res', methods: ['GET', 'POST'])]
    public function reservate(Book $idb, Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservation();
       $reservation->setBookId($idb);
       $resD=new \DateTime();
       $reservation->setReservationDate($resD);
        $reservation->setStudent($this->getUser());
        $reservation->setReturnDate($resD->modify("+30 days"));
        $entityManager->persist($reservation);
        $entityManager->flush();
        return $this->redirectToRoute('student_book_index');
    }


}
