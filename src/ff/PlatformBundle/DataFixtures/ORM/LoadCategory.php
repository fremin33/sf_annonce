<?php

namespace ff\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ff\PlatformBundle\Entity\Category;

class LoadCategory implements FixtureInterface
{
    // Dans l'argument de la méthode load, manager est le gestionnaire d'entité
    public function load(ObjectManager $manager)
    {
        // Liste des noms de categories à ajouter
        $names = [
            'Développement web',
            'Développement mobile',
            'Graphisme',
            'Intégration',
            'Réseau'
        ];




        foreach ($names as $name) {
            // On crée la catégory
            $category = new Category();
            $category->setName($name);
            // On la fait persister
            $manager->persist($category);
        }
        // On déclenche l'enregistrement de toutes les categories
        $manager->flush();
    }
}