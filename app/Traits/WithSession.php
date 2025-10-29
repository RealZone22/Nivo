<?php

namespace App\Traits;

use App\Models\Session;

trait WithSession
{
    public function getAllSessions()
    {
        return Session::where('user_id', auth()->id())->get();
    }

    public function deleteSession($sessionId)
    {
        return Session::where('id', $sessionId)->where('user_id', auth()->id())->delete();
    }

    public function revokeOtherSessions()
    {
        Session::where('user_id', auth()->id())->whereNotIn('id', [session()->id()])->delete();
    }
}
