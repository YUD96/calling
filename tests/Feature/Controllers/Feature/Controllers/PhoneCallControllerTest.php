<?php

namespace Tests\Feature\Controllers\Feature\Controllers;

use App\Enums\PhoneCallStatus;
use App\Models\PhoneCall;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PhoneCallControllerTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    public function test_電話をかけることができる(): void
    {
        // given
        $now = CarbonImmutable::now();
        CarbonImmutable::setTestNow($now);

        /** @var User $caller */
        $caller = User::factory()->create();
        $this->actingAs($caller);

        /** @var User $receiver */
        $receiver = User::factory()->create();

        // when
        $actual = $this->postJson('/api/phone_calls', [
            'user_id' => $receiver->id,
        ]);
        // then
        $actual->assertCreated();

        $this->assertDatabaseHas(PhoneCall::class, [
            'caller_user_id' => $caller->id,
            'receiver_user_id' => $receiver->id,
            'status' => PhoneCallStatus::WaitingReceiver->value,
            'called_at' => $now,
        ]);
    }

    public function test_電話をキャンセルすることができる(): void
    {
        // given
        /** @var PhoneCall $phoneCall */
        $phoneCall = PhoneCall::factory()->create();
        $now = CarbonImmutable::now();
        CarbonImmutable::setTestNow($now);

        // when
        $actual = $this->postJson("/api/phone_calls/{$phoneCall->id}/cancel");

        // then
        $actual->assertNoContent();

        $this->assertDatabaseHas(PhoneCall::class, [
            'id' => $phoneCall->id,
            'status' => PhoneCallStatus::Cancelled,
            'finished_at' => $now,
        ]);
    }

    public function test_電話を受けることができる(): void
    {
        // given
        $now = CarbonImmutable::now();
        CarbonImmutable::setTestNow($now);

        /** @var PhoneCall $phoneCall */
        $phoneCall = PhoneCall::factory()->create();

        // when
        $actual = $this->postJson("/api/phone_calls/{$phoneCall->id}/receive");

        // then
        $actual->assertNoContent();

        $this->assertDatabaseHas(PhoneCall::class, [
            'id' => $phoneCall->id,
            'status' => PhoneCallStatus::TalkStated,
            'talk_started_at' => $now,
        ]);
    }

    public function test_通話を終了することができる(): void
    {
        // given
        $now = CarbonImmutable::now();
        CarbonImmutable::setTestNow($now);

        /** @var PhoneCall $phoneCall */
        $phoneCall = PhoneCall::factory()->create();

        // when
        $actual = $this->postJson("/api/phone_calls/{$phoneCall->id}/finish");

        // then
        $actual->assertNoContent();

        $this->assertDatabaseHas(PhoneCall::class, [
            'id' => $phoneCall->id,
            'status' => PhoneCallStatus::Finished,
            'finished_at' => $now,
            'call_charge' => 100,
        ]);
    }
}
