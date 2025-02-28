<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use DateTimeImmutable;
use Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /** @throws Exception */
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $user = new User();
                ->setFirstName("FirstName $i")
                ->setLastName("LastName $i")
                ->setGuestNumber(random_int(0, 10))
                ->setEmail("email.$i@studi.fr")
                ->setCreatedAt(new DateTimeImmutable());

            // Correction : Suppression de "plainPassword:"
            $user->setPassword($this->passwordHasher->hashPassword($user, "password$i"));

            $manager->persist($user);
        }

        $manager->flush();
    }
}
