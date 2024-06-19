<?php

namespace App\Controller;

use App\Entity\Trajet;
use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use App\Service\BuildHashedCode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/dashboard', name: 'app_reservation_index', methods: ['GET'])]
    public function dashboard(Request $request, EntityManagerInterface $em, ReservationRepository $rm): Response
    {
        $user = 1;
        if ($user) {      // Mettre derriere par-feu le tabl de bord $user = $this->getUser() && role;
            $indiceDv = 0;
            $flush = false;
            $indiceNews = 0;
            $oldReservation = null;
            $currentDate = new \Datetime();
            if ($reservations = $rm->findWithTrajet('id')) {  //recup les reservations d'aujourd8 et d'hier nn validés 
                $newReservations = [];
                foreach ($reservations as $reservation) {
                    $trajet = $reservation->getTrajet();
                    $interval = $reservation->getCreatedAt()->diff($currentDate);
                    $interval2 = $trajet->getDateDept()->diff($currentDate);
                    if ($reservation->getCreatedAt()->format('Y-m-d') == $currentDate->format('Y-m-d') or (($interval->format('%a') <= 1 && ($reservation->getCreatedAt()->format('d') + 1) == $currentDate->format('d') /*&& $reservation->getCreatedAt()->format('H') > 13*/) && ($interval2->invert or $trajet->getDateDept()->format('Y-m-d') == $currentDate->format('Y-m-d')))) {
                        if ($reservation->getCreatedAt()->format('Y-m-d') != $currentDate->format('Y-m-d'))
                            $oldReservation++;
                        else
                            $indiceNews++;
                        $newReservations[] = $reservation;
                    } else {
                        $em->remove($reservation);
                        $flush = true;
                    }
                    unset($trajet, $interval, $interval2);
                }
                unset($reservations);
            } else
                $newReservations = null;
            if ($reservationsDv = $rm->findWithTrajet('dateValidationClient', $currentDate->format('Y-m-d'), true))  //recup les reservations validés
                $indiceDv = count($reservationsDv);

            if ($currentDate->format('d') > 4) {   //sup les reservations des trajets partis
                $dateRecupRsrvParti = ($request->getSession()->get('dateRecupRsrvParti')) ? $request->getSession()->get('dateRecupRsrvParti') : $currentDate;
                $interval = $dateRecupRsrvParti->diff($currentDate);
                if ($interval->format('%h') > 3 or $dateRecupRsrvParti->format('Y-m-d') != $currentDate->format('Y-m-d')) {
                    $day = $currentDate->format('d') - 4;
                    $day = ($day > 9) ? $day : '0' . $day;
                    $dated = $currentDate->format('Y') . '-' . $currentDate->format('m') . '-' . $day;
                    if ($reservationsDp = $rm->findWithTrajet(false, $dated)) {
                        $flush = true;
                        foreach ($reservationsDp as $reservationDv)
                            $em->remove($reservationDv);
                        unset($reservationsDp);
                    }
                    $request->getSession()->set('dateRecupRsrvParti', $currentDate);
                } else
                    $request->getSession()->set('dateRecupRsrvParti', $dateRecupRsrvParti);
            }
            if ($flush)
                $em->flush();
            unset($em, $rm, $currentDate, $interval);
            return $this->render('reservation/dashboard.html.twig', [
                'title' => 'Réservations du jour', 'reservationsDv' => $reservationsDv,
                'newReservations' => $newReservations, 'nbReservation' => $indiceNews, 'nbReservationDv' => $indiceDv,
                'oldReservation' => $oldReservation
            ]);
        }
    }

    #[Route('/validation/{id}/{hashedCode}', name: 'app_reservation_validation', methods: ['GET'])]
    public function validation($id = null, $hashedCode = null, BuildHashedCode $buildCode, EntityManagerInterface $em, ReservationRepository $rm, MailerInterface $mailer): Response
    {
        $user = 1;
        if ($user) {    // Mettre derriere par-feu $user = $this->getUser() && role;
            if ($id && $hashedCode) {
                if ($reservation = $rm->findOneBy(['id' => $id, 'publish' => true])) {
                    $hashedCodeOrigin = $buildCode->buildHashedCodeOrigin($hashedCode, $reservation->getHashedCode2());
                    if (password_verify($reservation->getCodeUser(), $hashedCodeOrigin)) {
                        $trajet = $reservation->getTrajet();
                        if ($this->checkTimeDept($trajet->getDateDept())) {
                            $nbPlace = $trajet->getNbrDePlace() - $reservation->getNbrDePlaceRsrv();
                            if ($nbPlace >= 0) {
                                $reservation->setPublish(false);
                                $reservation->setDateValidationClient(new \DateTimeImmutable());
                                $trajet->setNbrDePlace($nbPlace);
                                $em->flush();
                                $this->addFlash('success', 'Réservation payée avec succès. Informer le client en l\'envoyant les informations qui suivent: ' . $reservation()->getPhoneChauf() ? $reservation()->getPhoneChauf() : $trajet()->getUser()->getPhone() . ' : Code de reservation ' . $reservation->getCodeUser() . ' (à présenter au départ)');
                                //envoyer mail au condcuteur + passager
                                $emailPassager = (new TemplatedEmail())
                                    ->from(new Address('admin@obled.com', 'Partner'))
                                    ->to($reservation->getMailPassager())
                                    ->subject('Réservation passager!')
                                    ->htmlTemplate('trajet/emailPaiement.html.twig');
                                $mailer->send($emailPassager);

                                $emailChauf = (new TemplatedEmail())
                                    ->from(new Address('admin@obled.com', 'Partner'))
                                    ->to($reservation->getMailChauf())
                                    ->subject('Réservation passager!')
                                    ->htmlTemplate('trajet/reservationPassager.html.twig');
                                $mailer->send($emailChauf);
                            } else {
                                if ($reservation->getNbrDePlaceRsrv() == 1)
                                    $place = 'place';
                                else
                                    $place = 'places';
                                if ($trajet->getNbrDePlace() == 1)
                                    $phrase = 'qu\'une place disponible';
                                elseif ($trajet->getNbrDePlace() > 1)
                                    $phrase = 'que ' . $trajet->getNbrDePlace() . ' places disponibles';
                                else
                                    $phrase = 'de place disponible';
                                $this->addFlash('notice', 'Paiement refusée. Le client a reservé ' . $reservation->getNbrDePlaceRsrv() . ' ' . $place . ' Mais il ne reste plus ' . $phrase . ' pour ce trajet !<br>
                                    Veuillez informer le client ' . $reservation->getPhonePassager() . ' en lui renvoyant son transfert !');
                                $em->remove($reservation);
                            }
                        } else {
                            $this->addFlash('notice', 'Réservation refusée, car le conducteur est déja parti (heure de départ dépassée, le paiement est arrivée trop tard) veuillez informer le client ' . $reservation->getPhonePassager() . ' en lui renvoyant son transfert!');
                            $em->remove($reservation);
                        }
                    } else
                        $this->addFlash('notice', 'La Réservation n\'existe pas !');
                }
                return $this->redirectToRoute('app_reservation_index');
            }
        }
    }

    #[Route('/trajet', name: 'app_reservation_trajet', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $post = $request->request->all();
            if (!empty($post['mailPassager']) && !empty($post['nbrDePlaceRsrv']) && !empty($post['idTrajet'])) {
                if ($trajet = $em->getRepository(trajet::class)->find($post['idTrajet'])) {
                    if ($this->checkTimeDept($trajet->getDateDept())) {
                        $nbPlace = $trajet->getNbrDePlace() - $post['nbrDePlaceRsrv'];
                        if ($nbPlace >= 0) {
                            $reservation = new Reservation();
                            $reservation->setTrajet($trajet);
                            $reservation->setPrixPlaceRsrv($post['nbrDePlaceRsrv']);
                            $reservation->setMailPassager($post['mailPassager']);
                            if (!empty($post['phonePassager']))
                                $reservation->setPhonePassager($post['phonePassager']);
                            $reservation->setMailChauf($trajet->getEmail());
                            $reservation->setPhoneChauf($trajet->getPhone());
                            $reservation->setDateDept($trajet->getDateDept());
                            $reservation->setVilleDept($trajet->getVilleDept());
                            $reservation->setVilleArrv($trajet->getVilleArrv());
                            if ($this->getUser())
                                $reservation->setUser($this->getUser());
                            $em->persist($reservation);
                            if ($hashedCodeClient = $reservation->getHashedCode()) {
                                dd($reservation);
                                $em->flush();
                                return $this->redirectToRoute(
                                    'app_afficheEntity',
                                    [
                                        'page' => 'reservation', 'villeDept' => $trajet->getVilleDept(),
                                        'villeArrv' => $trajet->getVilleArrv(), 'id' => $reservation->getId(),
                                        'hashedCode' => $hashedCodeClient
                                    ],
                                    Response::HTTP_SEE_OTHER
                                );
                            }
                        } else {
                            if ($trajet->getNbrDePlace() == 1)
                                $place = 'qu\'une place disponible';
                            elseif ($trajet->getNbrDePlace() > 1)
                                $place = 'que ' . $trajet->getNbrDePlace() . ' places disponibles';
                            else
                                $place = 'de place disponible';
                            $this->addFlash('danger', 'Plus ' . $place . ' pour ce trajet !');
                            return $this->redirectToRoute('app_trajet_show', ['id' => $post['idTrajet'], 'villeDept' => $trajet->getVilleDept(), 'villeArrv' => $trajet->getVilleArrv()]);
                        }
                    } else
                        $this->addFlash('danger', 'Réservation impossible, la date de départ du voyage est dépassé.');
                }
            } else
                $this->addFlash('danger', 'Vos informations sont incomplètes');
            return $this->redirectToRoute('app_trajet');
        }
        return $this->redirectToRoute('app_trajet');
    }

    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reservation->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }

    public function checkTimeDept($dateDept)
    {
        $currentDate = new \Datetime();
        $interval = $currentDate->diff($dateDept);
        if ($interval->format('%R') == '+') {
            if (($dateDept->format('Y-m-d') == $currentDate->format('Y-m-d')) && $interval->format('%h') == 0 && $interval->format('%i') < 12)
                return FALSE;
            return TRUE;
        }
        return FALSE;
    }
}
