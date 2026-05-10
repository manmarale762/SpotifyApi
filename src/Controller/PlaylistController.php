<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\AnyadeCancionPlaylist;
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
            $playlists = $this->getDoctrine()->getRepository(Playlist::class)->findBy(['usuario'=>$id]);

            if (!$playlists) {
                return new Response(json_encode(['error' => 'El usuario no tiene playlists']), 404);
            }

            $data = $serializer->serialize($playlists, 'json', ['groups' => 'playlist:read']);

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
            $playlist = $this->getDoctrine()->getRepository(Playlist::class)->findOneBy(['id'=> $idPlaylist]);

            if (!$playlist){
                return new Response(json_encode(['error' => 'Playlist no encontrada']), 404);
            }

            $data = $serializer->serialize($playlist, 'json', ['groups' => 'playlistDetalles:read']);

            return new Response($data,Response::HTTP_OK,['Content-Type'=>'application/json']);

        }

        return new Response("Not allowed", 405);
    }


    public function cancionesPlaylist(Request $request, SerializerInterface $serializer): Response
    {
        $idPlaylist = $request->get('id');

        $playlist = $this->getDoctrine()->getRepository(Playlist::class)->findOneBy(['id'=> $idPlaylist]);

        if (!$playlist) {
            return new Response(json_encode(['error' => 'Playlist no encontrada']), 404);
        }

        #CANCIONES DE UNA PLAYLIST
        if($request->isMethod("GET")){

            $relaciones = $this->getDoctrine()->getRepository(AnyadeCancionPlaylist::class)->findBy(['playlist'=>$playlist]);


            $resultado = [];
            foreach ($relaciones as $rel) {
                $resultado1 = $rel->getCancion();
                $resultado2 = $rel->getFechaAnyadida();
                $resultado3 = $rel->getUsuario();

                $resultado = array_merge($resultado1,$resultado2,$resultado3);
            }

            $data = $serializer->serialize($resultado, 'json', ['groups' => 'playlist:read']);

            return new Response($data,Response::HTTP_OK,['Content-Type'=>'application/json']);

        }

        if($request->isMethod("POST")){

            $content = json_decode($request->getContent(), true);
            $em = $this->getDoctrine()->getManager();
            $cancionId = $content['cancion_id'] ?? null;
            $usuarioId = $content['usuario_id'] ?? null;

            #Traemos la info para la relacion

            $playlist = $this->getDoctrine()->getRepository(Playlist::class)->findOneBy(['id'=> $idPlaylist]);
            $cancion = $this->getDoctrine()->getRepository(Cancion::class)->find($cancionId);
            $usuario = $this->getDoctrine()->getRepository(Usuario::class)->find($usuarioId);

            if (!$playlist || !$cancion || !$usuario) {
                return new Response(json_encode(['error' => 'Playlist, Canción o Usuario no encontrado']), 404);
            }

            // 3. Crear la relación en la tabla intermedia
            $relacion = new AnyadeCancionPlaylist();
            $relacion->setPlaylist($playlist);
            $relacion->setCancion($cancion);
            $relacion->setUsuario($usuario);
            $relacion->setFechaAnyadida(new \DateTime()); // Fecha actual


            if (method_exists($playlist, 'getNumeroCanciones') && method_exists($playlist, 'setNumeroCanciones')) {
                $playlist->setNumeroCanciones(((int)($playlist->getNumeroCanciones() ?? 0)) + 1);
            }

            $repo = $em->getRepository(AnyadeCancionPlaylist::class);
            $exists = $repo->findOneBy([
                'playlist' => $playlist,
                'cancion' => $cancion,
                'usuario' => $usuario,
            ]);

            if ($exists) {
                return new Response(json_encode(['error' => 'La canción ya está en la playlist']), 409, ['Content-Type' => 'application/json']);
            }


            $em->persist($relacion);
            $em->flush();

            // 5. Devolver la relación recién creada usando tus grupos
            $resultado = $serializer->serialize($relacion, 'json', ['groups' => 'playlist:read']);

            return new Response($resultado, 201, ['Content-Type' => 'application/json']);

        }

        if($request->isMethod("DELETE")){

            $idCancion = $request->get('cancionId');

            $em = $this->getDoctrine()->getManager();

            // Buscamos el registro en la tabla intermedia
            $registro = $em->getRepository(AnyadeCancionPlaylist::class)->findOneBy([
                'playlist' => $idPlaylist,
                'cancion' => $idCancion
            ]);

            if (!$registro) {
                return new Response(json_encode(['error' => 'La canción no pertenece a esta playlist']), 404);
            }

            $playlist = $registro->getPlaylist();

            $em->remove($registro);

            // Decrementamos el contador
            if ($playlist->getNumeroCanciones() > 0) {
                $playlist->setNumeroCanciones($playlist->getNumeroCanciones() - 1);
            }

            $em->flush();

            return new Response(json_encode(['status' => 'Cancion eliminada de la playlist']), 200, ['Content-Type' => 'application/json']);
        }


        return new Response("Not allowed", 405);
    }

}
