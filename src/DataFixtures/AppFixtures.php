<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker=Factory::create('fr-FR');
        //Nous gerons les utilisateurs
        $users=[];
        $genders = ['men', 'women'];
        for($i = 1 ; $i<=10;$i++){
            $user= new User();
            $gender = $faker->randomElement($genders);
            $picture= 'https://randomuser.me/api/portraits/';
            $pictureId=$faker->numberBetween(1,99).'.jpg';
            $picture .= "$gender/".$pictureId;
            $hash = $this->encoder->encodePassword($user,'password');
            $user->setFirstName($faker->firstName($gender))
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>'.join( '<p></p>',$faker->paragraphs(5)).'</p>')
                ->setHash($hash)
                ->setPicture($picture);
            $manager->persist($user);
            $users[]=$user;
        }
        //Nous gerons les annonces
        for($i = 1; $i <=30 ;$i++) {


            $ad = new Ad();
            $user= $users[mt_rand(0, count($users)-1)];
            $title=$faker->sentence();
            $coverImage=$faker->imageUrl(1000,350);
            $introduction=$faker->paragraph(2);
            $content='<p>'.join( '<p></p>',$faker->paragraphs(5)).'</p>';
            $ad->setTitle($title)

                ->setContent($content)
                ->setRooms(mt_rand(1, 5))
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setPrice(mt_rand(40, 200))
                ->setAuthor($user);
            for($j=1;$j <= mt_rand(2,5);$j++){
                $image=new Image();
                $image->setUrl($faker->imageUrl())
                    ->setCaption(($faker->sentence))
                    ->setAd($ad);
                $manager->persist(($image));
            }
            $manager->persist($ad);
        }
        $manager->flush();
    }
}
