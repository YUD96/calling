<?php

namespace App\Http\Controllers;

use App\Enums\PhoneCallStatus;
use App\Http\Requests\PhoneCallControllerRequest;
use App\Models\PhoneCall;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class PhoneCallController extends Controller
{
    public function store(PhoneCallControllerRequest $request): JsonResponse
    {
        /** @var PhoneCall $phoneCall */
        $phoneCall = DB::transaction(function () use ($request) {
            /** @var User $caller */
            $caller = Auth::user();
            /** @var User $receiver */
            $receiver = User::query()->find($request->user_id);

            $phoneCall = new PhoneCall();
            $phoneCall->caller_user_id = $caller->id;
            $phoneCall->receiver_user_id = $receiver->id;
            $phoneCall->status = PhoneCallStatus::WaitingReceiver;
            $phoneCall->called_at = CarbonImmutable::now();
            $phoneCall->save();

            return $phoneCall;
        });

        return response()->json([
            'data' => [
                'phone_call_id' => $phoneCall->id,
            ],
        ], Response::HTTP_CREATED);
    }

    public function cancel(PhoneCall $phoneCall): JsonResponse
    {
        DB::transaction(function () use ($phoneCall) {
            $phoneCall->status = PhoneCallStatus::Cancelled;
            $phoneCall->finished_at = CarbonImmutable::now();
            $phoneCall->save();
        });

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    public function receive(PhoneCall $phoneCall): JsonResponse
    {
        DB::transaction(function () use ($phoneCall) {
            $phoneCall->status = PhoneCallStatus::TalkStated;
            $phoneCall->talk_started_at = CarbonImmutable::now();
            $phoneCall->save();
        });

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    public function finish(PhoneCall $phoneCall): JsonResponse
    {
        DB::transaction(function () use ($phoneCall) {
            $phoneCall->status = PhoneCallStatus::Finished;
            $phoneCall->finished_at = CarbonImmutable::now();
            $phoneCall->call_charge = 100;
            $phoneCall->save();
        });

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
