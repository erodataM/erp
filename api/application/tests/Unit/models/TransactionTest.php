<?php

declare(strict_types=1);

namespace Tests\Unit;


use Transaction;

class TransactionTest extends AbstractUnitTest
{
    public function testValidationProviderOrClient(): void
    {
        $transaction = new Transaction();
        $validate = $transaction->validation();

        $this->assertEquals(false, $validate);
    }

    public function testValidationProviderAndClient(): void
    {
        $transaction = new Transaction();
        $transaction->provider_id = 1;
        $transaction->client_id = 1;
        $validate = $transaction->validation();

        $this->assertEquals(false, $validate);
    }
}