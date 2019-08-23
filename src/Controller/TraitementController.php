<?php

namespace App\Controller;



use index;
use App\Entity\User;
use App\Entity\Compte;
use App\Form\UserType;
use App\Form\CompteType;
use App\Entity\Versement;
use App\Entity\Entreprise;
use App\Form\VersementType;
use App\Form\EntrepriseType;
use App\Repository\CompteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * @Route("/api")
 */
class TraitementController extends AbstractController
{
    
    /**
     * @Route("/traitement", name="traitement",methods={"POST"})
     * @Security("has_role('ADMIN_SYSTEME')")
     */
   
   
    
    public function index( Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        
        
        
            $user = new User();
            $entreprise = new Entreprise();
            $compte = new Compte();
            $use =  $this->getUser();

            $form = $this->createForm(UserType::class, $user);
            $form->handleRequest($request);
            $data = $request->request->all();
            $user->setRoles(["ROLE_ADMIN_COMPTE"]);
            
            $form->submit($data);
            
            
            $user->setCreePart($use);
           
            $form = $this->createForm(EntrepriseType::class, $entreprise);
            $entreprise->setSysteme($use);
            $form->handleRequest($request);
            $form->submit($data);
            
            
            $jour=date('d');
            $mois=date('m');
            $annee=date('Y');
            $heur=date('H');
            $minute=date('i');
            $seconde=date('s');
            $num=$annee.$mois.$jour.$heur.$minute.$seconde;

            
            $form = $this->createForm(CompteType::class, $compte);
            $compte->setEntreprise($entreprise);
            
            $compte->setSysteme($use);
            $compte->setNumeroCompte($num);

            $form->submit($data);

           
            $errors = $validator->validate($user);
            if(count($errors)) {
                $errors = $serializer->serialize($errors, 'json');
                return new Response($errors, 500, [
                    'Content-Type' => 'application/json'
                ]);
            }

            $errors = $validator->validate($entreprise);
            if(count($errors)) {
                $errors = $serializer->serialize($errors, 'json');
                return new Response($errors, 500, [
                    'Content-Type' => 'application/json'
                ]);
            }
            $errors = $validator->validate($compte);
            if(count($errors)) {
                $errors = $serializer->serialize($errors, 'json');
                return new Response($errors, 500, [
                    'Content-Type' => 'application/json'
                ]);
            }
            $user->setEntreprise($entreprise);
            $user->setCompte($compte);
            $entityManager->persist($user);
            $entityManager->persist($entreprise);
            $entityManager->persist($compte);  
            $hash = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            
            $entityManager->flush();

            $data = [
                'status' => 201,
                'message' => 'nouveau partenaire creer'
            ];

            return new JsonResponse($data, 201);
        
    }

    /**
 * @Route("/bloquer", name="userBlock", methods={"GET","POST"})
 * @Route("/debloquer", name="userDeblock", methods={"GET","POST"})
 */

/* public function userBloquer(JWTEncoderInterface $JWTEncoder, Request $request, UserRepository $userRepo,EntityManagerInterface $entityManager): Response
{
    $values = json_decode($request->getContent());
    $user=$userRepo->findOneByUsername($values->username);
    $password=$values->password;
    
    echo $situation=$user->getIsActive();

    if($situation =="0"){
        $user->setStatut("1");
        $entityManager->flush();
        $token = '';

    return new JsonResponse(['message' => 'tu es bloqué']);
    }
    
    else{
        $situation("0");
        $entityManager->flush();
        $data = [
            'status' => 200,
            'message' => 'utilisateur a été bloqué'
        ];
        return new JsonResponse($data);
    }
}
 */
    /**
     * @Route("/depot", name="depot", methods={"POST"})
     * @Security("has_role('ROLE_ADMIN_CAISSIER')")
     * 
     */

    public function versement (CompteRepository $compteRepository ,Request $request,EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
       // $values = json_decode($request->getContent());

       $versement = new Versement();
            $comp = new Compte();
        $form = $this->createForm(VersementType::class, $versement);
        
            $form->handleRequest($request);
            $data = $request->request->all();
            /* var_dump($data);
            die(); */
            
            $compte= $compteRepository->findOneBy(['NumeroCompte'=>$data['numeroCompte']]);
          
            
        if ($compte ) {
                
            
                if($data['depot'] > 75000){
                    
                    $use = $this->getUser();
                  
        
                  
                    $idcompte=$compteRepository->find($compte->getId());
                    //var_dump($idcompte);die();
                    $versement->setCompte($idcompte);
                    $versement->setDepot($data['Depot']);

                    $use->getCompte()->setSolde($idcompte->getSolde() + $versement->getCompte());
                    //$idcompte->setSolde($idcompte->getSolde() + $versement->getCompte());
                    $form->submit($data);

                    if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager->persist($versement);                    
                    $entityManager->flush();
                    }
                    
                    $errors = $validator->validate($versement);
                    if(count($errors)) {
                        $errors = $serializer->serialize($errors, 'json');
                        return new Response($errors, 500, [
                            'Content-Type' => 'application/json'
                        ]);
                    }
                    

                    $data = [
                        'status' => 200,
                        'message' => 'Vesement effectuer'
                    ];

                    return new JsonResponse($data, 200);
                    }
                else{
                    $data = [
                        'status' => 201,
                        'message' => 'Versement inferieur a 75000'
                    ];
        
                    return new JsonResponse($data, 201);
                }
                       
                       
        }
        else {
            $data = [
                'status' => 201,
                'message' => 'compte n\'existe pas'
            ];

            return new JsonResponse($data, 201);

            
        }
        

        
            /* 
            if ($use->getRoles()[0]=='ADMIN_SYSTEME')
            {
                //if ($user->getRole()==1) 
                    $user->setRoles(["ADMIN_COMPTE"]);
                
               // elseif ($user->getRole()==2)
                    $user->setRoles["ADMIN_CAISSIER"];
            } 
                
            elseif ($use->getRoles()[0]=='ADMIN_COMPTE' || $use->getRoles()[0]=='ADMIN')
                {
                    if ($user->getRole()== 3) {
                        $user->setRoles(["ADMIN"]);
                        
                    }
                    elseif ($user->getRole()== 4) {
                        $user->setRoles(["CAISSIER"]);
                        
                    }
                    
                }
                
            
            else {
                $data = [
                    'status' => 404,
                    'message' => 'vous avez pas le privilege '
                ];
                return new JsonResponse($data, 404);
            } */
           
    }
}
