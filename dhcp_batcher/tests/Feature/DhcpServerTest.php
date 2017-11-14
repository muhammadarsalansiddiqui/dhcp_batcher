<?php

namespace Tests\Feature;

use App\Http\Middleware\VerifyCsrfToken;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\DhcpServer;

class DhcpServerTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        $this->withoutMiddleware([VerifyCsrfToken::class]);
    }

    /**
     * @test
     */
    public function creating_a_dhcp_server_succeeds()
    {
        $user = factory(User::class)->create();

        $count = DhcpServer::count();

        $this->actingAs($user)
            ->post("/dhcp_servers",[
                'name' => 'Foo'
            ])->assertStatus(302)
            ->assertRedirect("/dhcp_servers");

        $this->assertCount($count+1, DhcpServer::all());
        $this->assertNotNull(DhcpServer::where('name','=','Foo')->first());
    }

    /**
     * @test
     */
    public function creating_a_dhcp_server_without_a_name_fails()
    {
        $user = factory(User::class)->create();

        $count = DhcpServer::count();

        $this->actingAs($user)
            ->post("/dhcp_servers",[
                'name' => null
            ])->assertStatus(302);

        $this->assertCount($count, DhcpServer::all());
        $this->assertNull(DhcpServer::where('name','=',null)->first());
    }
}
