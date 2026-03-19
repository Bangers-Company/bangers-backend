use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can fetch feature flags', function () {
    $user = User::factory()->create();
    $response = $this->actingAs($user)->getJson('/api/mobile/config/features');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'flags' => [
                'enable_biometric_login',
                'enable_new_event_cards',
                'enable_group_timetables',
            ]
        ]);
});
