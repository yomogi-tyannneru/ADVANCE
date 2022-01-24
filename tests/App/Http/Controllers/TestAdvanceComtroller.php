<?php

namespace Tests\App\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use App\Models\User;

class TestAdvanceController extends TestCase
{
  /**
   * 基本的なテスト例
   *
   * @return void
   */
  public function test_index()
  {
    $response = $this
      ->actingAs(User::factory()->create()) // 追加
      ->get(route('home'));


    $response->assertStatus(200)
      ->assertViewIs('home')
      ->assertSee('You are logged in!');
  }
}
