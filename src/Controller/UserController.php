<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Calidad;
use App\Entity\Configuracion;
use App\Entity\Free;
use App\Entity\Idioma;
use App\Entity\Premium;
use App\Entity\Suscripcion;
use App\Entity\TipoDescarga;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    public function usuarios(Request $request, SerializerInterface $serializer): Response
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

            return new Response($data,201 ,['Content-Type' => 'application/json']);
        }

        return new Response("Not allowed",405);
    }


    public function usuario(Request $request, SerializerInterface $serializer): Response
    {
        //Como son comunes los tres metodos que necesitamos el $id, lo hacemos fuera
        $id = $request->get('id');
        $user = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['id'=>$id]);

        if($request->isMethod("GET")){
            $data = $serializer->serialize($user, 'json', ['groups' => 'usuario:read']);

            return new Response($data,200,['Content-Type' => 'application/json']);
        }

        if ($request->isMethod('PUT')) {

            //De primeras si no existe user, a fer la ma
            if (!$user) {
                return new Response(json_encode(['error' => 'Usuario no encontrado']), 404);
            }


            //Leemos body de la peticion
            $data = $request->getContent();

            //Deserializamos SOBBRE EL OBJETO QUE YA EXISTE y uso el ABSTRACTNORMALIZER para no volver a crear un objeto nuevo
            //sino remplazar el existente
            $serializer->deserialize($data, Usuario::class, 'json', [
                'groups' => 'usuario:write',
                AbstractNormalizer::OBJECT_TO_POPULATE => $user
            ]);

            //Llamamos al manager para que funcione
            $entityManager = $this->getDoctrine()->getManager();


            try {
                $entityManager->flush();
            } catch (\Exception $e) {
                return new Response(json_encode(['error' => 'El email o username ya existe']), 409); // 409 Conflict
            }
            //solo tenemos que guardar cambios,porque ya conoce cual es el objeto

            $data2 = $serializer->serialize($user, 'json', ['groups' => 'usuario:read']);

            return new Response($data2, Response::HTTP_ACCEPTED, ['Content-Type' => 'application/json']);

        }
        if ($request->isMethod('DELETE')) {

            //De primeras si no existe user, a fer la ma
            if (!$user) {
                return new Response(json_encode(['error' => 'Usuario no encontrado']), 404);
            }
            //Traemos al manager para los metodos de las class
            $entityManager = $this->getDoctrine()->getManager();

            //Buscamos la config de ese user
            $config = $entityManager->getRepository(Configuracion::class)->findOneBy(['usuario' => $user]);

            //Si hay config la eliminamos
            if ($config) {
                $entityManager->remove($config);
            }

            //Traremos si es premium y hacemos los pasos anteriores
            $premium = $entityManager->getRepository(Premium::class)->findOneBy(['usuario' => $user]);

            if ($premium) {
                $entityManager->remove($premium);
            }
            //Traemos si es free y hacmoes los pasos anteriores
            $free = $entityManager->getRepository(Free::class)->findOneBy(['usuario' => $user]);

            if ($free) {
                $entityManager->remove($free);
            }

            //Por ultimo nos cargamos al usuario
            $entityManager->remove($user);

            //Ejecutamos todos los cambios de una
            $entityManager->flush();

            return new Response(json_encode(['mensaje' => 'Eliminado el usuario con sus relaciones']), Response::HTTP_ACCEPTED,
                ['Content-Type' => 'application/json']);


        }
        return new Response("Not allowed", 405);
    }


    public function consultarPlan(Request $request, SerializerInterface $serializer): Response
    {
        if($request->isMethod('GET')){
            $id = $request->get('id');
            $user = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['id'=>$id]);

            //De primeras si no existe user, a fer la ma
            if (!$user) {
                return new Response(json_encode(['error' => 'Usuario no encontrado']), 404);
            }

            $plan = $this->getDoctrine()->getRepository(Premium::class)->findOneBy(['user'=>$user]);
            $tipoPlan = 'premium';

            if(!$plan){
                $plan = $this->getDoctrine()->getRepository(Free::class)->findOneBy(['usuario' => $user]);
                $tipoPlan = 'gratuito';
            }

            $conjunto = [
                'tipoPlan' => $tipoPlan,
                'detalles' => $plan
            ];

            $data = $serializer->serialize($conjunto, 'json', [
                'groups' => 'plan:read'
            ]);

            return new Response($data, 200, ['Content-Type' => 'application/json']);

        }

        return new Response("Not allowed", 405);
    }

    public function premium(Request $request, SerializerInterface $serializer): Response
    {
        if($request->isMethod('POST')){

            $id = $request->get('id');
            $user = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['id'=>$id]);
            $entityManager = $this->getDoctrine()->getManager();


            if(!$user){
                return new Response(json_encode(['error' => 'Usuario no encontrado']), 404);
            }

            $YaesPremium = $this->getDoctrine()->getRepository(Premium::class)->findOneBy(['user'=>$user]);

            if ($YaesPremium) {
                return new Response(json_encode(['error' => 'El usuario ya es premium']), 409);
            }

            // --- LIMPIEZA: Si era Free, lo borramos ---
            $planFree = $this->getDoctrine()->getRepository(Free::class)->findOneBy(['usuario' => $user]);
            if ($planFree) {
                $entityManager->remove($planFree);
            }

            // 1. Creamos nuevo Premium
            $premium = new Premium();
            $premium->setUsuario($user);
            $premium->setFechaRenovacion((new \DateTime('today'))->modify('+1 month'));
            $entityManager->persist($premium);

            // 2. Creamos la Suscripción
            $suscripcion = new Suscripcion();
            $suscripcion->setPremiumUsuario($premium);
            $suscripcion->setFechaInicio(new \DateTime('today'));
            $suscripcion->setFechaFin((new \DateTime('today'))->modify('+1 month'));
            $entityManager->persist($suscripcion);

            // 3. BLOQUE DE SEGURIDAD
            try {
                $entityManager->flush();
            } catch (\Exception $e) {
                return new Response(json_encode(['error' => 'Error de integridad: el usuario ya tiene un plan activo']), 409);
            }

            $data = $serializer->serialize($user, 'json', ['groups' => 'usuario:read']);
            return new Response($data, 201, ['Content-Type' => 'application/json']);
        }

        return new Response("Not allowed", 405);
    }


}
