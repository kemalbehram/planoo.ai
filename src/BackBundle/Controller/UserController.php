<?php

namespace BackBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UserBundle\Entity\User;
use UserBundle\Form\UserType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use PartnersBundle\Service\IzIdentificationPartnerService;

/**
 * User controller.
 *
 */
class UserController extends Controller {

    /**
     * Lists all User entities.
     *
     */
    public function indexAction($isAdminView) {

        $identificationPartnerService = $this->container->get(IzIdentificationPartnerService::class);
        $partner = $identificationPartnerService->getCurrentPartner();

        return $this->render('BackBundle:User:index.html.twig', array(
                    'partner' => $partner,
                    'isAdminView' => $isAdminView
        ));
    }

    /**
     * Finds and displays a User entity.
     *
     */
    public function showAction(User $user) {
        $em = $this->getDoctrine()->getManager();
        $bps = $em->getRepository('BPBundle:BusinessPlan')->myFindBy($user->getId());

        return $this->render('BackBundle:User:show.html.twig', array(
                    'user' => $user,
                    'bps' => $bps,
        ));
    }

    public function formAction(Request $request, $id = 0) {
        $user = new User();
        $title_action = 'Création';

        // Edit action
        if ($id > 0) {
            $user = $this->getDoctrine()->getManager()->getRepository("UserBundle:user")->find($id);
            $title_action = 'Edition';
        }
        // Création form
        $formBuilder = $this->get('form.factory')->createBuilder(UserType::class, $user)
                ->add('roles', ChoiceType::class, [
            'required' => true,
            'multiple' => true,
            'choices' => [
                'Utilisateur' => 'ROLE_USER',
                'Partenaire' => 'ROLE_PARTNER',
                'Administrateur' => 'ROLE_ADMIN'
            ],
            'attr' => [
                'class' => 'form-control'
            ]
        ]);
        $form = $formBuilder->getForm();
        if ($id == null) {
//            $form->add('username' , null, array('required' => true));
            $form->add('password', null, array('required' => true));
        }
//        $form->add('submit', 'submit', array('attr' => array('class' => 'btn btn-success'), 'label' => 'Ajouter'));
        // Enregistrement data
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if ($id == null) {
                $factory = $this->get('security.encoder_factory');
                $hash = $factory->getEncoder($user)->encodePassword($user->getPassword(), null);
                $user->setPassword($hash);
            }
            //$user->setRoles(array($form->get("roles_")->getData()));
            $em->persist($user);
            $em->flush();
            $session = $request->getSession();
            $this->addFlash("info", "Opération validée : le user a bien été créé .");


            return $this->redirect($this->generateUrl('back_user_index'));
        }


        return $this->render('BackBundle:User:form.html.twig', [
                    'form' => $form->createView(),
                    'title_action' => $title_action,
                    'user' => $user
        ]);
    }

    /**
     * Creates a new User entity.
     *
     */
    public function newAction(Request $request) {
        $user = new User();
        $form = $this->createForm('UserBundle\Form\UserType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('back_user_show', array('id' => $user->getId()));
        }

        return $this->render('BackBundle:User:new.html.twig', array(
                    'user' => $user,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     */
    public function editAction(Request $request, User $user) {
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('UserBundle\Form\UserType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('back_user_edit', array('id' => $user->getId()));
        }

        return $this->render('BackBundle:User:edit.html.twig', array(
                    'user' => $user,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Delete user
     *
     */
    public function deleteAction(Request $request, User $user) {

        $em = $this->getDoctrine()->getManager();
        
        // initiate an array for the removed listeners
        $originalEventListeners = [];

        // cycle through all registered event listeners
        foreach ($em->getEventManager()->getListeners() as $eventName => $listeners) {
            foreach ($listeners as $listener) {
                if ($listener instanceof \Gedmo\SoftDeleteable\SoftDeleteableListener) {

                    // store the event listener, that gets removed
                    $originalEventListeners[$eventName] = $listener;

                    // remove the SoftDeletableSubscriber event listener
                    $em->getEventManager()->removeEventListener($eventName, $listener);
                }
            }
        }

        // remove the entity
        $em->remove($user);
        $em->flush();

       // re-add the removed listener back to the event-manager
        foreach ($originalEventListeners as $eventName => $listener) {
            $em->getEventManager()->addEventListener($eventName, $listener);
        }

        return $this->redirectToRoute('back_user_index');
    }

    /**
     * Activate or desactivate an user
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activate_or_desactivateAction(Request $request, User $user) {
        $em = $this->getDoctrine()->getManager();

        if ($user->isEnabled()) {
            $user->setEnabled(false);
        } else {
            $user->setEnabled(true);
        }
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('back_user_index');
    }

    public function ajaxUserListDataTableAction(Request $request, $isAdminView) {

        $length = $request->get('length');
        $length = $length && ($length != -1) ? $length : 0;

        $start = $request->get('start');
        $start = $length ? ($start && ($start != -1) ? $start : 0) / $length : 0;

        $search = $request->get('search');
        $filters = [
            'query' => @$search['value']
        ];

        if ($isAdminView === 'true') {
            $partner = null;
        } else {
            $identificationPartnerService = $this->container->get(IzIdentificationPartnerService::class);
            $partner = $identificationPartnerService->getCurrentPartner();
        }


        $users = $this->getDoctrine()->getRepository('UserBundle:User')->search(
                $filters, $start, $length, false, $partner
        );

        $output = array(
            'data' => array(),
            'recordsFiltered' => $this->getDoctrine()->getRepository('UserBundle:User')->search($filters, 0, null, true, $partner),
            'recordsTotal' => $this->getDoctrine()->getRepository('UserBundle:User')->search(array(), 0, null, true, $partner)
        );

        foreach ($users as $user) {

            $output['data'][] = [
                'lastLogin' => $user->getLastLogin() ? $user->getLastLogin()->format('d/m/Y H:i:s') : '00/00/00 00:00:00',
                'createdAt' => $user->getCreatedAt()->format('d/m/Y'),
                'updatedAt' => $user->getUpdatedAt()->format('d/m/Y'),
                'owner' => '<a href="' . $this->generateUrl('back_user_show', array('id' => $user->getId())) . '">' . $user->getUsername() . '</a></td>',
                'mail' => '<a href="' . $this->generateUrl('back_user_show', array('id' => $user->getId())) . '">' . $user->getEmail() . '</a></td>',
                'partner' => $user->getPartner() ? $user->getPartner()->getNom() : null,
                'stateAction' => $user->isEnabled() ? '<a href="' . $this->generateUrl('back_user_activate_desactivate', array('id' => $user->getId())) . '" class="btn btn-success">Activé</a>' : '<a href="' . $this->generateUrl('back_user_activate_desactivate', array('id' => $user->getId())) . '" class="btn btn-danger">Désactivé</a>',
                    //'actions' => '<a href="' . $this->generateUrl('back_user_edit', array('id' => $user->getId())) . '" class="btn btn-warning"><i class="fa fa-pencil"></i></a>'
            ];
        }

        return new Response(json_encode($output), 200, ['Content-Type' => 'application/json']);
    }

}
