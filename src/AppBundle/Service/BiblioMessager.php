<?php

namespace AppBundle\Service;

use AppBundle\Entity\Trick;

class BiblioMessager
{
    public function messageCreator(Trick $trick)
    {
        $nbPhotos = count($trick->getPhotos());
        $nbVideos = count($trick->getVideos());
        $nb = $nbPhotos + $nbVideos;

        if ($nbPhotos == 0) {
            $messBiblioP = "";
        } elseif ($nbPhotos == 1) {
            $messBiblioP = "1 photo";
        } else {
            $messBiblioP = $nbPhotos." photos";
        }

        if ($nbVideos == 0) {
            $messBiblioV = "";
        } elseif ($nbVideos == 1) {
            $messBiblioV = "1 vidéo";
        } else {
            $messBiblioV = $nbVideos." vidéos";
        }

        if ($nbPhotos == 0 || $nbVideos == 0) {
            $and = "";
        } else {
            $and = " et ";
        }

        if ($nb == 0){
            $message="Pas encore de contenu multimédia pour ce trick.";
        } else {
            $message = "Ce trick est illustré par ".$messBiblioP.$and.$messBiblioV.".";
        }
        return $message;
    }
}
