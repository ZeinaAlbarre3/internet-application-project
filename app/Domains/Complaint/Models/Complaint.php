<?php

namespace App\Domains\Complaint\Models;

use App\Domains\Auth\Models\User;
use App\Traits\HasUniqueCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Complaint extends Model
{
    use HasUniqueCode;

    protected $table = 'complaints';

    protected $fillable = ['title', 'description', 'user_id','is_read', 'status','response' , 'assigned_to', 'version'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(ComplaintReply::class);
    }
    protected function getCodeColumn(): string
    {
        return 'reference_number';
    }

    protected function getCodePrefix(): string
    {
        return 'CM-';
    }

    protected function getCodePadding(): int
    {
        return 6;
    }

    public function getRouteKeyName(): string
    {
        return 'reference_number';
    }

}
