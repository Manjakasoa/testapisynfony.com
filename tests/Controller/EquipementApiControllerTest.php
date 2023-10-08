<?php


namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EquipementApiControllerTest extends WebTestCase
{
    public function testGetResource()
    {
        $client = static::createClient();

        $client->request('GET', '/api/equipement/1'); // Endpoint de votre API
        $response = $client->getResponse()->getContent();
        $data = json_decode($response,true);
        $this->assertResponseIsSuccessful();
        $this->assertJson($response);
        $this->assertEquals('Iphone 8',$data['name']);
    }

    public function testputResource()
    {
        $client = static::createClient();

        // Simule une requête PUT pour mettre à jour une ressource avec l'ID 1
        $client->request('PUT', '/api/equipement/1', [], [], [], json_encode(['name' => 'Iphone 8']),);
        $response = $client->getResponse()->getContent();
        $data = json_decode($response,true);
        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
        $this->assertEquals('Iphone 8',$data['name']);
    }
    public function testCreateResource()
    {
        $client = static::createClient();

        // Simule une requête POST pour créer une nouvelle ressource
        $client->request('POST', '/api/equipement', [], [], [], json_encode(['name' => 'Mac','category' => 'ordinateur','number' => '12344']),);

        $this->assertResponseStatusCodeSame(201); // Vérifie que la réponse a le code HTTP 201 (Created)
        $this->assertResponseHeaderSame('Content-Type', 'application/json'); // Vérifie le type de contenu de la réponse
        $this->assertJson($client->getResponse()->getContent()); // Vérifie que la réponse est au format JSON
    }
    public function testDeleteResource()
    {
        $client = static::createClient();

        // Simule une requête DELETE pour supprimer une ressource avec l'ID 1
        $client->request('DELETE', '/api/equipement/1');

        $this->assertResponseStatusCodeSame(204); // Vérifie que la réponse a le code HTTP 204 (No Content)
    }
}
