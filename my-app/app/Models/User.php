<?php

// namespace App\Models;

// // use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Notifications\Notifiable;

// class User extends Authenticatable
// {
//     /** @use HasFactory<\Database\Factories\UserFactory> */
//     use HasFactory, Notifiable;

//     /**
//      * The attributes that are mass assignable.
//      *
//      * @var list<string>
//      */
//     protected $fillable = [
//         'name',
//         'email',
//         'password',
//     ];

//     /**
//      * The attributes that should be hidden for serialization.
//      *
//      * @var list<string>
//      */
//     protected $hidden = [
//         'password',
//         'remember_token',
//     ];

//     /**
//      * Get the attributes that should be cast.
//      *
//      * @return array<string, string>
//      */
//     protected function casts(): array
//     {
//         return [
//             'email_verified_at' => 'datetime',
//             'password' => 'hashed',
//         ];
//     }
//     public function wallet()
//     {
//         return $this->hasOne(Wallet::class);
//     }

//     public function cart()
//     {
//         return $this->hasOne(UserCart::class);
//     }

//     public function roles()
//     {
//         return $this->belongsToMany(Role::class, 'user_roles');
//     }

// }

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'auth_info_id',
        'user_info_id',
        'gmail_user_id',
        'status',
        'deleted_at',
    ];

    // ارتباط با Wallet (یک به یک)
    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    // ارتباط با UserCart (یک به یک)
    public function cart()
    {
        return $this->hasOne(UserCart::class);
    }

    // ارتباط با roles (چند به چند)
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    // ارتباط با UserChat (چند به یک)
    public function userChats()
    {
        return $this->hasMany(UserChat::class, 'user_sender_id');
    }

    // ارتباط با UserLogin (یک به چند)
    public function logins()
    {
        return $this->hasMany(UserLogin::class);
    }

    // ارتباط با UserResume (یک به یک)
    public function resume()
    {
        return $this->hasOne(UserResume::class);
    }

    // ارتباط با UserResumeCompanyRoleRequest (چند به چند)
    public function companyRoleRequests()
    {
        return $this->belongsToMany(CompanyRoleRequest::class, 'user_resume_company_role_request');
    }
}

