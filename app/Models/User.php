<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Project;

class User extends Model implements Authenticatable
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
        'token'
    ];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, "user_id", "id");
    }


    public function getAuthIdentifierName()
    {
        return "name";
    }

    public function getAuthIdentifier(){
        return $this->name;
    }

    public function getAuthPassword(){
        return $this->password;
    }

    public function getRememberToken(){
        return $this->token;
    }

    public function setRememberToken($value){
        $this->token = $value;
    }

    public function getRememberTokenName(){
        return "token";
    }
}
