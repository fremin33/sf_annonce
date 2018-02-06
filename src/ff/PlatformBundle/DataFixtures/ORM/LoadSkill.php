<?php

namespace ff\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ff\PlatformBundle\Entity\Skill;

class LoadSkill implements FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        // Liste des skills à ajouter
        $names = ['PHP', 'Symfony', 'Ruby', 'HTML', 'CSS', 'Photoshop'];
        foreach ($names as $name) {
            // On crée la compétence
            $skill = new Skill();
            $skill->setName($name);
            $manager->persist($skill);
        }
        // On le persist dans la BDD
        $manager->flush();
    }
}