<?php

namespace App\DataFixtures;

use App\Entity\Trajet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 5; $i++) {
            if ($i == 0) {
                $villeDept = 'LBV';
                $villeArrv = 'Moanda';
                $paysDept = 'Gabon';
                $paysArrv = 'Gabon';
                $dateDept = new \DateTimeImmutable('2024-06-24');
                $nbDePlace = 2;
                $prixPlace = 23000;
                $rendezVsDept = 'Carrefour';
                $rendezVsArrv = 'Marché';
                $description = 'Voyage cool';
                $restrictions = 'Pas d\'animaux';
                $nbPlaceArr = 2;
                $marqueVoiture = 'Peugeot 3008';
                $email = 'Véronica@email.com';
                $phone = '07201232';
            }
            if ($i == 1) {
                $villeDept = 'Port-Gentil';
                $villeArrv = 'Mayumba';
                $paysDept = 'Gabon';
                $paysArrv = 'Gabon';
                $dateDept = new \DateTimeImmutable('2024-07-05 19:57');
                $nbDePlace = 3;
                $prixPlace = 21000;
                $rendezVsDept = 'Carrefour';
                $rendezVsArrv = 'Marché';
                $description = 'Voyage leger';
                $restrictions = 'Pas d\'animaux, non fumeur';
                $nbPlaceArr = 2;
                $marqueVoiture = 'Renault Clio';
                $email = 'amandine@email.com';
                $phone = '06201232';
            }
            if ($i == 2) {
                $villeDept = 'Lyon';
                $villeArrv = 'Toulouse';
                $paysDept = 'France';
                $paysArrv = 'France';
                $dateDept = new \DateTimeImmutable('2024-07-22');
                $nbDePlace = 3;
                $prixPlace = 25000;
                $rendezVsDept = 'gare';
                $rendezVsArrv = 'gare';
                $description = 'Passager leger';
                $restrictions = 'Non fumeur';
                $nbPlaceArr = 2;
                $marqueVoiture = 'Ford Fiesta';
                $email = 'ema@email.com';
                $phone = '07201232';
            }
            if ($i == 3) {
                $villeDept = 'Lyon';
                $villeArrv = 'New York État de New York';
                $paysDept = 'France';
                $paysArrv = 'États-Unis';
                $dateDept = new \DateTimeImmutable('2024-06-25');
                $nbDePlace = 3;
                $prixPlace = 29000;
                $rendezVsDept = 'Marché';
                $rendezVsArrv = 'gare';
                $description = 'Passager leger';
                $restrictions = 'fumeur';
                $nbPlaceArr = 2;
                $marqueVoiture = 'Ford';
                $email = 'vesna@email.com';
                $phone = '07101432';
            }
            if ($i == 4) {
                $villeDept = 'Lyon';
                $villeArrv = 'Okinawa';
                $paysDept = 'France';
                $paysArrv = 'Japon';
                $dateDept = new \DateTimeImmutable('2024-08-24');
                $nbDePlace = 4;
                $prixPlace = 21000;
                $rendezVsDept = 'Marché';
                $rendezVsArrv = 'Marché';
                $description = 'Passager leger';
                $restrictions = 'fumeur';
                $nbPlaceArr = 2;
                $marqueVoiture = 'citroen C3';
                $email = 'deneris@email.com';
                $phone = '07501131';
            }

            $trajet = new Trajet;
            $trajet->setVilleDept($villeDept);
            $trajet->setVilleArrv($villeArrv);
            $trajet->setPaysDept($paysDept);
            $trajet->setPaysArrv($paysArrv);
            $trajet->setDateDept($dateDept);
            $trajet->setNbrDePlace($nbDePlace);
            $trajet->setPrixPlace($prixPlace);
            $trajet->setRendezVsDept($rendezVsDept);
            $trajet->setRendezVsArrv($rendezVsArrv);
            $trajet->setDescription($description);
            $trajet->setRestrictions($restrictions);
            $trajet->setNbrePlaceArr($nbPlaceArr);
            $trajet->setMarqVoiture($marqueVoiture);
            $trajet->setEmail($email);
            $trajet->setPhone($phone);

            $manager->persist($trajet);
            unset($trajet);
        }
        $manager->flush();
    }
}
