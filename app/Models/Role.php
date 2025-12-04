<?php

namespace App\Models;

use App\Enums\ModeloStatus;
use App\Traits\LogsAllActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Role extends \Spatie\Permission\Models\Role
{
    use SoftDeletes;
    use LogsAllActivity;

    protected static ?string $modelLabel        = 'Papel';
    protected static ?string $pluralModelLabel  = 'Papéis';

    protected $fillable = [
        'panel_id',
        'name',
        'guard_name',

        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions', 'role_id', 'permission_id');
    }

    public static function getModelLabel(): string
    {
        return self::$modelLabel;
    }

    public static function getPluralLabel(): ?string
    {
        return self::$pluralModelLabel;
    }
}
