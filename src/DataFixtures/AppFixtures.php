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
        $praticien1->setNumSeq(1);
        $praticien1->setIdPraticien(101);
        $praticien1->setNom('Durand');
        $praticien1->setPrenom('Michel');
        $praticien1->setSpecialite($specialite1);
        $manager->persist($praticien1);

        $praticien2 = new Praticien();
        $praticien2->setNumSeq(1);
        $praticien2->setIdPraticien(103);
        $praticien2->setNom('Robert');
        $praticien2->setPrenom('Axel');
        $praticien2->setSpecialite($specialite1);
        $manager->persist($praticien2);

        $praticien3 = new Praticien();
        $praticien3->setNumSeq(2);
        $praticien3->setIdPraticien(102);
        $praticien3->setNom('Bernard');
        $praticien3->setPrenom('Anna');
        $praticien3->setSpecialite($specialite2);
        $manager->persist($praticien3);

        $praticien4 = new Praticien();
        $praticien4->setNumSeq(2);
        $praticien4->setIdPraticien(103);
        $praticien4->setNom('Pidoux');
        $praticien4->setPrenom('Gaëlle');
        $praticien4->setSpecialite($specialite2);
        $manager->persist($praticien4);

        $praticien5 = new Praticien();
        $praticien5->setNumSeq(3);
        $praticien5->setIdPraticien(103);
        $praticien5->setNom('Morel');
        $praticien5->setPrenom('Julie');
        $praticien5->setSpecialite($specialite3);
        $manager->persist($praticien5);

        $praticien6 = new Praticien();
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
        $visite1->setPraticien($praticien2); // Robert Axel (1,103)
        $manager->persist($visite1);

        $visite3 = new Visite();
        $visite3->setId(3);
        $visite3->setDate(new \DateTime('2025-11-22'));
        $visite3->setMotif('Présentation du Codoliprane');
        $visite3->setBilan('Inutile, ça fait aucun effet !!!');
        $visite3->setCompteRendu(null);
        $visite3->setVisiteur($visiteur1);
        $visite3->setPraticien($praticien4); // Pidoux Gaëlle (2,103)
        $manager->persist($visite3);

        $visite4 = new Visite();
        $visite4->setId(4);
        $visite4->setDate(new \DateTime('2025-11-22'));
        $visite4->setMotif('Présentation de la Soupline');
        $visite4->setBilan('Elle adore mais aucun effet thérapeutique :(');
        $visite4->setCompteRendu(null);
        $visite4->setVisiteur($visiteur1);
        $visite4->setPraticien($praticien6); // Bakanova Zargan (3,104)
        $manager->persist($visite4);

        // ==================== PROPOSER (échantillons) ====================
        $proposer1 = new Proposer();
        $proposer1->setVisite($visite1);
        $proposer1->setMedicament($medicament1);
        $proposer1->setQuantite(5);
        $manager->persist($proposer1);

        $proposer3 = new Proposer();
        $proposer3->setVisite($visite3);
        $proposer3->setMedicament($medicament1);
        $proposer3->setQuantite(43);
        $manager->persist($proposer3);

        $proposer4 = new Proposer();
        $proposer4->setVisite($visite4);
        $proposer4->setMedicament($medicament1);
        $proposer4->setQuantite(1);
        $manager->persist($proposer4);

        // ==================== REPERTORIER ====================
        $rep1 = new Repertorier();
        $rep1->setPraticien($praticien2); // (1,103)
        $rep1->setVisiteur($visiteur1);
        $manager->persist($rep1);

        $rep2 = new Repertorier();
        $rep2->setPraticien($praticien4); // (2,103)
        $rep2->setVisiteur($visiteur1);
        $manager->persist($rep2);

        $rep3 = new Repertorier();
        $rep3->setPraticien($praticien6); // (3,104)
        $rep3->setVisiteur($visiteur1);
        $manager->persist($rep3);

        // ==================== PRESENTER (visiteur-région) ====================
        $presenter1 = new Presenter();
        $presenter1->setVisiteur($visiteur1);
        $presenter1->setRegion($region1);
        $presenter1->setDateAffect(new \DateTime('2024-01-10'));
        $manager->persist($presenter1);

        $presenter2 = new Presenter();
        $presenter2->setVisiteur($visiteur1);
        $presenter2->setRegion($region2);
        $presenter2->setDateAffect(new \DateTime('2024-02-01'));
        $manager->persist($presenter2);

        // ==================== TRAVAILLER (praticien-région) ====================
        $travail1 = new Travailler();
        $travail1->setPraticien($praticien1); // Durand (1,101)
        $travail1->setRegion($region1);
        $travail1->setDate(new \DateTime('2024-01-15'));
        $manager->persist($travail1);

        $travail2 = new Travailler();
        $travail2->setPraticien($praticien2); // Robert (1,103)
        $travail2->setRegion($region2);
        $travail2->setDate(new \DateTime('2024-05-15'));
        $manager->persist($travail2);

        $travail3 = new Travailler();
        $travail3->setPraticien($praticien3); // Bernard (2,102)
        $travail3->setRegion($region2);
        $travail3->setDate(new \DateTime('2024-03-12'));
        $manager->persist($travail3);

        $travail4 = new Travailler();
        $travail4->setPraticien($praticien4); // Pidoux (2,103)
        $travail4->setRegion($region2);
        $travail4->setDate(new \DateTime('2024-06-04'));
        $manager->persist($travail4);

        $travail5 = new Travailler();
        $travail5->setPraticien($praticien6); // Bakanova (3,104)
        $travail5->setRegion($region2);
        $travail5->setDate(new \DateTime('2024-04-09'));
        $manager->persist($travail5);

        $travail6 = new Travailler();
        $travail6->setPraticien($praticien5); // Morel (3,103)
        $travail6->setRegion($region3);
        $travail6->setDate(new \DateTime('2024-04-20'));
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
        $org1->setVisiteur($visiteur1);
        $org1->setAc($ac1);
        $manager->persist($org1);

        $org2 = new Organiser();
        $org2->setVisiteur($visiteur1);
        $org2->setAc($ac2);
        $manager->persist($org2);

        // ==================== PARTICIPER (praticien-AC) ====================
        $part1 = new Participer();
        $part1->setPraticien($praticien1); // Durand (1,101)
        $part1->setAc($ac1);
        $manager->persist($part1);

        $part2 = new Participer();
        $part2->setPraticien($praticien3); // Bernard (2,102)
        $part2->setAc($ac1);
        $manager->persist($part2);

        $part3 = new Participer();
        $part3->setPraticien($praticien5); // Morel (3,103)
        $part3->setAc($ac2);
        $manager->persist($part3);

        // Flush final
        $manager->flush();
    }
}