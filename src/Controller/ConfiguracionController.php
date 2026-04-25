<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Calidad;
use App\Entity\Configuracion;
use App\Entity\Idioma;
use App\Entity\TipoDescarga;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class ConfiguracionController extends AbstractController
{

    public function configuracionUser(Request $request, SerializerInterface $serializer): Response
    {
        if ($request->isMethod('POST')) {
            $id = $request->get('id');

            $config = $this->getDoctrine()->getRepository(Configuracion::class)->findOneBy(['id' => $id]);

            if (!$config) {
                return new Response(json_encode(['error' => 'Usuario no tiene configuracion']), 404);
            }


            $data = $serializer->serialize($config, 'json', ['groups' => 'configuracion:read']);

            return new Response ($data, 200, ['Content-Type' => 'application/json']);

        }
        return new Response("Not allowed", 405);
    }

    public function cambiarConfiguracion(Request $request, SerializerInterface $serializer): Response
    {
        if ($request->isMethod('PUT')){

            $id = $request->get('id');
            $entityManager = $this->getDoctrine()->getManager();
            $user = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['id'=>$id]);
            $config = $this->getDoctrine()->getRepository(Configuracion::class)->findOneBy(['usuario'=>$user]);

            if (!$user) {
                return new Response(json_encode(['error' => 'Usuario no encontrado']), 404);
            }

            if (!$config){
                $config = new Configuracion();
                $config->setUsuario($user);
                $entityManager->persist($config);
            }

            #recogemos la data del json

            $data = $request->getContent();

            #Bloque importante en el que validamos todo

            try {

                //DESERIALIZAMOS SOBRE EL OBJETO YA EXISTENTE Y USAMOS ABSTRACTNORMALIZER PARA NO VOLVER A CREAR SINO REEEMPLAZAR
                $serializer->deserialize($data, Configuracion::class, 'json', [
                    'groups' => 'configuracion:read',
                    AbstractNormalizer::OBJECT_TO_POPULATE => $config
                ]);

                # Ahora reemplazomos en la BD las entidades

                if ($config->getCalidad()){
                    $calidad = $this->getDoctrine()->getRepository(Calidad::class)->find($config->getCalidad()->getId());
                    if (!$calidad) throw new \Exception("Calidad no válida");
                    $config->setCalidad($calidad);
                }

                if($config->getIdioma()) {
                    $idioma = $this->getDoctrine()->getRepository(Idioma::class)->find($config->getIdioma()->getId());
                    if (!$idioma) throw new \Exception("Idioma no valido");
                    $config->setIdioma($idioma);
                }

                if($config->getTipoDescarga()){
                    $descarga = $this->getDoctrine()->getRepository(TipoDescarga::class)->find($config->getTipoDescarga()->getId());
                    if (!$descarga) throw new \Exception("El tipo de descarga no es válido");
                    $config->setIdioma($descarga);
                }

                #Guardamos tutto
                $entityManager->persist($config);

            }catch (\Exception $e){
                //Capturamos cualquier error que podamos tener
                return new Response(
                    json_encode(['error' => 'Error al actualizar la configuración: ' . $e->getMessage()]),
                    Response::HTTP_BAD_REQUEST,
                    ['Content-Type' => 'application/json']
                );

            }

            $dataResponse = $serializer->serialize($config, 'json', ['groups' => 'configuracion:read']);

            return new Response ($dataResponse, 201, ['Content-Type' => 'application/json']);
        }
        return new Response("Not allowed", 405);
    }

}


