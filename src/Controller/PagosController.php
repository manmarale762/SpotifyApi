<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Premium;
use App\Entity\Suscripcion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PagosController extends AbstractController
{
    public function suscripcion(Request $request, SerializerInterface $serializer): Response
    {

        if($request->isMethod('GET')){
            $id = $request->get('id');
            $suscripcion = $this->getDoctrine()->getRepository(Suscripcion::class)->findOneBy(['id'=>$id]);

            $data = $serializer->serialize($suscripcion, 'json', ['groups' => 'suscripciones:read']);

            return new Response($data, 200, ['Content-Type' => 'application/json']);
        }

        return new Response("Not allowed", 405);

    }

    public function suscripciones(Request $request, SerializerInterface $serializer): Response
    {
        if($request->isMethod('GET')){

            $id = $request->get('id');

            // Buscamos el registro en la tabla Premium por el ID de usuario
            // Como la PK de Premium es usuario_id, podemos buscar directamente por el ID
            $premium = $this->getDoctrine()->getRepository(Premium::class)->findOneBy(['usuario' => $id]);

            if (!$premium) {
                return new Response(json_encode(['error' => 'Usuario no es premium']), 404);
            }

            $suscripciones = $this->getDoctrine()
                ->getRepository(Suscripcion::class)
                ->findBy(['premiumUsuario' => $premium]);

            $data = $serializer->serialize($suscripciones, 'json', ['groups' => 'suscripciones:read']);

            return new Response($data, 200, ['Content-Type' => 'application/json']);

        }

        return new Response("Not allowed", 405);

    }
}
