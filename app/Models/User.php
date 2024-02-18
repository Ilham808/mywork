<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    use HasFactory;
    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'token'
    ];

    protected $hidden = [
        'password',
    ];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, "user_id", "id");
    }

}
