<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Equipement;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Tools\ValidatorError;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use App\Repository\EquipementRepository;

class EquipementController extends AbstractController
{
    /**
     * @Route("/api/equipement", name="create_equipement", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $em,ValidatorInterface $validator): JsonResponse
    {
        $equipement = $serializer->deserialize($request->getContent(), Equipement::class, 'json');
        if(count($validator->validate($equipement)))
            return new JsonResponse(ValidatorError::getResponseError($validator->validate($equipement)), Response::HTTP_BAD_REQUEST);
        $em->persist($equipement);
        $em->flush();
        $jsonEquipement = $serializer->serialize($equipement, 'json');

        return new JsonResponse(json_decode($jsonEquipement), Response::HTTP_CREATED);
    }

     /**
     * @Route("/api/equipement/{id}", name="get_equipement", methods={"GET"})
     */
    public function getDetail(Equipement $equipement, SerializerInterface $serializer): JsonResponse 
    {
        $jsonEquipement = $serializer->serialize($equipement, 'json');
        return new JsonResponse($jsonEquipement, Response::HTTP_OK, [], true);
    }
    /**
     * @Route("/api/equipements", name="get_all_equipement", methods={"GET"})
     */
    public function getAll(EquipementRepository $equipementRepository, SerializerInterface $serializer): JsonResponse
    {
        $equipementList = $equipementRepository->findAll();

        $jsonEquipementList = $serializer->serialize($equipementList, 'json');
        return new JsonResponse($jsonEquipementList, Response::HTTP_OK, [], true);
    }
    /**
     * @Route("/api/equipement/{id}", name="delete_equipement", methods={"DELETE"})
     */
    public function delete(Equipement $equipement, EntityManagerInterface $em): JsonResponse 
    {
        $em->remove($equipement);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);

    }
   /**
     * @Route("/api/equipement/{id}", name="update_equipement", methods={"PUT"})
     */

    public function update(Request $request, SerializerInterface $serializer, Equipement $equipement, EntityManagerInterface $em,ValidatorInterface $validator): JsonResponse 
    {
        $updatedEquipement = $serializer->deserialize($request->getContent(), 
                Equipement::class, 
                'json', 
                [AbstractNormalizer::OBJECT_TO_POPULATE => $equipement]);
         if(count($validator->validate($updatedEquipement)))
            return new JsonResponse(ValidatorError::getResponseError($validator->validate($updatedEquipement)), Response::HTTP_BAD_REQUEST);
        $em->persist($updatedEquipement);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
   }
}
