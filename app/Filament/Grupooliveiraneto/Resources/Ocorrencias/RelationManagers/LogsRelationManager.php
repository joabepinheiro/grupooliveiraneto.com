<?php

namespace App\Filament\Grupooliveiraneto\Resources\Ocorrencias\RelationManagers;

use App\Enums\ActivityLogEvent;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LogsRelationManager extends RelationManager
{
    protected static string $relationship = 'logs';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('log_name'),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('subject_type'),
                TextInput::make('event'),
                TextInput::make('subject_id')
                    ->numeric(),
                TextInput::make('causer_type'),
                TextInput::make('causer_id')
                    ->numeric(),
                TextInput::make('properties'),
                TextInput::make('batch_uuid'),
            ]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('log_name')
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->columnSpanFull(),
                TextEntry::make('subject_type')
                    ->placeholder('-'),
                TextEntry::make('event')
                    ->placeholder('-'),
                TextEntry::make('subject_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('causer_type')
                    ->placeholder('-'),
                TextEntry::make('causer_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('batch_uuid')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('log_name')
            ->columns([

                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('event')
                    ->label('Evento')
                    ->badge()
                    ->color(fn (string $state): string => ActivityLogEvent::from($state)->getColor())
                    ->sortable()
                    ->placeholder('Não informado'),

                TextColumn::make('subject_id')
                    ->label('ID do registro')
                    ->sortable(),

                TextColumn::make('subject_type')
                    ->label('Tipo')
                    ->sortable()
                    ->placeholder('Não informado'),

                TextColumn::make('description')
                    ->label('Descrição')
                    ->sortable()
                    ->placeholder('Não informado'),

                TextColumn::make('causer.name')
                    ->label('Usuário')
                    ->placeholder('Não informado'),

                TextColumn::make('created_at')
                    ->label('Cadastrado em')
                    ->sortable()
                    ->date('d/m/Y H:i:s')
                    ->placeholder('Não informado'),
            ])
            ->filters([
                //
            ])
            ->headerActions([

            ])
            ->recordActions([
            ])
            ->toolbarActions([
            ]);
    }
}
