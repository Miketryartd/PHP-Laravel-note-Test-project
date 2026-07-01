<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Iluminate\Databbase\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;

use App\Models\Post;
use App\Models\User;
use Laravel\Sanctum\HasApiTokens;
#[Fillable(["comment", "post_id", "user_id"])]
#[Hidden(["post_id", "user_id"])]
class Comment extends Model
{
    
   use HasApiTokens;
   protected $table = 'comments';

   public function post(): BelongsTo {
    return $this->belongsTo(Post::class);
   }

   public function user(): BelongsTo{
    return $this->belongsTo(User::class);
   }
}
