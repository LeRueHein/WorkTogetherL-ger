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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{


    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface  $hasher)
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
        for ($i = 1; $i <= 10; $i++) {

            $user = new User();
            $user->setFirstName('UserFirstName'.$i);
            $user->setLastName('UserLastName'.$i);
            $user->setEmail('user'.$i.'@example.com');
            // Encodage simple du mot de passe
            $password= $this->hasher->hashPassword($user, 'password');
            $user->setPassword($password);
            // Génération de dates de naissance aléatoires pour les utilisateurs
            $birthday = new \DateTime();
            $birthday->setTimestamp(mt_rand(strtotime('1980-01-01'), strtotime('2000-12-31')));
            $user->setBirthday($birthday);

            $manager->persist($user);
            $manager->flush();
        }
        // Création des baies dans une boucle
        for ($i = 1; $i <= 10; $i++) {

            $rack = new Rack();
            $rack->setNumberSlot(rand(1, 42));

            $manager->persist($rack);
            $manager->flush();

        }
        // Création des types d'unités dans une boucle
        for ($i = 1; $i <= 10; $i++) {

            $typeunit = new TypeUnit();
            $typeunit->setName('UnitName'.$i);

            $manager->persist($typeunit);
            $manager->flush();

        }
        // Création des types de réservation dans une boucle
        for ($i = 1; $i <= 10; $i++) {

            $typereservation = new TypeReservation();
            $typereservation->setName('ReservationName'.$i);
            $typereservation->setPercentage((rand(1, 100)));
            $manager->persist($typereservation);
            $manager->flush();

        }
        // récupérations de packs, type de réservations et des customers
        $user = $manager->getRepository(User::class)->findAll();
        $typereservation = $manager->getRepository(TypeReservation::class)->findAll();
        $pack = $manager->getRepository(Pack::class)->findAll();
        // Création des réservations dans une boucle
        for ($i = 1; $i <= 10; $i++) {

            $reservation = new Reservation();
            $reservation->setCode('ReservationCode'.$i.'256');
            $startDate = new \DateTime();
            $startDate->setTimestamp(mt_rand(strtotime('1980-01-01'), strtotime('1999-12-31')));
            $reservation->setStartDate($startDate);
            $startEnd = new \DateTime();
            $startEnd->setTimestamp(mt_rand(strtotime('2000-12-31'), strtotime('2020-01-01'), ));
            $reservation->setEndDate($startEnd);
            $reservation->setPrice(rand(1000, 100000));
            $reservation->setPercentage((rand(1, 100)));
            $reservation->setTypeReservation($typereservation[random_int(0, count($typereservation)-1)]);
            $reservation->setUser($user[random_int(0, count($user)-1)]);
            $reservation->setPack($pack[random_int(0, count($pack)-1)]);
            $manager->persist($reservation);
            $manager->flush();

        }
        $rack = $manager->getRepository(Rack::class)->findAll();
        $reservation = $manager->getRepository(Reservation::class)->findAll();
        $typeunit = $manager->getRepository(TypeUnit::class)->findAll();
        for ($i = 1; $i <= 10; $i++) {
            $unit = new Unit();
            $unit->setLocationSlot(rand(1, 42));
            $unit->setTypeUnit($typeunit[random_int(0, count($typeunit)-1)]);
            $unit->setRack($rack[random_int(0, count($rack)-1)]);
            $unit->setReservation($reservation[random_int(0, count($reservation)-1)]);

            $manager->persist($unit);
            $manager->flush();


        }
    }
}
