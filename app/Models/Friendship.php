<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Friendship extends Model
{
    use HasFactory;

    protected $table = 'friendships';
    public $incrementing = false;
    protected $primaryKey = ['user_id_1', 'user_id_2'];

    protected $fillable = [
        'user_id_1',
        'user_id_2',
        'status',
        'requested_by',
    ];

    /**
     * Relationship to the first user.
     */
    public function user1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_1');
    }

    /**
     * Relationship to the second user.
     */
    public function user2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_2');
    }

    /**
     * Relationship to the user who requested the friendship.
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /**
     * Helper to get the other user in the relationship.
     */
    public function getFriendOf($userId): ?User
    {
        if ((string)$this->user_id_1 === (string)$userId) {
            return $this->user2;
        }
        if ((string)$this->user_id_2 === (string)$userId) {
            return $this->user1;
        }
        return null;
    }
}
