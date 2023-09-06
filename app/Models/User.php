<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Role;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'username',
        'password',
        '_password',
        'role_id',
    ];

    protected $hidden = [
        'password', 
        '_password',
        'role_id'
    ];

    public function isAdmin() // Admin
    {
        if($this->role_id === 1)
            return true; 
        else 
            return false;
    }

    public function isZavSklad() // Omborxona mudiri
    {
        if($this->role_id === 2)
            return true; 
        else 
            return false;
    }

    public function isModerator() // Moderator
    {
        if($this->role_id === 3)
            return true; 
        else 
            return false;
    }

    public function isManager() // Savdo menejeri
    {
        if($this->role_id === 4)
            return true; 
        else 
            return false;
    }

    public function isChief() // Boshliq
    {
        if($this->role_id === 5)
            return true; 
        else 
            return false;   
    }

    public function isСashier() // Kassir
    {
        if($this->role_id === 6)
            return true; 
        else 
            return false;   
    }

    public function isAccountant() // Buxgalter
    {
        if($this->role_id === 7)
            return true; 
        else 
            return false;   
    }

    public function role()
    {
        $role = Role::find($this->role_id);
        return $role->name; 
    }
}
