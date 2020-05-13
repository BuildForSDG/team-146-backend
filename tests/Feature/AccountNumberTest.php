<?php

namespace Tests\Feature;

use http\Client\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AccountNumberTest extends TestCase
{
    /**
     * A feature test to test the generation of account numbers.
     *
     * @return Response /newAccountNumber
     */

    Public function testNewAccountNumberGeneration(){
        $response = $this->get('/api/v1/newAccountNumber');
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'Account_Number'
            ]);
    }
}
