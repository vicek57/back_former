<?php

namespace FormerDUTStudentsBundle\Controller;

use FormerDUTStudentsBundle\FormerDUTStudentsBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class ApiController extends Controller
{
    public function getAllStudentsAction() {
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

        //return new JsonResponse($jsonArray);
        return $this->render('FormerDUTStudentsBundle::index.html.twig');
    }

    public function getStudent($id) {
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

    public function addStudentAction(Request $request) {
        $repository = $this->getDoctrine()
            ->getManager();
        $data = json_decode($request->getContent(), true);  //permet de récupérer les données en POST

        $student = new Student($data['name'], $data['lastname'], $data['mail'], $data['graduationyear'], $data['phone'], $data['company'], $data['job'], $data['idformation']);

        $repository->persist($student);
        $repository->flush();
    }

    public function deleteStudentAction(Request $request) {
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

    public function modifyStudentAction(Request $request) {
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
}