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

class Media extends \Spatie\MediaLibrary\MediaCollections\Models\Media
{
    use SoftDeletes;
    use LogsAllActivity;

    protected static ?string $modelLabel        = 'Mídia';
    protected static ?string $pluralModelLabel  = 'Mídias';

    public static function customPreviewUrl($record)
    {
        if (str_starts_with($record->mime_type, 'image')) {
            return route('private.image', ['path' => $record->getPathRelativeToRoot('preview')]);
        }

        if ($record->mime_type == 'application/pdf') {
            return url('/images/pdf.png');
        }

        if (str_starts_with($record->mime_type, 'video')) {
            return url('/images/video.png');
        }

        return  url('/images/doc.png');
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
