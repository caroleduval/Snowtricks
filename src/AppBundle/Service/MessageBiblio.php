<?php

namespace AppBundle\Service;

use AppBundle\Entity\Trick;

class MessageBiblio
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
            $messBiblioP = $nbPhotos . " photos";
        }

        if ($nbVideos == 0) {
            $messBiblioV = "";
        } elseif ($nbVideos == 1) {
            $messBiblioV = "1 video";
        } else {
            $messBiblioV = $nbVideos . " videos";
        }

        if ($nbPhotos == 0 || $nbVideos == 0) {
            $and = "";
        } else {
            $and = " et ";
        }

        if ($nb == 0){
            $message="Pas encore de contenu multimédia pour ce trick.";
        } else {
            $message = $messBiblioP . $and . $messBiblioV.".";
        }
        return $message;
    }
}
