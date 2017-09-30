<?php

namespace FormerDUTStudentsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ApiController extends Controller
{
    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function getAllStudentsAction()
    {
        /*
        //check si l'utilisateur est un user -> si oui affiche la page sinon bloque l'accés
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException('Accès limité aux utilisateurs.');
        }
        */

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('FormerDUTStudentsBundle:Student');

        $students = $repository->findAll();

        $jsonArray = [];

        foreach ($students as $student) {
            $array = array("id" => $student->GetId(),
                "name" => $student->GetName(),
                "lastName" => $student->GetLastName(),
                "mail" => $student->GetMail());

            array_push($jsonArray, $array);
        }

        return new Response($this->getUser());
        //return new JsonResponse($jsonArray);
        //return $this->render('FormerDUTStudentsBundle::index.html.twig');
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function getStudent($id)
    {
        /*
        //check si l'utilisateur est un user -> si oui affiche la page sinon bloque l'accés
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException('Accès limité aux utilisateurs.');
        }
        */

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('FormerDUTStudentsBundle:Student');

        $student = $repository->findOneBy([
            "id" => $id
        ]);

        $jsonArray = [];

        $array = array("id" => $student->GetId(),
            "name" => $student->GetName(),
            "lastName" => $student->GetLastName(),
            "mail" => $student->GetMail());

        array_push($jsonArray, $array);

        return new JsonResponse($jsonArray);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addStudentAction(Request $request)
    {
        /*
        //check si l'utilisateur est un admin -> si oui affiche la page sinon bloque l'accés
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Accès limité aux admins.');
        }
        */

        $repository = $this->getDoctrine()
            ->getManager();
        $data = json_decode($request->getContent(), true);  //permet de récupérer les données en POST

        $student = new Student($data['name'], $data['lastname'], $data['mail'], $data['graduationyear'], $data['phone'], $data['company'], $data['job'], $data['idformation']);

        $repository->persist($student);
        $repository->flush();
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteStudentAction(Request $request)
    {
        /*
        //check si l'utilisateur est un admin -> si oui affiche la page sinon bloque l'accés
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Accès limité aux admins.');
        }
        */

        $repository = $this->getDoctrine()
            ->getManager();
        $data = json_decode($request->getContent(), true);

        $id = $data->getId();

        $student = $repository->findOneBy([
            "id" => $id
        ]);

        $repository->remove($student);
        $repository->flush();
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function modifyStudentAction(Request $request)
    {
        /*
        //check si l'utilisateur est un admin -> si oui affiche la page sinon bloque l'accés
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Accès limité aux admins.');
        }
        */

        $repository = $this->getDoctrine()
            ->getManager();
        $data = json_decode($request->getContent(), true);

        $id = $data->getId();

        $student = $repository->findOneBy([
            "id" => $id
        ]);

        $student->setName($data['name']);
        $student->setLastName($data['lastname']);
        $student->setMail($data['mail']);
        $student->setGraduationYear($data['graduationyear']);
        $student->setPhone($data['phone']);
        $student->setCompany($data['company']);
        $student->setJob($data['job']);
        $student->setIdFormation($data['idformation']);

        $repository->flush();
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function sendMailAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);

    }
}