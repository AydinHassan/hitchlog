<?php

namespace Tests\Feature;

use App\Journey;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JourneyAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $name): User
    {
        return User::create([
            'name' => $name,
            'email' => strtolower($name) . '@example.com',
            'password' => bcrypt('secret'),
        ]);
    }

    private function makeJourney(User $owner): Journey
    {
        return $owner->journeys()->create([
            'start_location' => 'London',
            'end_location' => 'Paris',
            'date' => '2020-01-01',
            'start_time' => '09:00',
            'end_time' => '17:00',
        ]);
    }

    private function payload(): array
    {
        return [
            'start_location' => 'Berlin',
            'end_location' => 'Prague',
            'date' => '2020-02-02',
            'start_time' => '08:00',
            'end_time' => '12:00',
        ];
    }

    public function testRegistrationIsDisabled()
    {
        $this->get('/register')->assertNotFound();
        $this->post('/register', [
            'name' => 'Mallory',
            'email' => 'mallory@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertNotFound();

        $this->assertDatabaseMissing('users', ['email' => 'mallory@example.com']);
    }

    public function testNonOwnerCannotUpdateJourney()
    {
        $journey = $this->makeJourney($this->makeUser('Owner'));

        $this->actingAs($this->makeUser('Mallory'))
            ->putJson("/journey/{$journey->id}", $this->payload())
            ->assertForbidden();

        $this->assertDatabaseHas('journeys', ['id' => $journey->id, 'start_location' => 'London']);
    }

    public function testNonOwnerCannotDeleteJourney()
    {
        $journey = $this->makeJourney($this->makeUser('Owner'));

        $this->actingAs($this->makeUser('Mallory'))
            ->deleteJson("/journey/{$journey->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('journeys', ['id' => $journey->id]);
    }

    public function testOwnerCanUpdateJourney()
    {
        $owner = $this->makeUser('Owner');
        $journey = $this->makeJourney($owner);

        $this->actingAs($owner)
            ->putJson("/journey/{$journey->id}", $this->payload())
            ->assertOk()
            ->assertJson(['start_location' => 'Berlin', 'user' => ['name' => 'Owner']]);

        $this->assertDatabaseHas('journeys', ['id' => $journey->id, 'start_location' => 'Berlin']);
    }

    public function testOwnerCanDeleteJourney()
    {
        $owner = $this->makeUser('Owner');
        $journey = $this->makeJourney($owner);

        $this->actingAs($owner)
            ->deleteJson("/journey/{$journey->id}")
            ->assertOk();

        $this->assertDatabaseMissing('journeys', ['id' => $journey->id]);
    }

    public function testIndexDoesNotExposeEmails()
    {
        $owner = $this->makeUser('Owner');
        $this->makeJourney($owner);

        $response = $this->actingAs($this->makeUser('Viewer'))
            ->getJson('/journey')
            ->assertOk()
            ->assertJsonPath('0.user.name', 'Owner');

        $this->assertStringNotContainsString('@example.com', $response->getContent());
        $this->assertArrayNotHasKey('email', $response->json('0.user'));
    }
}
