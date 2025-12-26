<?php

namespace App\Domains\Auth\Models;

use App\Domains\Complaint\Models\Complaint;
use App\Domains\Shared\Tracing\Models\Trace;
use App\Traits\HasUniqueCode;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable ,HasApiTokens , SoftDeletes , HasRoles, HasUniqueCode;

    protected $guard_name = 'web';
    /**

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }
    protected static function newFactory()
    {
        return UserFactory::new();
    }

    public function traces(): HasMany
    {
        return $this->hasMany(Trace::class);
    }

    protected function getCodeColumn(): string
    {
        return 'reference_number';
    }

    protected function getCodePrefix(): string
    {
        return 'US-';
    }

    protected function getCodePadding(): int
    {
        return 6;
    }

}
