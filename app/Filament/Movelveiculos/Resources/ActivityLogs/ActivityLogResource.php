<?php

namespace App\Filament\Movelveiculos\Resources\ActivityLogs;

use App\Filament\Movelveiculos\Resources\ActivityLogs\Pages\CreateActivityLog;
use App\Filament\Movelveiculos\Resources\ActivityLogs\Pages\EditActivityLog;
use App\Filament\Movelveiculos\Resources\ActivityLogs\Pages\ListActivityLogs;
use App\Filament\Movelveiculos\Resources\ActivityLogs\Pages\ViewActivityLog;
use App\Filament\Movelveiculos\Resources\ActivityLogs\Schemas\ActivityLogForm;
use App\Filament\Movelveiculos\Resources\ActivityLogs\Schemas\ActivityLogInfolist;
use App\Filament\Movelveiculos\Resources\ActivityLogs\Tables\ActivityLogsTable;
use App\Models\ActivityLog;
use App\Traits\HasResourcePermissions;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ActivityLogResource extends Resource
{
    use HasResourcePermissions;

    protected static ?string $model = ActivityLog::class;

    protected static string|BackedEnum|null $navigationIcon = 'fas-flag';
    protected static ?string $recordTitleAttribute = 'Log';
    protected static ?string $slug              = 'logs-de-atividades';
    protected static ?string $modelLabel        = 'Log';
    protected static ?string $pluralModelLabel  = 'Logs';
    protected static ?string $navigationLabel   = 'Logs';
    protected static string|null|\UnitEnum $navigationGroup = 'Configurações';
    protected static ?int $navigationSort = 180;

    public static function form(Schema $schema): Schema
    {
        return ActivityLogForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ActivityLogInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ActivityLogsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActivityLogs::route('/'),
            //'create' => CreateActivityLog::route('/create'),
            'view' => ViewActivityLog::route('/{record}'),
            //'edit' => EditActivityLog::route('/{record}/edit'),
        ];
    }
}
