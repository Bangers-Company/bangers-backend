<?php

namespace App\Exceptions;

use Exception;

class GroupMembershipException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'message' => $this->getMessage() ?: 'Group membership error',
            'error_code' => 'GROUP_MEMBERSHIP_ERROR'
        ], 403);
    }
}
