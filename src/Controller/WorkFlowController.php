<?php

namespace App\Controller;

use LogicException;
use App\Entity\Products;
use App\Form\ProduitRequestType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\WorkflowInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WorkFlowController extends AbstractController
{
    public $envoiCommandeWorkflow;
    public function __construct(WorkflowInterface $envoiCommandeWorkflow)
    {
        $this->envoiCommandeWorkflow = $envoiCommandeWorkflow;
    }



    /**
     * @Route("/afficheProduit", name="afficheProduit")
     */
    public function afficheProduit()
    {

        $product = $this->getDoctrine()
            ->getRepository(Products::class)
            ->findAll();
        return $this->render('product/product.html.twig', [
            'product' => $product
        ]);
    }


    /**
     * @Route("/slect/{id}", name="slect")
     */
    public function index($id, Request $request, EntityManagerInterface $entityManager): Response
    {

        $product = $this->getDoctrine()
            ->getRepository(Products::class)
            ->find($id);
        if (isset($_POST['submit'])) {

            try {
                $this->envoiCommandeWorkflow->apply($product, 'pour_creation');
            } catch (LogicException $exception) {
                //
            }
            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirectToRoute('paiement', ['id' => $product->getId()]);
        }

        return $this->render('workflow/slect.html.twig', ['product' => $product]);
    }


    /**
     * @Route("/paiement/{id}", name="paiement")
     */
    public function paiement($id, Request $request, EntityManagerInterface $entityManager): Response
    {

        $product = $this->getDoctrine()
            ->getRepository(Products::class)
            ->find($id);
        if (isset($_POST['submit2'])) {

            try {
                $this->envoiCommandeWorkflow->apply($product, 'pour_paiement');
            } catch (LogicException $exception) {
                //
            }
            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirectToRoute('etat', ['id' => $product->getId()]);
        }

        return $this->render('workflow/paiement.html.twig', ['product' => $product]);
    }

    /**
     * @Route("/etat/{id}", name="etat")
     */
    public function etat($id, Request $request, EntityManagerInterface $entityManager): Response
    {

        $product = $this->getDoctrine()
            ->getRepository(Products::class)
            ->find($id);
        if (isset($_POST['submit3'])) {

            try {
                $this->envoiCommandeWorkflow->apply($product, 'pour_expedition');
            } catch (LogicException $exception) {
                //
            }
            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirectToRoute('livree', ['id' => $product->getId()]);
        }

        if (isset($_POST['submit4'])) {

            try {
                $this->envoiCommandeWorkflow->apply($product, 'to_annulee');
            } catch (LogicException $exception) {
                //
            }
            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirectToRoute('afficheProduit');
        }

        return $this->render('workflow/etat.html.twig', ['product' => $product]);
    }

    /**
     * @Route("/livree/{id}", name="livree")
     */
    public function livree($id, Request $request, EntityManagerInterface $entityManager): Response
    {

        $product = $this->getDoctrine()
            ->getRepository(Products::class)
            ->find($id);
        if (isset($_POST['submit5'])) {

            try {
                $this->envoiCommandeWorkflow->apply($product, 'pour_livraison');
            } catch (LogicException $exception) {
                //
            }
            $entityManager->persist($product);
            $entityManager->flush();
        }

        return $this->render('workflow/livree.html.twig', ['product' => $product]);
    }
}
