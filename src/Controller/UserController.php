<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Calidad;
use App\Entity\Configuracion;
use App\Entity\Free;
use App\Entity\Idioma;
use App\Entity\Premium;
use App\Entity\TipoDescarga;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    public function usuario(Request $request, SerializerInterface $serializer): Response
    {
        if ($request->isMethod('GET')){
            //Llamamos a todos los usuarios de la tabla Usuario
            $usuarios = $this->getDoctrine()
                ->getRepository(Usuario::class)
                ->findAll();
            $data = $serializer->serialize($usuarios, 'json', ['groups' => 'usuario:read']);

            return new Response($data,200,['Content-Type' => 'application/json']);
        }
        if($request->isMethod('POST')){

            //Leemos query parameter , si es premium o no

            $premium = $request->query->get('premium');

            //Leemos lo que viene en el body de la peticion

            //User
            $data = $request->getContent();

            $user = $serializer->deserialize($data,Usuario::class,'json',['groups' => 'usuario:write']);


            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($user);

            //Dependiendo del tipo usuario, creamos free o premium (relacion tabla)

            if($premium){
                $premium = new Premium();
                $premium->setUsuario($user);
                $proximRenovacion = (new \DateTime('today'))->modify('+1 month');

                $premium->setFechaRenovacion($proximRenovacion);
                $entityManager->persist($premium);
            }else{
                $free = new Free();
                $free->setUsuario($user);
                $proximaRevision = (new \DateTime('today'))->modify('+1 month');

                $free->setFechaRevision($proximaRevision);
                $entityManager->persist($free);
            }

            //Creamos la configuracion por defecto para el user
            $config = new Configuracion();
            $config->setUsuario($user);
            $config->setAutoplay(true);
            $config->setNormalizacion(true);
            $config->setAjuste(true);

            //Llamamos a los settings por defecto = 1
            $calidad = $entityManager->getRepository(Calidad::class)->findOneBy(['id' => 1]);
            $idioma = $entityManager->getRepository(Idioma::class)->findOneBy(['id' => 1]);
            $tipoDescarga = $entityManager->getRepository(TipoDescarga::class)->findOneBy(['id' => 1]);

            $config->setIdioma($idioma);
            $config->setTipoDescarga($tipoDescarga);
            $config->setCalidad($calidad);

            $entityManager->persist($config);

            try {
                // El flush guarda TODO: el user, el plan y la configuración
                $entityManager->flush();
            } catch (\Exception $e) {
                // Si entra aquí, es que el email o el username ya están en uso
                return new Response(
                    json_encode(['error' => 'No se pudo crear el usuario. El email o username ya existen en el sistema.']),
                    Response::HTTP_CONFLICT, // Código 409
                    ['Content-Type' => 'application/json']
                );
            }
            $entityManager->flush();

            //En read porque no queremos exponer la contra
            $data = $serializer->serialize($user, 'json', ['groups' => 'usuario:read']);

            return new Response($data,Response::HTTP_ACCEPTED,['Content-Type' => 'application/json']);
        }

        return new Response("Not allowed",405);
    }

    public function usuarios(Request $request, SerializerInterface $serializer): Response
    {
        //Como son comunes los tres metodos que necesitamos el $id, lo hacemos fuera
        $id = $request->get('id');
        $user = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['id'=>$id]);

        if($request->isMethod("GET")){

        }


        return new Response("Not allowed",405);
    }
}
