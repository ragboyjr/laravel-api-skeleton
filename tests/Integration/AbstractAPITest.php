<?php

namespace App\Tests\Integration;

use App\Tests\TestCase;

abstract class AbstractAPITest extends TestCase
{
    public function json($method, $uri, array $data = [], array $headers = []) {
        return parent::json($method, $uri, $data, array_merge(array_filter([
            'Authorization' => 'Bearer ' . getenv('INTEGRATION_API_TOKEN'),
        ]), $headers));
    }

    protected function deleteRecord($table, $id) {
        $conn = $this->createApplication()['em']->getConnection();
        $conn->query("DELETE FROM $table WHERE id = $id");
    }

    protected function deleteEntity($entityName, $id) {
        $em = $this->app['em'];
        $meta = $em->getClassMetadata($entityName);
        $this->deleteRecord($meta->getTableName(), $id);
    }

    protected function withEntity($endpoint, $entityName, array $data, callable $fn, callable $getId = null) {
        $getId = $getId ?: function($resp) { return $resp->json('id'); };

        $resp = $this->json("POST", $endpoint, $data)->assertStatus(201);

        $e = null;
        try {
            $fn($resp->json(), $resp);
        } catch (\Throwable $e) {

        }

        $this->deleteEntity($entityName, $getId($resp));

        if ($e) {
            throw $e;
        }
    }

    protected function entity(array $data = []) {
        return array_merge([
            'field' => 'value'
        ], $data);
    }
}

