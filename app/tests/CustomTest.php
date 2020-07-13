<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class CustomTest extends TestCase
{
    /**
     * @return array
     */
    public function testToken()
    {
        $user = [
            'name'    => 'Test',
            'surname' => 'Unit',
            'email'   => 'unit-test@unit-test.test4'
        ];

        $event_id = 4;

        $context = $this->json('GET', '/access-api-key/901865c5054987fac6b59611e633d76b');
        $context->assertResponseStatus(200);
        $context->seeJsonStructure([
            'http_code',
            'error',
            'data' => [
                'api_key',
                'expired'
            ]
        ]);

        $access_api_key = json_decode($this->response->getContent(), true)['data']['api_key'];
        return ['access_api_key' => $access_api_key, 'user' => $user, 'event_id' => $event_id];
    }

    /**
     * @depends testToken
     *
     * @param array $data
     *
     * @return array
     */
    public function testCreate(array $data)
    {
        $context_event = $this->json('POST', '/api/events/'. $data['event_id'] .'/user/create', $data['user'], ['x-api-key' => $data['access_api_key']]);
        $context_event->assertResponseStatus(200);

        $response = json_decode($this->response->getContent(), true);
        $assertKey = array_intersect_key($response['data'] ?? [], array_flip(['id', 'user_id', 'event_id']));
        $this->assertTrue(!empty($assertKey) && count($assertKey) == 3);

        $data['user_id'] = $response['data']['user_id'] ?? 0;

        $this->assertLessThanOrEqual($data['user_id'], 0);

        return $data;
    }

    /**
     * @depends testCreate
     *
     * @param array $data
     *
     * @return array
     */
    public function testFilter(array $data){
        $context_list = $this->json('GET', '/api/events/'. $data['event_id'] .'/user/list', ['x-api-key' => $data['access_api_key']]);
        $context_list->assertResponseStatus(200);
        $context_list->seeJsonStructure([
            'http_code',
            'error',
            'data' => [
                '*' => [
                    'event_id',
                    'event_name',
                    'event_city_id',
                    'event_city_name',
                    'event_date_begin',
                    'users' => [
                        '*' => [
                            'user_id',
                            'user_name',
                            'user_surname'
                        ]
                    ]
                ]
            ]
        ]);

        return $data;
    }

    /**
     * @depends testFilter
     *
     * @param array $data
     */
    public function testDelete(array $data){
        $context_delete = $this->json('DELETE', '/api/events/'. $data['event_id'] .'/user/' . $data['user_id'] . '/delete', [], ['x-api-key' => $data['access_api_key']]);
        $context_delete->assertResponseStatus(200);
        $context_delete->seeJsonStructure([
            'http_code',
            'error',
            'data' => [
                'is_delete'
            ]
        ]);
    }
}
