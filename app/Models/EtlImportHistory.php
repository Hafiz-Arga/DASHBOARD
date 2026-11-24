<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EtlImportHistory extends Model
{
    protected $table = 'etl_import_histories';

    protected $fillable = [
        'file_name',
        'import_method',
        'total_rows',
        'valid_rows',
        'invalid_rows',
        'loaded_rows',
        'status',
        'failed_stage',
        'error_message',
        'duration_seconds',
        'user_id',
    ];

    protected $casts = [
        'total_rows' => 'integer',
        'valid_rows' => 'integer',
        'invalid_rows' => 'integer',
        'loaded_rows' => 'integer',
        'duration_seconds' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
