<?php

namespace App\Models;

use App\Traits\LogsAllActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Modelo extends AbstractModel
{
    use SoftDeletes;
    use LogsAllActivity;

    protected $table = 'modelos';

    protected $fillable = [
        'nome',
        'status',

        'created_by',
        'updated_by',
        'deleted_by',

        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'cores'      => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function cores(): HasMany
    {
        return $this->hasMany(Cor::class, 'modelos_id');
    }
}
