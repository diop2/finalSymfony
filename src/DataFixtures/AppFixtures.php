<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder){
        $this->passwordEncoder = $passwordEncoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $user = new User();
        
        $user->setEmail('diop@gmail.com');
        $user->setRoles(["ADMIN_SYSTEME"]);
                
            
            $user->setPassword($this->passwordEncoder->encodePassword($user,'01234'));
            $user->setNomComplet('Ousmane DIOP');
            $user->setAdresse('Saint-Louis');
            $user->setNci ( "0204582");
            $user->setTel ( "773861858");
            $user->setAdresse('Saint-Louis');
            
            //$user->setEntreprise('1');

               
            
            $manager->persist($user);
            $manager->flush();
    }
}
