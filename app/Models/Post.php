<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\Comment;
#[Fillable(['title', 'subject', 'body', 'slug', 'user_id'])]
#[Hidden(['user_id'])]
class Post extends Model
{
    use HasApiTokens;
    protected $table = 'post';

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function comment(): BelongsTo{
        return $this->belongsTo(Comment::class);
    }

    

   
}
