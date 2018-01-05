<?php
namespace ff\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller
{

    public function indexAction($page)
    {
        return $this->render('ffPlatformBundle:Advert:index.html.twig', [
            'listAdverts' => [
                [
                    'title' => 'Recherche développpeur Symfony',
                    'id' => 1,
                    'author' => 'Alexandre',
                    'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla... ',
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
        return $this->render('ffPlatformBundle:Advert:view.html.twig', [
            'advert' =>
                [
                    'title' => 'Recherche développpeur Symfony',
                    'id' => 1,
                    'author' => 'Alexandre',
                    'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla... ',
                    'date' => new \Datetime()
                ]
        ]);
    }


    public function viewSlugAction($year, $slug, $format)
    {
        return new Response("On passe ici un slug $slug : , une année : $year et un format: $format");
    }


    public function addAction(Request $request)
    {
        // Si l'user soumet le formulaire
        if ($request->isMethod('POST')) {
            // On enregistre le message Flash pour valider l'enregistrement
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistré');
            // On redirige l'user vers la visualisation de son anonce 
            return $this->redirectToRoute('ff_platform_view', ['id' => 5]);
        }
        return $this->render('ffPlatformBundle:Advert:add.html.twig', []);
    }


    public function editAction($id, Request $request)
    {
      $advert = [
            'title' => 'Recherche développpeur Symfony',
            'id' => $id,
            'author' => 'Alexandre',
            'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla... ',
            'date' => new \Datetime()
      ];
      return $this->render('ffPlatformBundle:Advert:edit.html.twig', ['advert' => $advert]);
    }


    public function deleteAction($id, Request $request)
    {
        return $this->render('ffPlatformBundle:Advert:delete.html.twig', []);
    }

    public function menuAction($limit)
    {
        $listAdverts = array(
            array('id' => 2, 'title' => 'Recherche développeur Symfony'),
            array('id' => 5, 'title' => 'Mission de webmaster'),
            array('id' => 9, 'title' => 'Offre de stage webdesigner')
        );
        return $this->render('ffPlatformBundle:Advert:menu.html.twig', [
            'listAdverts' => $listAdverts
        ]);
    }

}
