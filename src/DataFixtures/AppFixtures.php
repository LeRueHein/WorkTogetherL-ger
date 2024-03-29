<?php

namespace App\DataFixtures;

use App\Entity\Pack;
use App\Entity\Rack;
use App\Entity\Reservation;
use App\Entity\TypeReservation;
use App\Entity\TypeUnit;
use App\Entity\Unit;
use App\Entity\User;
use App\Repository\TypeReservationRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use phpDocumentor\Reflection\Types\True_;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{


    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $connection = $manager->getConnection();
        $connection->executeStatement("DBCC CHECKIDENT ('pack', RESEED, 0);");
        $connection->executeStatement("DBCC CHECKIDENT ('user', RESEED, 0);");
        $connection->executeStatement("DBCC CHECKIDENT ('rack', RESEED, 0);");
        $connection->executeStatement("DBCC CHECKIDENT ('type_reservation', RESEED, 0);");
        $connection->executeStatement("DBCC CHECKIDENT ('type_unit', RESEED, 0);");
        $connection->executeStatement("DBCC CHECKIDENT ('unit', RESEED, 0);");
        $connection->executeStatement("DBCC CHECKIDENT ('reservation', RESEED, 0);");

        // Création des packs dans une boucle
        $pack = new Pack();
        $pack->setName('Base');
        $pack->setPrice(100); // Prix aléatoire entre 1000 et 100000
        $pack->setNumberSlot(1); // Nombre de slot aléatoire entre 1 et 42
        $manager->persist($pack);
        $manager->flush();

        $pack = new Pack();
        $pack->setName('Start-up');
        $pack->setPrice(900); // Prix aléatoire entre 1000 et 100000
        $pack->setNumberSlot(10); // Nombre de slot aléatoire entre 1 et 42
        $manager->persist($pack);
        $manager->flush();

        $pack = new Pack();
        $pack->setName('PME');
        $pack->setPrice(1680); // Prix aléatoire entre 1000 et 100000
        $pack->setNumberSlot(21); // Nombre de slot aléatoire entre 1 et 42
        $manager->persist($pack);
        $manager->flush();

        $pack = new Pack();
        $pack->setName('Entreprise');
        $pack->setPrice(2940); // Prix aléatoire entre 1000 et 100000
        $pack->setNumberSlot(42); // Nombre de slot aléatoire entre 1 et 42
        $manager->persist($pack);
        $manager->flush();


        // Création d'utilisateurs dans une boucle
        for ($i = 1; $i <= 5; $i++) {

            $user = new User();
            $user->setFirstName('UserFirstName' . $i);
            $user->setLastName('UserLastName' . $i);
            $user->setEmail('user' . $i . '@example.com');
            // Encodage simple du mot de passe
            $password = $this->hasher->hashPassword($user, 'password');
            $user->setPassword($password);
            // Génération de dates de naissance aléatoires pour les utilisateurs
            $birthday = new \DateTime();
            $birthday->setTimestamp(mt_rand(strtotime('1980-01-01'), strtotime('2000-12-31')));
            $user->setBirthday($birthday);

            $manager->persist($user);
            $manager->flush();
        }
        // Création des baies dans une boucle
        for ($i = 1; $i <= 30; $i++) {

            $rack = new Rack();
            $rack->setNumberSlot(42);

            $manager->persist($rack);
            $manager->flush();

        }


        // Création des types d'unités dans une boucle

        $typeunit = new TypeUnit();
        $typeunit->setName('Stockage');

        $manager->persist($typeunit);
        $manager->flush();

        $typeunit = new TypeUnit();
        $typeunit->setName('Calcul');

        $manager->persist($typeunit);
        $manager->flush();

        $typeunit = new TypeUnit();
        $typeunit->setName('CPU');

        $manager->persist($typeunit);
        $manager->flush();

        //creation unit
        $racks = $manager->getRepository(Rack::class)->findAll();
        $typeunit = $manager->getRepository(TypeUnit::class)->findAll();
        foreach ($racks as $rack) {
            for ($i = 1; $i <= $rack->getNumberSlot(); $i++) {
                $unit = new Unit();
                $unit->setLocationSlot($i);
                $unit->setTypeUnit($typeunit[random_int(0, count($typeunit) - 1)]);
                $unit->setRack($rack);
                $unit->setState(false);
                $unit->setReservation(null);
                $manager->persist($unit);
            }


            // Création des types de réservation dans une boucle


        }
        $typereservation = new TypeReservation();
        $typereservation->setName('Mensuel');
        $typereservation->setPercentage(0);
        $manager->persist($typereservation);
        $manager->flush();

        $typereservation = new TypeReservation();
        $typereservation->setName('Annuel');
        $typereservation->setPercentage(20);
        $manager->persist($typereservation);
        $manager->flush();


        // récupérations de packs, type de réservations et des customers
        $user = $manager->getRepository(User::class)->findAll();
        $typereservation = $manager->getRepository(TypeReservation::class)->findAll();
        $pack = $manager->getRepository(Pack::class)->findAll();
        // Création des réservations dans une boucle
        for ($i = 1; $i <= 10; $i++) {

            $reservation = new Reservation();
            $reservation->setCode('ReservationCode' . $i . '256');
            $startDate = new \DateTime();
            $startDate->setTimestamp(mt_rand(strtotime('1980-01-01'), strtotime('1999-12-31')));
            $reservation->setStartDate($startDate);
            $startEnd = new \DateTime();
            $startEnd->setTimestamp(mt_rand(strtotime('2000-12-31'), strtotime('2020-01-01'),));
            $reservation->setEndDate($startEnd);

            $reservation->setTypeReservation($typereservation[random_int(0, count($typereservation) - 1)]);
            $reservation->setUser($user[random_int(0, count($user) - 1)]);
            $reservation->setPack($pack[random_int(0, count($pack) - 1)]);

            $reservation->setPrice($reservation->getPack()->getPrice());
            $reservation->setPercentage($reservation->getTypeReservation()->getPercentage());
            $manager->persist($reservation);
            $manager->flush();
            $units = $manager->getRepository(Unit::class)->findBy(array('reservation' => null));
            for($j = 0 ; $j < $reservation->getPack()->getNumberSlot(); $j++){
                $units[$j]->setReservation($reservation);
                $units[$j]->setState(true);
                $manager->flush();
            }
        }
    }
}
