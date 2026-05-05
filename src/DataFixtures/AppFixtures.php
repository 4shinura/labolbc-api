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
        $region1 = (new Region())->setLibelle('Île-de-France');
        $region2 = (new Region())->setLibelle('Occitanie');
        $region3 = (new Region())->setLibelle('Nouvelle-Aquitaine');
        $manager->persist($region1);
        $manager->persist($region2);
        $manager->persist($region3);

        // SPECIALITES
        $specialite1 = (new Specialite())->setNumeroSequentiel(13)->setLibelle('Généraliste');
        $specialite2 = (new Specialite())->setNumeroSequentiel(14)->setLibelle('Cardiologue');
        $specialite3 = (new Specialite())->setNumeroSequentiel(15)->setLibelle('Dermatologue');
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

        // VISITEUR
        $visiteur1 = (new Visiteur())->setNom('VamichLab')->setProfil($profil2);
        $manager->persist($visiteur1);

        // PRATICIENS
        $praticien1 = (new Praticien())
            ->setNom('Durand')->setPrenom('Michel')->setSpecialite($specialite1);
        $praticien2 = (new Praticien())
            ->setNom('Robert')->setPrenom('Axel')->setSpecialite($specialite1);
        $praticien3 = (new Praticien())
            ->setNom('Bernard')->setPrenom('Anna')->setSpecialite($specialite2);
        $praticien4 = (new Praticien())
            ->setNom('Pidoux')->setPrenom('Gaëlle')->setSpecialite($specialite2);
        $praticien5 = (new Praticien())
            ->setNom('Morel')->setPrenom('Julie')->setSpecialite($specialite3);
        $praticien6 = (new Praticien())
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

        // Premier flush pour avoir les IDs
        $manager->flush();

        // VISITES
        $visite1 = (new Visite())
            ->setDate(new \DateTime('2025-11-22'))
            ->setMotif('Présentation du Doliprane brûlé')
            ->setBilan('Préfère manger des têtes brûlées :(')
            ->setVisiteur($visiteur1)
            ->setPraticien($praticien2);
        $visite2 = (new Visite())
            ->setDate(new \DateTime('2025-11-22'))
            ->setMotif('Présentation du Codoliprane')
            ->setBilan('Inutile, ça fait aucun effet !!!')
            ->setVisiteur($visiteur1)
            ->setPraticien($praticien4);
        $visite3 = (new Visite())
            ->setDate(new \DateTime('2025-11-22'))
            ->setMotif('Présentation de la Soupline')
            ->setBilan('Elle adore mais aucun effet thérapeutique :(')
            ->setVisiteur($visiteur1)
            ->setPraticien($praticien6);
        $manager->persist($visite1);
        $manager->persist($visite2);
        $manager->persist($visite3);

        // PROPOSER
        $proposer1 = (new Proposer())->setVisite($visite1)->setMedicament($medicament1)->setQuantite(5);
        $proposer2 = (new Proposer())->setVisite($visite2)->setMedicament($medicament1)->setQuantite(43);
        $proposer3 = (new Proposer())->setVisite($visite3)->setMedicament($medicament1)->setQuantite(1);
        $manager->persist($proposer1);
        $manager->persist($proposer2);
        $manager->persist($proposer3);

        // REPERTORIER
        $rep1 = (new Repertorier())->setPraticien($praticien2)->setVisiteur($visiteur1);
        $rep2 = (new Repertorier())->setPraticien($praticien4)->setVisiteur($visiteur1);
        $rep3 = (new Repertorier())->setPraticien($praticien6)->setVisiteur($visiteur1);
        $manager->persist($rep1);
        $manager->persist($rep2);
        $manager->persist($rep3);

        // PRESENTER
        $presenter1 = (new Presenter())
            ->setVisiteur($visiteur1)
            ->setVisiteurId($visiteur1->getId())
            ->setRegion($region1)
            ->setRegionId($region1->getId())
            ->setDateAffect('2024-01-10');
        $presenter2 = (new Presenter())
            ->setVisiteur($visiteur1)
            ->setVisiteurId($visiteur1->getId())
            ->setRegion($region2)
            ->setRegionId($region2->getId())
            ->setDateAffect('2024-02-01');
        $manager->persist($presenter1);
        $manager->persist($presenter2);

        // AC
        $ac1 = (new Ac())->setTheme('Conférence régionale')->setDate(new \DateTime('2024-01-20'))->setLieu('Paris');
        $ac2 = (new Ac())->setTheme('Atelier thérapeutique')->setDate(new \DateTime('2024-03-05'))->setLieu('Toulouse');
        $manager->persist($ac1);
        $manager->persist($ac2);

        $manager->flush(); // flush pour générer ids des AC

        // ORGANISER
        $org1 = (new Organiser())
            ->setVisiteur($visiteur1)
            ->setVisiteurId($visiteur1->getId())
            ->setAc($ac1)
            ->setAcId($ac1->getId());
        $org2 = (new Organiser())
            ->setVisiteur($visiteur1)
            ->setVisiteurId($visiteur1->getId())
            ->setAc($ac2)
            ->setAcId($ac2->getId());
        $manager->persist($org1);
        $manager->persist($org2);

        // PARTICIPER
        $part1 = (new Participer())->setPraticien($praticien1)->setAc($ac1);
        $part2 = (new Participer())->setPraticien($praticien3)->setAc($ac1);
        $part3 = (new Participer())->setPraticien($praticien6)->setAc($ac2);
        $manager->persist($part1);
        $manager->persist($part2);
        $manager->persist($part3);

        // TRAVAILLER
        $travail1 = (new Travailler())->setPraticien($praticien1)->setRegion($region1)->setDateA('2024-01-15');
        $travail2 = (new Travailler())->setPraticien($praticien2)->setRegion($region2)->setDateA('2024-05-15');
        $travail3 = (new Travailler())->setPraticien($praticien3)->setRegion($region2)->setDateA('2024-03-12');
        $travail4 = (new Travailler())->setPraticien($praticien4)->setRegion($region2)->setDateA('2024-06-04');
        $travail5 = (new Travailler())->setPraticien($praticien5)->setRegion($region3)->setDateA('2024-04-09');
        $travail6 = (new Travailler())->setPraticien($praticien6)->setRegion($region3)->setDateA('2024-04-20');
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