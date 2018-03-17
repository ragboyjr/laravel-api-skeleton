<?php

use Illuminate\Database\Seeder;
use Doctrine\ORM\EntityManagerInterface;

class OAuthSeeder extends Seeder
{
    private $clientId;
    private $clientSecret;

    public function __construct() {
        $this->clientId = getenv("OAUTH_CLIENT_ID");
        $this->clientSecret = getenv("OAUTH_CLIENT_SECRET");
    }

    public function run(EntityManagerInterface $em) {
        $scopes = [
            new Scope('basic', 'Basic', 'Allows basic access to the API.'),
            new Scope('user', 'User', 'Allows user access to the API.'),
        ];

        $client = new Client($this->clientId, $this->clientId, $this->clientSecret, '', $scopes);
        $em->persist($client);
        foreach ($scopes as $scope) {
            $em->persist($scope);
        }
        $em->flush();
    }
}
