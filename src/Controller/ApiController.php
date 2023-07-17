<?php

namespace App\Controller;

use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/api', name: 'app_api')]
    public function index(): Response
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }
    #[Route('/api/{id}/edit', name: 'app_api_event_edit', methods: ['PUT'])]
	public function majEvent(?Reservation $reservation, Request $request): Response
    {
        $data = json_decode($request->getContent());

        if(
            isset($data->start) && !empty($data->start) &&
            isset($data->description) && !empty($data->description) &&
            isset($data->backgroundColor) && !empty($data->backgroundColor) &&
            isset($data->borderColor) && !empty($data->borderColor) &&
            isset($data->textColor) && !empty($data->textColor)

        ){
            $code = 200;
            if(!$reservation){
                $reservation = new Reservation();
                $code = 201;
            }

            $reservation->setDescription($data->description);
            $reservation->setDatecheckin(new \DateTime($data->start));
            if($data->allDay == true){
                $reservation->setDatecheckout(new \DateTime($data->start));
            }else{
                $reservation->setDatecheckout(new \DateTime($data->end));
            }
            $reservation->setBackgroundColor($data->backgroundColor);
            $reservation->setBorderColor($data->borderColor);
            $reservation->setTextColor($data->textColor);

            $this->em->persist($reservation);
            $this->em->flush();

            return new Response('ok', $code);

        }else{
            return new Response('Données incomplètes', 404);
        }
    }
}
