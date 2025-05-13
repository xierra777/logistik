<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class jobContainer extends Model
{
    protected $casts = ['containers' => 'array'];

    protected $fillable = [
        'id_job',
        'containers'
    ];

    public function job()
    {
        return $this->belongsTo(TJob::class, 'id_job');
    }
}
