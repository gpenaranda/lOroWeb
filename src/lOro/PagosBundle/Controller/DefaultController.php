<?php

namespace lOro\PagosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('lOroPagosBundle:Default:index.html.twig', array('name' => $name));
    }
}
