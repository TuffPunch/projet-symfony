<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[IsGranted("ROLE_ADMIN")]
    #[Route('/', name: 'app_reservation_show', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }


    #[IsGranted("ROLE_ADMIN")]
    #[Route('/validateReservation/{idR}', name: 'app_reservation_validation', methods: ['GET', 'POST'])]
    public function validate(Reservation $idR, Request $request, EntityManagerInterface $entityManager): Response
    {

        $idR->setValidator($this->getUser());
        $entityManager->persist($idR);
        $entityManager->flush();
        return $this->redirectToRoute('app_reservation_show');
    }

}
