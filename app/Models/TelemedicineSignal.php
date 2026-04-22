<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelemedicineSignal extends Model
{
    protected $fillable = ['room_code', 'from_role', 'type', 'payload'];

    public static function forRoom(string $roomCode, string $forRole, int $afterId = 0)
    {
        $fromRole = $forRole === 'caller' ? 'callee' : 'caller';

        return static::where('room_code', $roomCode)
            ->where('from_role', $fromRole)
            ->where('id', '>', $afterId)
            ->orderBy('id')
            ->get();
    }

    public static function purgeRoom(string $roomCode): void
    {
        static::where('room_code', $roomCode)->delete();
    }
}
