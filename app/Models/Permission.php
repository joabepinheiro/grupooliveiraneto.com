<?php

namespace App\Models;

use App\Enums\ModeloStatus;
use App\Traits\LogsAllActivity;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

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

    public static function createCustomPermissions(string $titulo, string $panel, array $roles): void
    {
        // Gera o nome padronizado da permissão
        $permissionName = "{$panel}::" . Str::slug($titulo);

        // Cria ou atualiza a permissão de forma enxuta
        $permission = Permission::updateOrCreate(
            ['name' => $permissionName],
            [
                'panel_id'   => $panel,
                'titulo'     => $titulo,
                'descricao'  => $titulo,
                'tipo'       => 'custom',
                'guard_name' => 'web',
            ]
        );

        // Busca todas as roles de uma vez (elimina loop de queries)
        $funcoes = Role::query()
            ->whereIn('name', $roles)
            ->where('panel_id', $panel) // <-- antes estava procurando 'panel', literalmente
            ->pluck('id')
            ->toArray();

        // Faz sync sem remover permissões existentes
        $permission->roles()->syncWithoutDetaching($funcoes);
    }

    public static function can($titulo): string
    {
        return auth()->user()->can(
            Filament::getCurrentPanel()?->getId().'::'.Str::slug($titulo)
        );
    }


}
