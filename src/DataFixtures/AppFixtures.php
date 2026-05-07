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
        // REGIONS
        $region1 = (new Region())->setId(1)->setLibelle('Île-de-France');
        $region2 = (new Region())->setId(2)->setLibelle('Occitanie');
        $region3 = (new Region())->setId(3)->setLibelle('Nouvelle-Aquitaine');
        $manager->persist($region1);
        $manager->persist($region2);
        $manager->persist($region3);

        // Flush pour générer les IDs des régions et spécialités
        $manager->flush();

        // SPECIALITES
        $specialite1 = (new Specialite())->setNumeroSequentiel(1)->setLibelle('Généraliste');
        $specialite2 = (new Specialite())->setNumeroSequentiel(2)->setLibelle('Cardiologue');
        $specialite3 = (new Specialite())->setNumeroSequentiel(3)->setLibelle('Dermatologue');
        $manager->persist($specialite1);
        $manager->persist($specialite2);
        $manager->persist($specialite3);

        // PROFILS
        $profil1 = (new Profil())
            ->setEmail('admin1@example.com')
            ->setPassword('$2y$10$4QWPguslKrE7wBDLjOYCVeLczQzYYWDMtVvUR2sWkNAyz/QEOz9MG')
            ->setUsertype('admin');
        $profil2 = (new Profil())
            ->setEmail('visiteur1@example.com')
            ->setPassword('$2y$10$uCA81ccLsSLh5bVpU8gW5ux8lX3Whz7MqCj0nEO.Vwu0AmisyNgeG')
            ->setUsertype('visiteur');
        $profil3 = (new Profil())
            ->setEmail('responsable1@example.com')
            ->setPassword('$2y$10$qq17H5GT30gfm/j.TtPeSuLIrKhfRl8twm.XeB.NBjKY9DBwJxj/6')
            ->setUsertype('responsable');
        $manager->persist($profil1);
        $manager->persist($profil2);
        $manager->persist($profil3);

        $manager->flush(); // Flush pour générer les IDs des profils

        // VISITEUR
        $visiteur1 = (new Visiteur())->setNom('VamichLab')->setProfil($profil2);
        $manager->persist($visiteur1);

        // PRATICIENS
        $praticien1 = (new Praticien())
            ->setNumeroSequentiel($specialite1->getNumeroSequentiel())->setIdPraticien(1)
            ->setNom('Durand')->setPrenom('Michel')->setSpecialite($specialite1);
        $praticien2 = (new Praticien())
            ->setNumeroSequentiel($specialite1->getNumeroSequentiel())->setIdPraticien(2)
            ->setNom('Robert')->setPrenom('Axel')->setSpecialite($specialite1);
        $praticien3 = (new Praticien())
            ->setNumeroSequentiel($specialite2->getNumeroSequentiel())->setIdPraticien(1)
            ->setNom('Bernard')->setPrenom('Anna')->setSpecialite($specialite2);
        $praticien4 = (new Praticien())
            ->setNumeroSequentiel($specialite2->getNumeroSequentiel())->setIdPraticien(2)
            ->setNom('Pidoux')->setPrenom('Gaëlle')->setSpecialite($specialite2);
        $praticien5 = (new Praticien())
            ->setNumeroSequentiel($specialite3->getNumeroSequentiel())->setIdPraticien(1)
            ->setNom('Morel')->setPrenom('Julie')->setSpecialite($specialite3);
        $praticien6 = (new Praticien())
            ->setNumeroSequentiel($specialite3->getNumeroSequentiel())->setIdPraticien(2)
            ->setNom('Bakanova')->setPrenom('Zargan')->setSpecialite($specialite3);
        $manager->persist($praticien1);
        $manager->persist($praticien2);
        $manager->persist($praticien3);
        $manager->persist($praticien4);
        $manager->persist($praticien5);
        $manager->persist($praticien6);

        // MEDICAMENTS
        $medicament1 = (new Medicament())->setLibelle('Doliprane');
        $medicament2 = (new Medicament())->setLibelle('Amoxicilline');
        $medicament3 = (new Medicament())->setLibelle('Ibuprofène');
        $manager->persist($medicament1);
        $manager->persist($medicament2);
        $manager->persist($medicament3);

        $manager->flush();

        // VISITES
        $visite1 = (new Visite())
            ->setDate(new \DateTime('2025-11-22'))
            ->setMotif('Présentation du Doliprane brûlé')
            ->setBilan('Préfère manger des têtes brûlées :(')
            ->setIdVisiteur($visiteur1->getId())
            ->setNumeroSequentiel($praticien2->getNumeroSequentiel())
            ->setIdPraticien($praticien2->getIdPraticien())
            ->setVisiteur($visiteur1)
            ->setPraticien($praticien2);
        $visite2 = (new Visite())
            ->setDate(new \DateTime('2025-11-22'))
            ->setMotif('Présentation du Codoliprane')
            ->setBilan('Inutile, ça fait aucun effet !!!')
            ->setIdVisiteur($visiteur1->getId())
            ->setNumeroSequentiel($praticien4->getNumeroSequentiel())
            ->setIdPraticien($praticien4->getIdPraticien())
            ->setVisiteur($visiteur1)
            ->setPraticien($praticien4);
        $visite3 = (new Visite())
            ->setDate(new \DateTime('2025-11-22'))
            ->setMotif('Présentation de la Soupline')
            ->setBilan('Elle adore mais aucun effet thérapeutique :(')
            ->setIdVisiteur($visiteur1->getId())
            ->setNumeroSequentiel($praticien6->getNumeroSequentiel())
            ->setIdPraticien($praticien6->getIdPraticien())
            ->setVisiteur($visiteur1)
            ->setPraticien($praticien6);
        $manager->persist($visite1);
        $manager->persist($visite2);
        $manager->persist($visite3);

        $manager->flush(); // Flush pour générer les IDs des visites

        // PROPOSER
        $proposer1 = (new Proposer())->setIdVisite($visite1->getId())->setIdMedicament($medicament1->getId())->setNbEchantillon(5)->setVisite($visite1)->setMedicament($medicament1);
        $proposer2 = (new Proposer())->setIdVisite($visite2->getId())->setIdMedicament($medicament1->getId())->setNbEchantillon(43)->setVisite($visite2)->setMedicament($medicament1);
        $proposer3 = (new Proposer())->setIdVisite($visite3->getId())->setIdMedicament($medicament1->getId())->setNbEchantillon(1)->setVisite($visite3)->setMedicament($medicament1);
        $manager->persist($proposer1);
        $manager->persist($proposer2);
        $manager->persist($proposer3);

        $manager->flush(); // Flush après proposer

        // REPERTORIER
        $rep1 = (new Repertorier())->setNumeroSequentiel($praticien2->getNumeroSequentiel())->setIdPraticien($praticien2->getIdPraticien())->setIdVisiteur($visiteur1->getId())->setPraticien($praticien2)->setVisiteur($visiteur1);
        $rep2 = (new Repertorier())->setNumeroSequentiel($praticien4->getNumeroSequentiel())->setIdPraticien($praticien4->getIdPraticien())->setIdVisiteur($visiteur1->getId())->setPraticien($praticien4)->setVisiteur($visiteur1);
        $rep3 = (new Repertorier())->setNumeroSequentiel($praticien6->getNumeroSequentiel())->setIdPraticien($praticien6->getIdPraticien())->setIdVisiteur($visiteur1->getId())->setPraticien($praticien6)->setVisiteur($visiteur1);
        $manager->persist($rep1);
        $manager->persist($rep2);
        $manager->persist($rep3);

        // PRESENTER
        $presenter1 = (new Presenter())->setVisiteur($visiteur1)->setVisiteurId($visiteur1->getId())->setRegion($region1)->setRegionId($region1->getId())->setDateAffect('2024-01-10');
        $presenter2 = (new Presenter())->setVisiteur($visiteur1)->setVisiteurId($visiteur1->getId())->setRegion($region2)->setRegionId($region2->getId())->setDateAffect('2024-02-01');
        $manager->persist($presenter1);
        $manager->persist($presenter2);

        // AC
        $ac1 = (new Ac())->setTheme('Conférence régionale')->setDate(new \DateTime('2024-01-20'))->setLieu('Paris');
        $ac2 = (new Ac())->setTheme('Atelier thérapeutique')->setDate(new \DateTime('2024-03-05'))->setLieu('Toulouse');
        $manager->persist($ac1);
        $manager->persist($ac2);

        $manager->flush(); // flush pour générer ids des AC

        // ORGANISER
        $org1 = (new Organiser())->setVisiteur($visiteur1)->setVisiteurId($visiteur1->getId())->setAc($ac1)->setAcId($ac1->getId());
        $org2 = (new Organiser())->setVisiteur($visiteur1)->setVisiteurId($visiteur1->getId())->setAc($ac2)->setAcId($ac2->getId());
        $manager->persist($org1);
        $manager->persist($org2);

        // PARTICIPER
        $part1 = (new Participer())->setNumeroSequentiel($praticien1->getNumeroSequentiel())->setIdPraticien($praticien1->getIdPraticien())->setIdAC($ac1->getId())->setPraticien($praticien1)->setAc($ac1);
        $part2 = (new Participer())->setNumeroSequentiel($praticien3->getNumeroSequentiel())->setIdPraticien($praticien3->getIdPraticien())->setIdAC($ac1->getId())->setPraticien($praticien3)->setAc($ac1);
        $part3 = (new Participer())->setNumeroSequentiel($praticien6->getNumeroSequentiel())->setIdPraticien($praticien6->getIdPraticien())->setIdAC($ac2->getId())->setPraticien($praticien6)->setAc($ac2);
        $manager->persist($part1);
        $manager->persist($part2);
        $manager->persist($part3);

        // TRAVAILLER
        $travail1 = (new Travailler())->setNumeroSequentiel($praticien1->getNumeroSequentiel())->setIdPraticien($praticien1->getIdPraticien())->setNumRegion($region1->getId())->setDateA('2024-01-15')->setPraticien($praticien1)->setRegion($region1);
        $travail2 = (new Travailler())->setNumeroSequentiel($praticien2->getNumeroSequentiel())->setIdPraticien($praticien2->getIdPraticien())->setNumRegion($region2->getId())->setDateA('2024-05-15')->setPraticien($praticien2)->setRegion($region2);
        $travail3 = (new Travailler())->setNumeroSequentiel($praticien3->getNumeroSequentiel())->setIdPraticien($praticien3->getIdPraticien())->setNumRegion($region2->getId())->setDateA('2024-03-12')->setPraticien($praticien3)->setRegion($region2);
        $travail4 = (new Travailler())->setNumeroSequentiel($praticien4->getNumeroSequentiel())->setIdPraticien($praticien4->getIdPraticien())->setNumRegion($region2->getId())->setDateA('2024-06-04')->setPraticien($praticien4)->setRegion($region2);
        $travail5 = (new Travailler())->setNumeroSequentiel($praticien5->getNumeroSequentiel())->setIdPraticien($praticien5->getIdPraticien())->setNumRegion($region3->getId())->setDateA('2024-04-09')->setPraticien($praticien5)->setRegion($region3);
        $travail6 = (new Travailler())->setNumeroSequentiel($praticien6->getNumeroSequentiel())->setIdPraticien($praticien6->getIdPraticien())->setNumRegion($region3->getId())->setDateA('2024-04-20')->setPraticien($praticien6)->setRegion($region3);
        $manager->persist($travail1);
        $manager->persist($travail2);
        $manager->persist($travail3);
        $manager->persist($travail4);
        $manager->persist($travail5);
        $manager->persist($travail6);

        // Flush final
        $manager->flush();
    }
}