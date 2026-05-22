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
        $region1 = (new Region())->setNumRegion(1)->setLibelle('Île-de-France');
        $region2 = (new Region())->setNumRegion(2)->setLibelle('Occitanie');
        $region3 = (new Region())->setNumRegion(3)->setLibelle('Nouvelle-Aquitaine');
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
            ->setPassword('admin')
            ->setTypeProfil('admin');
        $profil2 = (new Profil())
            ->setEmail('visiteur1@example.com')
            ->setPassword('visiteur')
            ->setTypeProfil('visiteur');
        $profil3 = (new Profil())
            ->setEmail('responsable1@example.com')
            ->setPassword('responsable')
            ->setTypeProfil('responsable');
        $manager->persist($profil1);
        $manager->persist($profil2);
        $manager->persist($profil3);

        $manager->flush(); // Flush pour générer les IDs des profils

        // VISITEUR
        $visiteur1 = (new Visiteur())->setNomVisiteur('VamichLab')->setProfil($profil2);
        $manager->persist($visiteur1);

        // PRATICIENS
        $praticien1 = (new Praticien())
            ->setNumeroSequentiel($specialite1->getNumeroSequentiel())->setIdPraticien(1)
            ->setNomPraticien('Durand')->setPrenomPraticien('Michel')->setSpecialite($specialite1);
        $praticien2 = (new Praticien())
            ->setNumeroSequentiel($specialite1->getNumeroSequentiel())->setIdPraticien(2)
            ->setNomPraticien('Robert')->setPrenomPraticien('Axel')->setSpecialite($specialite1);
        $praticien3 = (new Praticien())
            ->setNumeroSequentiel($specialite2->getNumeroSequentiel())->setIdPraticien(1)
            ->setNomPraticien('Bernard')->setPrenomPraticien('Anna')->setSpecialite($specialite2);
        $praticien4 = (new Praticien())
            ->setNumeroSequentiel($specialite2->getNumeroSequentiel())->setIdPraticien(2)
            ->setNomPraticien('Pidoux')->setPrenomPraticien('Gaëlle')->setSpecialite($specialite2);
        $praticien5 = (new Praticien())
            ->setNumeroSequentiel($specialite3->getNumeroSequentiel())->setIdPraticien(1)
            ->setNomPraticien('Morel')->setPrenomPraticien('Julie')->setSpecialite($specialite3);
        $praticien6 = (new Praticien())
            ->setNumeroSequentiel($specialite3->getNumeroSequentiel())->setIdPraticien(2)
            ->setNomPraticien('Bakanova')->setPrenomPraticien('Zargan')->setSpecialite($specialite3);
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
            ->setDateVisite(new \DateTime('2025-11-22'))
            ->setMotifVisite('Présentation du Doliprane brûlé')
            ->setBilanVisite('Préfère manger des têtes brûlées :(')
            ->setIdVisiteur($visiteur1->getIdVisiteur())
            ->setNumeroSequentiel($praticien2->getNumeroSequentiel())
            ->setIdPraticien($praticien2->getIdPraticien())
            ->setVisiteur($visiteur1)
            ->setPraticien($praticien2);
        $visite2 = (new Visite())
            ->setDateVisite(new \DateTime('2025-11-22'))
            ->setMotifVisite('Présentation du Codoliprane')
            ->setBilanVisite('Inutile, ça fait aucun effet !!!')
            ->setIdVisiteur($visiteur1->getIdVisiteur())
            ->setNumeroSequentiel($praticien4->getNumeroSequentiel())
            ->setIdPraticien($praticien4->getIdPraticien())
            ->setVisiteur($visiteur1)
            ->setPraticien($praticien4);
        $visite3 = (new Visite())
            ->setDateVisite(new \DateTime('2025-11-22'))
            ->setMotifVisite('Présentation de la Soupline')
            ->setBilanVisite('Elle adore mais aucun effet thérapeutique :(')
            ->setIdVisiteur($visiteur1->getIdVisiteur())
            ->setNumeroSequentiel($praticien6->getNumeroSequentiel())
            ->setIdPraticien($praticien6->getIdPraticien())
            ->setVisiteur($visiteur1)
            ->setPraticien($praticien6);
        $manager->persist($visite1);
        $manager->persist($visite2);
        $manager->persist($visite3);

        $manager->flush(); // Flush pour générer les IDs des visites

        // PROPOSER
        $proposer1 = (new Proposer())->setIdVisite($visite1->getIdVisite())->setIdMedicament($medicament1->getIdMedicament())->setNbEchantillon(5)->setVisite($visite1)->setMedicament($medicament1);
        $proposer2 = (new Proposer())->setIdVisite($visite2->getIdVisite())->setIdMedicament($medicament1->getIdMedicament())->setNbEchantillon(43)->setVisite($visite2)->setMedicament($medicament1);
        $proposer3 = (new Proposer())->setIdVisite($visite3->getIdVisite())->setIdMedicament($medicament1->getIdMedicament())->setNbEchantillon(1)->setVisite($visite3)->setMedicament($medicament1);
        $manager->persist($proposer1);
        $manager->persist($proposer2);
        $manager->persist($proposer3);

        $manager->flush(); // Flush après proposer

        // REPERTORIER
        $rep1 = (new Repertorier())->setNumeroSequentiel($praticien2->getNumeroSequentiel())->setIdPraticien($praticien2->getIdPraticien())->setIdVisiteur($visiteur1->getIdVisiteur())->setPraticien($praticien2)->setVisiteur($visiteur1);
        $rep2 = (new Repertorier())->setNumeroSequentiel($praticien4->getNumeroSequentiel())->setIdPraticien($praticien4->getIdPraticien())->setIdVisiteur($visiteur1->getIdVisiteur())->setPraticien($praticien4)->setVisiteur($visiteur1);
        $rep3 = (new Repertorier())->setNumeroSequentiel($praticien6->getNumeroSequentiel())->setIdPraticien($praticien6->getIdPraticien())->setIdVisiteur($visiteur1->getIdVisiteur())->setPraticien($praticien6)->setVisiteur($visiteur1);
        $manager->persist($rep1);
        $manager->persist($rep2);
        $manager->persist($rep3);

        // PRESENTER
        $presenter1 = (new Presenter())->setVisiteur($visiteur1)->setIdVisiteur($visiteur1->getIdVisiteur())->setRegion($region1)->setNumRegion($region1->getNumRegion())->setDateAffect('2024-01-10');
        $presenter2 = (new Presenter())->setVisiteur($visiteur1)->setIdVisiteur($visiteur1->getIdVisiteur())->setRegion($region2)->setNumRegion($region2->getNumRegion())->setDateAffect('2024-02-01');
        $manager->persist($presenter1);
        $manager->persist($presenter2);

        // AC
        $ac1 = (new Ac())->setThemeAC('Conférence régionale')->setDateAC(new \DateTime('2024-01-20'))->setLieuAC('Paris');
        $ac2 = (new Ac())->setThemeAC('Atelier thérapeutique')->setDateAC(new \DateTime('2024-03-05'))->setLieuAC('Toulouse');
        $manager->persist($ac1);
        $manager->persist($ac2);

        $manager->flush(); // flush pour générer ids des AC

        // ORGANISER
        $org1 = (new Organiser())->setVisiteur($visiteur1)->setIdVisiteur($visiteur1->getIdVisiteur())->setAc($ac1)->setIdAC($ac1->getIdAC());
        $org2 = (new Organiser())->setVisiteur($visiteur1)->setIdVisiteur($visiteur1->getIdVisiteur())->setAc($ac2)->setIdAC($ac2->getIdAC());
        $manager->persist($org1);
        $manager->persist($org2);

        // PARTICIPER
        $part1 = (new Participer())->setNumeroSequentiel($praticien1->getNumeroSequentiel())->setIdPraticien($praticien1->getIdPraticien())->setIdAC($ac1->getIdAC())->setPraticien($praticien1)->setAc($ac1);
        $part2 = (new Participer())->setNumeroSequentiel($praticien3->getNumeroSequentiel())->setIdPraticien($praticien3->getIdPraticien())->setIdAC($ac1->getIdAC())->setPraticien($praticien3)->setAc($ac1);
        $part3 = (new Participer())->setNumeroSequentiel($praticien6->getNumeroSequentiel())->setIdPraticien($praticien6->getIdPraticien())->setIdAC($ac2->getIdAC())->setPraticien($praticien6)->setAc($ac2);
        $manager->persist($part1);
        $manager->persist($part2);
        $manager->persist($part3);

        // TRAVAILLER
        $travail1 = (new Travailler())->setNumeroSequentiel($praticien1->getNumeroSequentiel())->setIdPraticien($praticien1->getIdPraticien())->setNumRegion($region1->getNumRegion())->setDateA('2024-01-15')->setPraticien($praticien1)->setRegion($region1);
        $travail2 = (new Travailler())->setNumeroSequentiel($praticien2->getNumeroSequentiel())->setIdPraticien($praticien2->getIdPraticien())->setNumRegion($region2->getNumRegion())->setDateA('2024-05-15')->setPraticien($praticien2)->setRegion($region2);
        $travail3 = (new Travailler())->setNumeroSequentiel($praticien3->getNumeroSequentiel())->setIdPraticien($praticien3->getIdPraticien())->setNumRegion($region2->getNumRegion())->setDateA('2024-03-12')->setPraticien($praticien3)->setRegion($region2);
        $travail4 = (new Travailler())->setNumeroSequentiel($praticien4->getNumeroSequentiel())->setIdPraticien($praticien4->getIdPraticien())->setNumRegion($region2->getNumRegion())->setDateA('2024-06-04')->setPraticien($praticien4)->setRegion($region2);
        $travail5 = (new Travailler())->setNumeroSequentiel($praticien5->getNumeroSequentiel())->setIdPraticien($praticien5->getIdPraticien())->setNumRegion($region3->getNumRegion())->setDateA('2024-04-09')->setPraticien($praticien5)->setRegion($region3);
        $travail6 = (new Travailler())->setNumeroSequentiel($praticien6->getNumeroSequentiel())->setIdPraticien($praticien6->getIdPraticien())->setNumRegion($region3->getNumRegion())->setDateA('2024-04-20')->setPraticien($praticien6)->setRegion($region3);
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