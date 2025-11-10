<?php

namespace App\Models\Entrega;

use App\Models\AbstractModel;
use App\Traits\LogsAllActivity;
use Filament\Support\Enums\IconSize;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\HtmlString;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
class Nota extends AbstractModel
{
    use SoftDeletes;
    use LogsAllActivity;

    protected $table = 'notas';

    public static string $prefixId = 'NOT-';

    protected $fillable = [
        'codigo',
        'nota',
        'grupo',
        'atributos',
        'morphable_id',
        'morphable_type',

        'created_by',
        'updated_by',
        'deleted_by',

        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'anexos' => 'array',
        'atributos' => 'array',
    ];

    public function morphable()
    {
        return $this->morphTo();
    }
}
