<?php
namespace ff\PlatformBundle\Controller;

use ff\PlatformBundle\Entity\Advert;
use ff\PlatformBundle\Entity\AdvertSkill;
use ff\PlatformBundle\Entity\Application;
use ff\PlatformBundle\Entity\Category;
use ff\PlatformBundle\Entity\Image;
use ff\PlatformBundle\Entity\Skill;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AdvertController extends Controller
{

    public function indexAction($page)
    {
        return $this->render('ffPlatformBundle:Advert:index.html.twig', [
            'listAdverts' => [
                [
                    'title' => 'Recherche dérenderpppeur Symfony',
                    'id' => 1,
                    'author' => 'Alexandre',
                    'content' => 'Nous recherchons un dérenderppeur Symfony débutant sur Lyon. Blabla... ',
                    'date' => new \Datetime()
                ],
                [
                    'title' => 'Mission de webmaster',
                    'id' => 2,
                    'author' => 'Hugo',
                    'content' => 'Nous somme à la recherche d\'un webmaster débutant sur Paris. Blabla... ',
                    'date' => new \Datetime()
                ],
                [
                    'title' => 'Offre de stage webdesigner',
                    'id' => 3,
                    'author' => 'Mathieu',
                    'content' => 'Nous proposons un poste pour webdesigner. Blabla...',
                    'date' => new \Datetime()
                ]
            ]
        ]);
    }


    public function viewAction($id, Request $request)
    {

        // On récupère le repository
        $em = $this->getDoctrine()->getManager();


        // On récupère l'entité correspondant à l'id
        $advert = $em->getRepository(Advert::class)->find($id);


        // Si l'entité n'existe
        if ($advert === null) {
            // On renvoi une erreur
            throw new Exception("La page pour l'id : $id n'existe pas");
        }

        // On récupère les annonce de l'advert
        $listApplications = $em->getRepository(Application::class)->findBy(['advert' => $advert]);

        // On récupère les skll de l'annonce
        $listAdvertSkills = $em->getRepository(AdvertSkill::class)->findBy(['advert' => $advert]);
        // Sinon on return la vue
        return $this->render('ffPlatformBundle:Advert:view.html.twig', [
            'advert' => $advert,
            'listApplications' => $listApplications,
            'listAdvertSkills' => $listAdvertSkills
        ]);
    }


    public function viewSlugAction($year, $slug, $format)
    {
        return new Response("On passe ici un slug $slug : , une année : $year et un format: $format");
    }


    public function addAction(Request $request)
    {
        // Création de l'entité Advert
        $advert = new Advert();
        $advert->setTitle('Recherche développeur Symfony');
        $advert->setAuthor('Alexandre');
        $advert->setContent('Nous recherchons un dev Symfony sur Londre ...');

        // Création de l'entité Image
        $image = new Image();
        $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
        $image->setAlt('Job de rêve');

        // On lie l'image à l'annonce
        $advert->setImage($image);

        // Création de deux candidatures
        $application = new Application();
        $application->setAuthor('Marine');
        $application->setContent("J'ai toute les qualités requises");

        $application2 = new Application();
        $application2->setAuthor('Paul');
        $application2->setContent("Je pense être le bon candidat");

        // On lie les candidatures à l'annonce
        $application->setAdvert($advert);
        $application2->setAdvert($advert);

        $em = $this->getDoctrine()->getManager();
        // On récupère tous les skill
        $listSkills = $em->getRepository(Skill::class)->findAll();

        foreach ($listSkills as $skill) {
            $advertSkill = new AdvertSkill();
            $advertSkill->setAdvert($advert);
            $advertSkill->setSkill($skill);
            $advertSkill->setLevel('Expert');
            $em->persist($advertSkill);
        }


        // On récupère le gestionnaire d'entity (Entity_manager)

        $em->persist($advert);
        // On précise que l'on veut persiter l'objet
        $em->persist($application);
        $em->persist($application2);

        // On demande la mise à jours effectif
        $em->flush();

        // On affiche un message si l'objet est bien persisté
        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashBag->add('notice', 'Annonce bien enregistré');

            // Redirection vers la page de l'annonce
            return $this->redirectToRoute('ff_platform_view', ['id' => $advert->getId()]);
        }

        // Si ce n'est pas un POST on affiche le formulaire
        return $this->render('ffPlatformBundle:Advert:add.html.twig');


    }


    public function editAction($id, Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        // On récupère l'annonce de l'id $id
        $advert = $em->getRepository(Advert::class)->find($id);
        if (null === $advert) {
            throw new Exception("L'annonce à l'id : $id n'existe pas");
        }

        // On récupère toute les categories
        $listCategories = $em->getRepository(Category::class)->findAll();

        // On affecte les categories à l'annonce
        foreach ($listCategories as $category) {
            $advert->addCategory($category);
        }

        // On persiste dans la BDD (utilise uniquement sur le propriétaire)
        $em->flush();
    }


    public function deleteAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // On récupère l'annonce de l'id $id
        $advert = $em->getRepository(Advert::class)->find($id);

        if ($advert == null) {
            throw new Exception('La page n existe pas');
        }

        // On boucle sur les categories de l'annonce pour les supprimer
        foreach ($advert->getCategories() as $category) {
            $advert->removeCategory($category);
        }

        $em->flush();
    }

    public function menuAction($limit)
    {
        $listAdverts = array(
            array('id' => 2, 'title' => 'Recherche dérenderppeur Symfony'),
            array('id' => 5, 'title' => 'Mission de webmaster'),
            array('id' => 9, 'title' => 'Offre de stage webdesigner')
        );
        return $this->render('ffPlatformBundle:Advert:menu.html.twig', [
            'listAdverts' => $listAdverts
        ]);
    }

/*    public function editImageAction ($advertId) {
        $em = $this->getDoctrine()->getManager();

        // On récupère l'annonce
        $advert = $em->getRepository(Advert::class)->find($advertId);

        // On modifie l'alt de l'image sélectionné
        $advert->getImage()->setUrl('test.png');

        // On le persiste dans la BDD
        $em->flush();
        return new Response('ok');
    }*/
}
