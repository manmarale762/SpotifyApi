<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Cancion;
use App\Entity\Playlist;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PlaylistController extends AbstractController
{


    public function playlists(Request $request, SerializerInterface $serializer): Response
    {
        $id = $request->get('id');
        $user = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['id'=>$id]);

        #No user, no nada JAJAJAJA
        if (!$user) {
            return new Response(json_encode(['error' => 'Usuario no encontrado']), 404);
        }

        if($request->isMethod("GET")){
            $playlists = $this->getDoctrine()->getRepository(Cancion::class)->findBy(['usuario'=>$id]);

            if (!$playlists) {
                return new Response(json_encode(['error' => 'El usuario no tiene playlists']), 404);
            }

            $data = $serializer->serialize($playlists, 'json', ['groups' => 'playlists:read']);

            return new Response ($data, 200, ['Content-Type' => 'application/json']);
        }

        if($request->isMethod("POST")){
            $entityManager = $this->getDoctrine()->getManager();
            $data = $request->getContent();

            try {

                $playlist = $serializer->deserialize($data, Playlist::class, 'json',['groups' => 'playlist:write']);

                $playlist->setUsuario($user);
                $playlist->setNumeroCanciones(0); // Para que este a 0 por defecto
                $playlist->setFechaCreacion(new \DateTime());

                #Tenemos que persistir porque es nueva
                $entityManager->persist($playlist);

                #Y ahora guardamops
                $entityManager->flush();


            }catch (\Exception $e) {
                // Capturamos fallos (por ejemplo, si el JSON venía vacío o corrupto)
                return new Response(
                    json_encode(['error' => 'No se pudo crear la playlist. Revisa los datos enviados.']),
                    Response::HTTP_BAD_REQUEST,
                    ['Content-Type' => 'application/json']
                );
            }
            $dataJson = $serializer->serialize($playlist, 'json', ['groups' => 'playlist:read']);

            return new Response($dataJson, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
        }
        return new Response("Not allowed", 405);
    }


    public function playlistDetail(Request $request, SerializerInterface $serializer): Response
    {
        if($request->isMethod("GET")){
            $idPlaylist = $request->get('id');
            $playlist = $this->getDoctrine()->getRepository(Cancion::class)->findOneBy(['id'=> $idPlaylist]);

            if (!$playlist){
                return new Response(json_encode(['error' => 'Playlist no encontrada']), 404);
            }

            $data = $serializer->serialize($playlist, 'json', ['groups' => 'playlist:read']);

            return new Response($data,Response::HTTP_OK,['Content-Type'=>'application/json']);

        }

        return new Response("Not allowed", 405);
    }



}
