<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Image;
use App\Form\AdType;
use App\Repository\AdRepository;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo)
    {

        $ads= $repo->findAll();
        return $this->render('ad/index.html.twig', [
            'ads'=>$ads
        ]);
    }

    /**
     * @Route("/ads/new", name="ads_create")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request, EntityManagerInterface $manager){
        $ad= new Ad();

        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            foreach ( $ad->getImages()as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }
            $manager->persist($ad);
            $manager->flush();
            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée"
            );
            return $this->redirectToRoute('ads_show',[
                "slug"=>$ad->getSlug()
            ]);
        }
        return $this->render('ad/new.html.twig',[
            'form'=>$form->createView()
        ]);

    }
    /**
     * Permet d'afficher le formulaire d'édition
     * @Route("/ads/{slug}/edit", name="ads_edit")
     * @return Response
     **/
    public function edit(Request $request, Ad $ad,EntityManagerInterface $manager){
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            foreach ( $ad->getImages()as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }
            $manager->persist($ad);
            $manager->flush();
            $this->addFlash(
                'success',
                "Les modification de l'annonce : <strong>{$ad->getTitle()}</strong> ont bien été enregistrées"
            );
            return $this->redirectToRoute('ads_show',[
                "slug"=>$ad->getSlug()
            ]);
        }

        return $this->render('ad/edit.html.twig',[
            'form'=> $form->createView(),
            'ad'=> $ad
        ]);

    }
    /**
     * @Route("/ads/{slug}",name="ads_show")
     * @return Response
     **/
    public function show(Ad $ad)
    {
        //ici je récup l'annonce par le slug
        //$ad= $repo->findOneBySlug($slug);
        return $this->render('ad/show.html.twig',[
            'ad'=> $ad
        ]);
    }
}

