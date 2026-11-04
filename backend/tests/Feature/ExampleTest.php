<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_raiz_redireciona_para_a_documentacao_da_api(): void
    {
        $this->get('/')->assertRedirect('/api/documentation');
    }
}
