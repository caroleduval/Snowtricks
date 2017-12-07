<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Category;
use AppBundle\Entity\Trick;
use AppBundle\Entity\Photo;
use AppBundle\Entity\Video;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadData extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        // Liste des catégories de trick
        $names = array(
            'Grab',
            'Rotation',
            'Flip',
            'One Foot Trick',
            'Rotation désaxée',
            'Slide'
        );
        foreach ($names as $name) {
            $category = new Category();
            $category->setName($name);
            $manager->persist($category);
        }

        // Liste des tricks
        $tricks = array(
            array(
                'name' => 'Mute',
                'category' => '1',
                'description' => 'Saisie de la carre frontside de la planche entre les deux pieds avec la main avant.'
            ),
            array(
                'name' => 'Sad',
                'category' => '1',
                'description' => 'Saisie de la carre backside de la planche, entre les deux pieds, avec la main avant.'
            ),
            array(
                'name' => 'Indy',
                'category' => '1',
                'description' => 'saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière.'
            ),
            array(
                'name' => '180',
                'category' => '2',
                'description' => 'Demi-tour, soit 180 degrés d\'angle.'
            ),
            array(
                'name' => 'Stalefish',
                'category' => '1',
                'description' => 'Saisie de la carre backside de la planche entre les deux pieds avec la main arrière.'
            ),
            array(
                'name' => '360',
                'category' => '2',
                'description' => 'Trois six pour un tour complet.'
            ),
            array(
                'name' => 'Front Flip',
                'category' => '3',
                'description' => 'Rotation verticale vers l\'avant.'
            ),
            array(
                'name' => 'Nose Slide',
                'category' => '6',
                'description' => 'Glissade sur l\'avant de la planche sur une barre de slide.'
            ),
            array(
                'name' => 'Seat belt',
                'category' => '1',
                'description' => 'Saisie du carre frontside à l\'arrière avec la main avant.'
            ),
            array(
                'name' => '1080 ou big foot',
                'category' => '1',
                'description' => 'Trois tours complet.'
            ),
        );

        foreach ($tricks as $row) {
            // On crée le trick
            $trick = new Trick();
            $trick->setName($row['name']);
            $trick->setCategory($row['category']);
            $em->getRepository('AppBundle:Category')->findOneBy(array('name' => $fixture['group_figure']));


            $trick->setDescription($row['description']);

            // On la persiste
            $manager->persist($trick);
        }

        // Liste des photos
        $photos = array(
            array('trick_id' => '1','type' => 'jpg','alt' => 'grab_mute_1'),
            array('trick_id' => '1','type' => 'jpg','alt' => 'grab_mute_2'),
            array('trick_id' => '2','type' => 'jpg','alt' => 'grab_sad_1'),
            array('trick_id' => '2','type' => 'jpg','alt' => 'grab_sad_2'),
            array('trick_id' => '3','type' => 'jpg','alt' => 'grab_indy_1'),
            array('trick_id' => '3','type' => 'jpg','alt' => 'grab_indy_2'),
            array('trick_id' => '4','type' => 'jpg','alt' => 'Cab_180_Piste_Cudlip_REP'),
            array('trick_id' => '5','type' => 'jpg','alt' => 'grab_stalefish_1'),
            array('trick_id' => '5','type' => 'jpg','alt' => 'grab_stalefish_2'),
            array('trick_id' => '6','type' => 'jpg','alt' => 'rotation_360'),
            array('trick_id' => '7','type' => 'jpg','alt' => 'front_flip'),
            array('trick_id' => '8','type' => 'jpg','alt' => 'KBS_niseko-cayley'),
            array('trick_id' => '9','type' => 'jpg','alt' => 'grab_seatbelt_1'),
            array('trick_id' => '9','type' => 'jpg','alt' => 'grab_seatbelt_2'),
        );

        foreach ($photos as $row) {
            // On crée la photo
            $photo = new Photo();
            $photo->setTrick($row['trick_id']);
            $photo->setType($row['type']);
            $photo->setAlt($row['alt']);

            // On la persiste
            $manager->persist($photo);
        }

        // Liste des vidéos
        $videos = array(
            array('trick_id' => '1','type' => 'youtube','identif' => '4sha5smEUHA'),
            array('trick_id' => '2','type' => 'youtube','identif' => 'KEdFwJ4SWq4'),
            array('trick_id' => '3','type' => 'dailymotion','identif' => 'x11jh6u'),
            array('trick_id' => '3','type' => 'dailymotion','identif' => 'x11jh6u_snowboard-indy-grab_sport'),
            array('trick_id' => '4','type' => 'vimeo','identif' => '7678393'),
            array('trick_id' => '6','type' => 'youtube','identif' => 'JJy39dO_PPE'),
            array('trick_id' => '7','type' => 'youtube','identif' => 'xhvqu2XBvI0'),
            array('trick_id' => '8','type' => 'youtube','identif' => 'czpV-FOBHY4'),
        );

        foreach ($videos as $row) {
            // On crée la photo
            $video = new Video();
            $video->setTrick($row['trick_id']);
            $video->setType($row['type']);
            $video->setIdentif($row['identif']);

            // On la persiste
            $manager->persist($video);
        }

        // On déclenche l'enregistrement
        $manager->flush();
    }
}