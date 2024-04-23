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
                $villeDept = 'Lbv';
                $villeArrv = 'Moanda';
                $dateDept = new \DateTimeImmutable('2024-06-24');
                $paysDept = 'Gabon';
                $paysArrv = 'Gabon';
                $nbDePlace = 2;
                $prixPlace = 23;
                $nbPlaceArr = 2;
                $marqueVoiture = 'Peugeot 3008';
            }
            if ($i == 1) {
                $villeDept = 'Port-Gentil';
                $villeArrv = 'Mayumba';
                $dateDept = new \DateTimeImmutable('2024-07-05 19:57');
                $paysDept = 'Gabon';
                $paysArrv = 'Gabon';
                $nbDePlace = 3;
                $prixPlace = 21;
                $nbPlaceArr = 2;
                $marqueVoiture = 'Renault Clio';
            }
            if ($i == 2) {
                $villeDept = 'Lyon';
                $villeArrv = 'Toulouse';
                $dateDept = new \DateTimeImmutable('2024-07-22');
                $paysDept = 'France';
                $paysArrv = 'France';
                $nbDePlace = 3;
                $prixPlace = 25;
                $nbPlaceArr = 2;
                $marqueVoiture = 'Ford Fiesta';
            }
            if ($i == 3) {
                $villeDept = 'Lyon';
                $villeArrv = 'New York État de New York';
                $dateDept = new \DateTimeImmutable('2024-06-25');
                $paysDept = 'France';
                $paysArrv = 'États-Unis';
                $nbDePlace = 3;
                $prixPlace = 29;
                $nbPlaceArr = 2;
                $marqueVoiture = 'Ford';
            }
            if ($i == 4) {
                $villeDept = 'Lyon';
                $villeArrv = 'Okinawa';
                $dateDept = new \DateTimeImmutable('2024-08-24');
                $paysDept = 'France';
                $paysArrv = 'Japon';
                $nbDePlace = 4;
                $prixPlace = 21;
                $nbPlaceArr = 2;
                $marqueVoiture = 'citroen C3';
            }
            
            $trajet = new Trajet;
            $trajet->setVilleDept($villeDept);
            $trajet->setVilleArrv($villeArrv);
            $trajet->setDateDept($dateDept);
            $trajet->setPaysDept($paysDept);
            $trajet->setPaysArrv($paysArrv);
            $trajet->setNbrDePlace($nbDePlace);
            $trajet->setPrixPlace($prixPlace);
            $trajet->setNbrePlaceArr($nbPlaceArr);
            $trajet->setRendezVsDept('lieu rdv Dept');
            $trajet->setRendezVsArrv('lieu rdv Arrv');
            $trajet->setDescription('Je fais un déplacement pour des vacs et voir la famille....N\'ayant que peu d\' affaires, j\'ai de la place et je partage...');
            $trajet->setRestrictions('-Refus de produits illicites ou illégales, stupéfiants, drogues, Animaux...');
            $trajet->setMarqVoiture($marqueVoiture);
            
            $manager->persist($trajet);
            unset($trajet);
        }
        $manager->flush();
    }
}
