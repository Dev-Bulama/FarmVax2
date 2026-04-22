<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TelemedicineRequest;
use App\Models\TelemedicineSignal;
use Illuminate\Http\Request;

class TelemedicineSignalController extends Controller
{
    /**
     * Post a WebRTC signal (offer, answer, ICE candidate, or hangup).
     */
    public function send(Request $request, string $roomCode)
    {
        $request->validate([
            'from_role' => 'required|in:caller,callee',
            'type'      => 'required|in:offer,answer,ice,hangup',
            'payload'   => 'required|string',
        ]);

        $this->authorizeRoom($roomCode);

        TelemedicineSignal::create([
            'room_code' => $roomCode,
            'from_role' => $request->from_role,
            'type'      => $request->type,
            'payload'   => $request->payload,
        ]);

        return response()->json(['ok' => true]);
    }

    /**
     * Poll for signals addressed to this role (signals sent by the opposite role).
     */
    public function poll(Request $request, string $roomCode)
    {
        $request->validate([
            'for_role' => 'required|in:caller,callee',
            'last_id'  => 'nullable|integer|min:0',
        ]);

        $this->authorizeRoom($roomCode);

        $signals = TelemedicineSignal::forRoom(
            $roomCode,
            $request->for_role,
            (int) $request->input('last_id', 0)
        );

        return response()->json([
            'signals' => $signals->map(fn($s) => [
                'id'        => $s->id,
                'type'      => $s->type,
                'from_role' => $s->from_role,
                'payload'   => $s->payload,
            ]),
        ]);
    }

    /**
     * Verify the authenticated user belongs to this telemedicine room.
     */
    private function authorizeRoom(string $roomCode): void
    {
        $req = TelemedicineRequest::where('room_code', $roomCode)->first();

        if (!$req) {
            abort(404, 'Room not found.');
        }

        $uid = auth()->id();

        if ($uid !== $req->requester_id && $uid !== $req->professional_id) {
            abort(403, 'Access denied.');
        }
    }
}
