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
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('students');
        }

        $authenticationUtils = $this->get('security.authentication_utils');

        return $this->render('FormerDUTStudentsBundle::login.html.twig', array(
            'last_username' => $authenticationUtils->getLastUsername(),
            'error'         => $authenticationUtils->getLastAuthenticationError(),
        ));
    }
}