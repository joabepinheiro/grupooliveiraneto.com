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

class Permission extends \Spatie\Permission\Models\Permission
{
    use SoftDeletes;
    use LogsAllActivity;

    protected static ?string $modelLabel        = 'Permissão';
    protected static ?string $pluralModelLabel  = 'Permissões';

    protected $fillable = [
        'panel_id',
        'descricao',
        'resource',
        'methods',
        'page',
        'tipo',
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

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_has_permissions', 'permission_id', 'role_id', );
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
