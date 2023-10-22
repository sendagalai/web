<?php

namespace App\Controller;

use App\Repository\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Student;
use Symfony\Component\Form\AbstractType;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ClassroomRepository;
use App\Form\StudentType;

class StudentController extends AbstractController
{
    #[Route('/student', name: 'app_student')]
    public function index(): Response
    {
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }

    #[Route('/fetch', name: 'fetch')]

    public function fetch(StudentRepository $repo):Response {
        $result=$repo->findAll();
        return $this->render('student/test.html.twig',[
            'response' =>$result
        ]);

    }

    #[Route('/add', name: 'add')]

    public function add(ManagerRegistry $mr,ClassroomRepository $repo):Response{
   $c=$repo->find(1);
   $s=new Student();
   $s->setName('sara');
   $s->setEmail('aa@gmail.com');
   $s->setAge('24');
   $s->setClassroom($c);
   $em=$mr->getManager();
   $em->persist($s);
   $em->flush();
   return  $this->redirectToRoute('fetch');
    }

    #[Route('/addF/student', name:'addF')]

public function addF(ManagerRegistry $mr,Request $req):Response
    {

       $s=new Student();
       $form=$this->createForm(StudentType::class,$s);
       $form->handleRequest($req);
       if($form->isSubmitted()){
        $em=$mr->getManager();
        $em->persist($s);
        $em->flush();
       return  $this->redirectToRoute('fetch');
       }
        return  $this->render('student/add.html.twig',[
            'f'=>$form->createView()
        ]);

    } 

    


    #[Route('/getAll',name:'getAll')]
    public function get(StudentRepository $studentRepository){
        $students = $studentRepository->findAll();
        return $this->render('student/showList.html.twig',[
            'students'=>$students
        ]);
    }
    #[Route('/students/delete/{id}',name:'delete')]
    public function delete($id,ManagerRegistry $manager,StudentRepository $studentRepository){
        $student = $studentRepository->find($id);
        $manager->getManager()->remove($student);
        $manager->getManager()->flush();
        return $this->redirectToRoute('getAll');
    }
    #[Route('/students/edit/{id}',name:'update')]
    public function edit(Request $request, $id, EntityManagerInterface $entityManager): Response
    {
        $student = $entityManager->getRepository(Student::class)->find($id);

        if (!$student) {
            throw $this->createNotFoundException('Aucun étudiant trouvé pour cet ID');
        }

        if ($request->isMethod('POST')) {
            $name = $request->request->get('name'); 
           
            
            $entityManager->persist($student);
            $entityManager->flush();

            return $this->redirectToRoute('student_index'); 
        }

        return $this->render('student/edit.html.twig', [
            'student' => $student,
        ]);
    }

}