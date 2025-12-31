<?php

namespace App\Domains\Complaint\Models;

use App\Domains\Auth\Models\User;
use App\Domains\Shared\Tracing\Models\Trace;
use App\Traits\HasUniqueCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Complaint extends Model
{
    use HasUniqueCode,Notifiable;

    protected $table = 'complaints';

    protected $fillable = ['title', 'description', 'user_id','is_read', 'status','response' , 'assigned_to', 'version'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(ComplaintReply::class);
    }

    public function traces(): HasMany
    {
        return $this->hasMany(Trace::class, 'entity_id')
            ->where('entity_type', 'Complaint');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(ComplaintHistory::class);
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
