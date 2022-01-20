<?php

namespace Tests\App\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class TestAdvanceController extends TestCase
{
  /**
   * 基本的なテスト例
   *
   * @return void
   */
  public function test_index()
  {
    $response = $this->get('/');

    $response->assertStatus(200);
  }
}
