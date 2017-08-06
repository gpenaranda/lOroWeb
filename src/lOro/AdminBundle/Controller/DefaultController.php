<?php

namespace lOro\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('lOroAdminBundle:Default:index.html.twig', array('name' => $name));
    }
}
