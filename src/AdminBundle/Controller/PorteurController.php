<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Porteur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Porteur controller.
 *
 * @Route("espace/administration/porteur")
 */
class PorteurController extends Controller
{
    /**
     * Lists all porteur entities.
     *
     * @Route("/", name="porteur_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $porteurs = $em->getRepository('AdminBundle:Porteur')->findAll();

        return $this->render('@Admin/porteurs/index.html.twig', array(
            'porteurs' => $porteurs,
        ));
    }

    /**
     * Creates a new porteur entity.
     *
     * @Route("/new", name="porteur_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $porteur = new Porteur();
        $form = $this->createForm('AdminBundle\Form\PorteurType', $porteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($porteur);
            $em->flush();

            return $this->redirectToRoute('porteur_show', array('id' => $porteur->getId()));
        }

        return $this->render('@Admin/porteurs/new.html.twig', array(
            'porteur' => $porteur,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a porteur entity.
     *
     * @Route("/{id}", name="porteur_show")
     * @Method("GET")
     */
    public function showAction(Porteur $porteur)
    {
        $deleteForm = $this->createDeleteForm($porteur);

        return $this->render('porteur/show.html.twig', array(
            'porteur' => $porteur,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing porteur entity.
     *
     * @Route("/{id}/edit", name="porteur_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Porteur $porteur)
    {
        $deleteForm = $this->createDeleteForm($porteur);
        $editForm = $this->createForm('AdminBundle\Form\PorteurType', $porteur);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('porteur_edit', array('id' => $porteur->getId()));
        }

        return $this->render('porteur/edit.html.twig', array(
            'porteur' => $porteur,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a porteur entity.
     *
     * @Route("/{id}", name="porteur_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Porteur $porteur)
    {
        $form = $this->createDeleteForm($porteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($porteur);
            $em->flush();
        }

        return $this->redirectToRoute('porteur_index');
    }

    /**
     * Creates a form to delete a porteur entity.
     *
     * @param Porteur $porteur The porteur entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Porteur $porteur)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('porteur_delete', array('id' => $porteur->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
