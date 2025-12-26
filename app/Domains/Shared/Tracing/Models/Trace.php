<?php

namespace App\Domains\Shared\Tracing\Models;

use App\Domains\Auth\Models\User;
use App\Domains\Complaint\Models\Complaint;
use App\Domains\Complaint\Models\ComplaintReply;
use App\Traits\HasUniqueCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trace extends Model
{
    use HasUniqueCode;
    protected $fillable = [
        'reference_number','span_id','action',
        'entity_type','entity_id',
        'user_id','route','method','ip','status_code',
        'meta','occurred_at'
    ];

    protected $casts = [
        'meta' => 'array',
        'occurred_at' => 'datetime',
    ];


    protected function getCodeColumn(): string
    {
        return 'reference_number';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function complaint(): BelongsTo
    {
        return $this->belongsTo(Complaint::class,'entity_id')
            ->where('entity_type','Complaint');
    }

    public function complaintReply(): BelongsTo
    {
        return $this->belongsTo(ComplaintReply::class, 'entity_id')
            ->where('entity_type', 'ComplaintReply');
    }

    protected function getCodePrefix(): string
    {
        return 'REF-'.now()->format('Y').'-';
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
