<?php

namespace App\DataFixtures;

use App\Entity\Ac;
use App\Entity\Medicament;
use App\Entity\Organiser;
use App\Entity\Participer;
use App\Entity\Praticien;
use App\Entity\Presenter;
use App\Entity\Profil;
use App\Entity\Proposer;
use App\Entity\Region;
use App\Entity\Repertorier;
use App\Entity\Specialite;
use App\Entity\Travailler;
use App\Entity\Visite;
use App\Entity\Visiteur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // ==================== REGIONS ====================
        $region1 = new Region();
        $region1->setId(1);
        $region1->setLibelle('Île-de-France');
        $manager->persist($region1);

        $region2 = new Region();
        $region2->setId(2);
        $region2->setLibelle('Occitanie');
        $manager->persist($region2);

        $region3 = new Region();
        $region3->setId(3);
        $region3->setLibelle('Nouvelle-Aquitaine');
        $manager->persist($region3);

        // ==================== SPECIALITES ====================
        $specialite1 = new Specialite();
        $specialite1->setNumeroSequentiel(1);
        $specialite1->setLibelle('Généraliste');
        $manager->persist($specialite1);

        $specialite2 = new Specialite();
        $specialite2->setNumeroSequentiel(2);
        $specialite2->setLibelle('Cardiologue');
        $manager->persist($specialite2);

        $specialite3 = new Specialite();
        $specialite3->setNumeroSequentiel(3);
        $specialite3->setLibelle('Dermatologue');
        $manager->persist($specialite3);

        // ==================== PROFILS ====================
        $profil1 = new Profil();
        $profil1->setId(1);
        $profil1->setUsername('admin1');
        $profil1->setPassword('$2y$10$4QWPguslKrE7wBDLjOYCVeLczQzYYWDMtVvUR2sWkNAyz/QEOz9MG');
        $profil1->setUsertype('admin');
        $manager->persist($profil1);

        $profil2 = new Profil();
        $profil2->setId(2);
        $profil2->setUsername('visiteur1');
        $profil2->setPassword('$2y$10$uCA81ccLsSLh5bVpU8gW5ux8lX3Whz7MqCj0nEO.Vwu0AmisyNgeG');
        $profil2->setUsertype('visiteur');
        $manager->persist($profil2);

        $profil3 = new Profil();
        $profil3->setId(3);
        $profil3->setUsername('responsable1');
        $profil3->setPassword('$2y$10$qq17H5GT30gfm/j.TtPeSuLIrKhfRl8twm.XeB.NBjKY9DBwJxj/6');
        $profil3->setUsertype('responsable');
        $manager->persist($profil3);

        // ==================== VISITEURS ====================
        $visiteur1 = new Visiteur();
        $visiteur1->setId(2);
        $visiteur1->setNom('VamichLab');
        $visiteur1->setProfil($profil2);
        $manager->persist($visiteur1);

        // ==================== PRATICIENS ====================
        $praticien1 = new Praticien();
        $praticien1->setId(1);
        $praticien1->setNumSeq(1);
        $praticien1->setIdPraticien(101);
        $praticien1->setNom('Durand');
        $praticien1->setPrenom('Michel');
        $praticien1->setSpecialite($specialite1);
        $manager->persist($praticien1);

        $praticien2 = new Praticien();
        $praticien2->setId(2);
        $praticien2->setNumSeq(1);
        $praticien2->setIdPraticien(103);
        $praticien2->setNom('Robert');
        $praticien2->setPrenom('Axel');
        $praticien2->setSpecialite($specialite1);
        $manager->persist($praticien2);

        $praticien3 = new Praticien();
        $praticien3->setId(3);
        $praticien3->setNumSeq(2);
        $praticien3->setIdPraticien(102);
        $praticien3->setNom('Bernard');
        $praticien3->setPrenom('Anna');
        $praticien3->setSpecialite($specialite2);
        $manager->persist($praticien3);

        $praticien4 = new Praticien();
        $praticien4->setId(4);
        $praticien4->setNumSeq(2);
        $praticien4->setIdPraticien(103);
        $praticien4->setNom('Pidoux');
        $praticien4->setPrenom('Gaëlle');
        $praticien4->setSpecialite($specialite2);
        $manager->persist($praticien4);

        $praticien5 = new Praticien();
        $praticien5->setId(5);
        $praticien5->setNumSeq(3);
        $praticien5->setIdPraticien(103);
        $praticien5->setNom('Morel');
        $praticien5->setPrenom('Julie');
        $praticien5->setSpecialite($specialite3);
        $manager->persist($praticien5);

        $praticien6 = new Praticien();
        $praticien6->setId(6);
        $praticien6->setNumSeq(3);
        $praticien6->setIdPraticien(104);
        $praticien6->setNom('Bakanova');
        $praticien6->setPrenom('Zargan');
        $praticien6->setSpecialite($specialite3);
        $manager->persist($praticien6);

        // ==================== MEDICAMENTS ====================
        $medicament1 = new Medicament();
        $medicament1->setId(1);
        $medicament1->setLibelle('Doliprane');
        $manager->persist($medicament1);

        $medicament2 = new Medicament();
        $medicament2->setId(2);
        $medicament2->setLibelle('Amoxicilline');
        $manager->persist($medicament2);

        $medicament3 = new Medicament();
        $medicament3->setId(3);
        $medicament3->setLibelle('Ibuprofène');
        $manager->persist($medicament3);

        // ==================== VISITES ====================
        $visite1 = new Visite();
        $visite1->setId(1);
        $visite1->setDate(new \DateTime('2025-11-22'));
        $visite1->setMotif('Présentation du Doliprane brûlé');
        $visite1->setBilan('Préfère manger des têtes brûlées :(');
        $visite1->setCompteRendu(null);
        $visite1->setVisiteur($visiteur1);
        $visite1->setPraticien($praticien2); // Robert Axel
        $manager->persist($visite1);

        $visite3 = new Visite();
        $visite3->setId(3);
        $visite3->setDate(new \DateTime('2025-11-22'));
        $visite3->setMotif('Présentation du Codoliprane');
        $visite3->setBilan('Inutile, ça fait aucun effet !!!');
        $visite3->setCompteRendu(null);
        $visite3->setVisiteur($visiteur1);
        $visite3->setPraticien($praticien4); // Pidoux Gaëlle
        $manager->persist($visite3);

        $visite4 = new Visite();
        $visite4->setId(4);
        $visite4->setDate(new \DateTime('2025-11-22'));
        $visite4->setMotif('Présentation de la Soupline');
        $visite4->setBilan('Elle adore mais aucun effet thérapeutique :(');
        $visite4->setCompteRendu(null);
        $visite4->setVisiteur($visiteur1);
        $visite4->setPraticien($praticien6); // Bakanova Zargan
        $manager->persist($visite4);

        // ==================== PROPOSER (échantillons) ====================
        $proposer1 = new Proposer();
        $proposer1->setVisiteId(1);
        $proposer1->setMedicamentId(1);
        $proposer1->setQuantite(5);
        $manager->persist($proposer1);

        $proposer3 = new Proposer();
        $proposer3->setVisiteId(3);
        $proposer3->setMedicamentId(1);
        $proposer3->setQuantite(43);
        $manager->persist($proposer3);

        $proposer4 = new Proposer();
        $proposer4->setVisiteId(4);
        $proposer4->setMedicamentId(1);
        $proposer4->setQuantite(1);
        $manager->persist($proposer4);

        // ==================== REPERTORIER ====================
        $rep1 = new Repertorier();
        $rep1->setNumSeq(1);
        $rep1->setIdPraticien(103);
        $rep1->setVisiteurId(2);
        $manager->persist($rep1);

        $rep2 = new Repertorier();
        $rep2->setNumSeq(2);
        $rep2->setIdPraticien(103);
        $rep2->setVisiteurId(2);
        $manager->persist($rep2);

        $rep3 = new Repertorier();
        $rep3->setNumSeq(3);
        $rep3->setIdPraticien(104);
        $rep3->setVisiteurId(2);
        $manager->persist($rep3);

        // ==================== PRESENTER (visiteur-région) ====================
        $presenter1 = new Presenter();
        $presenter1->setVisiteurId(2);
        $presenter1->setRegionId(1);
        $presenter1->setDateAffect('2024-01-10'); // STRING !
        $manager->persist($presenter1);

        $presenter2 = new Presenter();
        $presenter2->setVisiteurId(2);
        $presenter2->setRegionId(2);
        $presenter2->setDateAffect('2024-02-01'); // STRING !
        $manager->persist($presenter2);

        // ==================== TRAVAILLER (praticien-région) ====================
        $travail1 = new Travailler();
        $travail1->setNumSeq(1);
        $travail1->setIdPraticien(101);
        $travail1->setRegionId(1);
        $travail1->setDateA('2024-01-15'); // STRING !
        $manager->persist($travail1);

        $travail2 = new Travailler();
        $travail2->setNumSeq(1);
        $travail2->setIdPraticien(103);
        $travail2->setRegionId(2);
        $travail2->setDateA('2024-05-15'); // STRING !
        $manager->persist($travail2);

        $travail3 = new Travailler();
        $travail3->setNumSeq(2);
        $travail3->setIdPraticien(102);
        $travail3->setRegionId(2);
        $travail3->setDateA('2024-03-12'); // STRING !
        $manager->persist($travail3);

        $travail4 = new Travailler();
        $travail4->setNumSeq(2);
        $travail4->setIdPraticien(103);
        $travail4->setRegionId(2);
        $travail4->setDateA('2024-06-04'); // STRING !
        $manager->persist($travail4);

        $travail5 = new Travailler();
        $travail5->setNumSeq(3);
        $travail5->setIdPraticien(104);
        $travail5->setRegionId(2);
        $travail5->setDateA('2024-04-09'); // STRING !
        $manager->persist($travail5);

        $travail6 = new Travailler();
        $travail6->setNumSeq(3);
        $travail6->setIdPraticien(103);
        $travail6->setRegionId(3);
        $travail6->setDateA('2024-04-20'); // STRING !
        $manager->persist($travail6);

        // ==================== AC (Activités Complémentaires) ====================
        $ac1 = new Ac();
        $ac1->setId(1);
        $ac1->setTheme('Conférence régionale');
        $ac1->setDate(new \DateTime('2024-01-20'));
        $ac1->setLieu('Paris');
        $manager->persist($ac1);

        $ac2 = new Ac();
        $ac2->setId(2);
        $ac2->setTheme('Atelier thérapeutique');
        $ac2->setDate(new \DateTime('2024-03-05'));
        $ac2->setLieu('Toulouse');
        $manager->persist($ac2);

        // ==================== ORGANISER (visiteur-AC) ====================
        $org1 = new Organiser();
        $org1->setVisiteurId(2);
        $org1->setAcId(1);
        $manager->persist($org1);

        $org2 = new Organiser();
        $org2->setVisiteurId(2);
        $org2->setAcId(2);
        $manager->persist($org2);

        // ==================== PARTICIPER (praticien-AC) ====================
        $part1 = new Participer();
        $part1->setNumSeq(1);
        $part1->setIdPraticien(101);
        $part1->setAcId(1);
        $manager->persist($part1);

        $part2 = new Participer();
        $part2->setNumSeq(2);
        $part2->setIdPraticien(102);
        $part2->setAcId(1);
        $manager->persist($part2);

        $part3 = new Participer();
        $part3->setNumSeq(3);
        $part3->setIdPraticien(103);
        $part3->setAcId(2);
        $manager->persist($part3);

        // Flush final
        $manager->flush();
    }
}