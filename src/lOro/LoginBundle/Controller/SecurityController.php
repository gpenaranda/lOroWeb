<?php

namespace lOro\LoginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use lOro\EntityBundle\Entity\Users;
use Symfony\Component\HttpFoundation\Response;
use lOro\LoginBundle\Form\CambiarContrasenaType;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SecurityController extends Controller
{
    public function indexAction()
    {
        return $this->render('lOroLoginBundle:Default:index.html.twig');
    }
    

    public function logoutAction()
    {
      $this->container->get('security.token_storage')->setToken(null);

      return $this->redirect($this->generateUrl('login'));
    }
    
    public function loginAction(Request $request)
    {
      $authenticationUtils = $this->get('security.authentication_utils');

      $error = $authenticationUtils->getLastAuthenticationError();

      $lastUsername = $authenticationUtils->getLastUsername();



      return $this->render('lOroLoginBundle:Security:login.html.twig',
        array(
          'last_username' => $lastUsername,
          'error'         => $error,
        )
      );
    }
    
    public function cargarUserAction() {
      $manager = $this->getDoctrine()->getManager();
     $user = new Users();
      $user->setUsername("jenny");
      $user->setSalt(md5(uniqid()));
      $user->setEmail("jenny@test.com");
      $user->setIsActive(1);

      $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
      $user->setPassword($encoder->encodePassword('123456', $user->getSalt()));      
      
      $manager->persist($user);

      $manager->flush();
      return new Response('cargado');
    }

    
    public function cambiarContrasenaAction(Request $request)
    {
      $em = $this->getDoctrine()->getManager();
      $form = $this->cambiarContrasenaForm();
      $form->handleRequest($request);

      $user = $this->get('security.token_storage')->getToken()->getUser();
      
      // Get list of roles for current user
      $roles = $user->getRoles();




      


      if ($form->isValid()) {
        $password = $form->get('password')->getData();
        
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
        $em->persist($user);
        $em->flush();

        $this->get('session')->getFlashBag()->add(
            'notice',
            'La contraseña ha sido cambiada exitosamente.'
        );
        



        

      // Tranform this list in array
        $rolesTab = array_map(function($role){ 
          return $role->getRole(); 
        }, $roles);
        
        if (in_array('ROLE_USER', $rolesTab, true)):
          return $this->redirect($this->generateUrl('pagina_inicial'));
        elseif (in_array('ROLE_PROVEEDOR', $rolesTab, true)):
          return $this->redirect($this->generateUrl('home_proveedores'));
        elseif (in_array('ROLE_SUPER_ADMIN', $rolesTab, true)):
          return $this->redirect($this->generateUrl('pagina_inicial'));
        endif;
      }
      
      return $this->render('lOroLoginBundle:Security:cambiar_contrasena.html.twig', array(
          'form' => $form->createView()
      ));
    }    

    protected function cambiarContrasenaForm() {
        $form = $this->createForm(CambiarContrasenaType::class,null, array(
            'attr' => array('id' => 'cambia-contrasena-form'),
            'action' => $this->generateUrl('cambiar_contrasena'),
            'method' => 'POST',
        ));

        $form->add('submit',SubmitType::class, array('label' => 'Cambiar Contraseña',
                                             'attr' => array('class' =>'btn btn-lg btn-success pull-right')));

        return $form;        
    }
    
}
