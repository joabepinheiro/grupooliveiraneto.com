<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

abstract class AbstractModel extends Model
{
    use HasFactory, SoftDeletes;

    public static string $prefixId;

    public $timestamps = true;

    protected static ?string $modelLabel = '';

    protected static ?string $pluralModelLabel = '';

    protected $fillable = [
        'created_by',
        'updated_by',
        'deleted_by',

        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    /**
     * Define eventos para preencher os campos de auditoria automaticamente.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });

        static::deleting(function ($model) {
            if (Auth::check()) {
                $model->deleted_by = Auth::id();
                $model->saveQuietly(); // Evita loop infinito no evento
            }
        });

        static::forceDeleted(function ($model) {
            $model->deleted_by = Auth::id();
            $model->saveQuietly();
        });
    }


    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /**
     * Retorna o usuário que atualizou o registro.
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Retorna o usuário que deletou o registro.
     */
    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function logs(): MorphMany
    {
        return $this->morphMany(ActivityLog::class, 'subject');
    }

    public static function getModelLabel(): string
    {
        return static::$modelLabel;
    }

    public static function getPluralLabel(): ?string
    {
        return static::$pluralModelLabel;
    }
}
