<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 30/09/2017
 * Time: 12:10
 */

namespace FormerDUTStudentsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class SecurityController extends Controller
{
    public function loginAction(Request $request) {
        //si connecté on redirige vers students/getAll
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('students');
        }

        $authenticationUtils = $this->get('security.authentication_utils');

        //si non connecté on redirige vers login
        return $this->render('FormerDUTStudentsBundle::login.html.twig', array(
            'last_username' => $authenticationUtils->getLastUsername(),
            'error'         => $authenticationUtils->getLastAuthenticationError(),
        ));
    }
}