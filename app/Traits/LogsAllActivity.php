<?php

namespace App\Traits;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;

trait LogsAllActivity
{
    public static function bootLogsAllActivity()
    {
        static::creating(fn ($model) => self::setUserStamps($model, 'created_by'));

        static::updating(fn ($model) => self::setUserStamps($model, 'updated_by'));

        static::deleting(fn ($model) => self::setUserStamps($model, 'deleted_by', true));

        static::created(fn (Model $model) => self::logActivity($model, 'created', 'Registro criado', ['attributes' => $model->getAttributes()]));

        static::updated(fn (Model $model) => self::logActivity($model, 'updated', 'Registro atualizado', ['old' => $model->getOriginal(), 'new' => $model->getDirty()]));

        static::deleted(fn (Model $model) => self::logActivity($model, 'deleted', 'Registro excluído', ['attributes' => $model->getAttributes()]));

        static::forceDeleting(fn (Model $model) => self::logActivity($model, 'force_deleted', 'Registro excluído permanentemente', ['attributes' => $model->getAttributes()]));

        static::restoring(fn (Model $model) => self::logActivity($model, 'restore', 'Registro restaurado', ['attributes' => $model->getAttributes()]));
    }

    protected static function setUserStamps(Model $model, string $column, bool $saveQuietly = false): void
    {
        if (Auth::check()) {
            $model->{$column} = Auth::id();

            if ($saveQuietly) {
                $model->saveQuietly();
            }
        }
    }

    protected static function logActivity(Model $model, string $event, string $message, array $additionalProperties = []): void
    {
        $user           = Auth::user();
        $agent          = new Agent();

        $properties = array_merge([
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'browser'    => $agent->browser(),
            'platform'   => $agent->platform(),
            'device'     => $agent->device(),
        ], $additionalProperties);

        activity()
            ->performedOn($model)
            ->event($event)
            ->causedBy($user)
            ->withProperties($properties)
            ->log($message);
    }
}
