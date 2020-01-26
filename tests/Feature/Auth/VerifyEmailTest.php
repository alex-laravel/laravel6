<?php

namespace Tests\Feature\Auth;

use App\Models\User\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class VerifyEmailTest extends TestCase
{
    use DatabaseTransactions;

    const ROUTE_AUTH_EMAIL_VERIFY = '/auth/email/verify';
    const ROUTE_AUTH_EMAIL_RESEND = '/auth/email/resend';
    const ROUTE_AUTH_LOGIN = '/auth/login';

    /**
     * @return void
     */
    public function testVerificationNoticeRoute()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get(self::ROUTE_AUTH_EMAIL_VERIFY);

        $response->assertStatus(200);
        $response->assertViewIs('auth.verify');
    }

    /**
     * @return void
     */
    public function testVerificationNoticeRouteIfNotAuthenticated()
    {
        $response = $this->get(self::ROUTE_AUTH_EMAIL_VERIFY);

        $response->assertStatus(302);
        $response->assertRedirect(self::ROUTE_AUTH_LOGIN);
        $response->assertSessionHasNoErrors();

        $this->assertGuest();
    }

    /**
     * @return void
     */
    public function testVerificationNoticeRouteIfEmailVerified()
    {
        $user = factory(User::class)->create([
            'email_verified_at' => now()
        ]);

        $response = $this->actingAs($user)->get(self::ROUTE_AUTH_EMAIL_VERIFY);

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $response->assertSessionHasNoErrors();

        $this->assertAuthenticatedAs($user);
    }

    /**
     * @return void
     */
    public function testVerificationVerify()
    {
        $user = factory(User::class)->create();

        $this->assertNull($user->email_verified_at);

        $response = $this->actingAs($user)->get($this->generateVerifyEmailLink($user->id, $user->email));

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $response->assertSessionHasNoErrors();

        $this->assertAuthenticatedAs($user);
        $this->assertNotNull($user->email_verified_at);
    }

    /**
     * @return void
     */
    public function testVerificationVerifyWithInvalidHash()
    {
        $user = factory(User::class)->create();

        $this->assertNull($user->email_verified_at);

        $response = $this->actingAs($user)->get($this->generateVerifyEmailLink($user->id, 'invalid-hash'));

        $response->assertStatus(403);
        $response->assertSessionHasNoErrors();

        $this->assertAuthenticatedAs($user);
        $this->assertNull($user->email_verified_at);
    }

    /**
     * @return void
     */
    public function testVerificationVerifyWithInvalidUserId()
    {
        $user = factory(User::class)->create();

        $this->assertNull($user->email_verified_at);

        $response = $this->actingAs($user)->get($this->generateVerifyEmailLink('invalid-id', $user->email));

        $response->assertStatus(403);
        $response->assertSessionHasNoErrors();

        $this->assertAuthenticatedAs($user);
        $this->assertNull($user->email_verified_at);
    }

    /**
     * @return void
     */
    public function testVerificationResendRoute()
    {
        Notification::fake();

        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post(self::ROUTE_AUTH_EMAIL_RESEND);

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $response->assertSessionHasNoErrors();

        $this->assertAuthenticatedAs($user);

        Notification::assertSentTo($user, VerifyEmailNotification::class);
    }

    /**
     * @return void
     */
    public function testVerificationResendRouteIfNotAuthenticated()
    {
        $response = $this->post(self::ROUTE_AUTH_EMAIL_RESEND);

        $response->assertStatus(302);
        $response->assertRedirect(self::ROUTE_AUTH_LOGIN);
        $response->assertSessionHasNoErrors();

        $this->assertGuest();
    }

    /**
     * @return void
     */
    public function testVerificationResendRouteIfVerified()
    {
        $user = factory(User::class)->create([
            'email_verified_at' => now()
        ]);

        $response = $this->actingAs($user)->post(self::ROUTE_AUTH_EMAIL_RESEND);

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $response->assertSessionHasNoErrors();

        $this->assertAuthenticatedAs($user);
    }

    /**
     * @param integer $userId
     * @param string $userEmail
     * @return string
     */
    private function generateVerifyEmailLink($userId, $userEmail)
    {
        return URL::temporarySignedRoute(
            'auth.verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $userId,
                'hash' => generateVerifyEmailHash($userId, $userEmail),
            ]
        );
    }
}
