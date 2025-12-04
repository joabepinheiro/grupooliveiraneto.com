<?php

namespace App\Filament\Resources\Permissions\Schemas;

use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class PermissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('')
                    ->schema([

                        Select::make('tipo')
                            ->label('Tipo')
                            ->required()
                            ->live()
                            ->options(function (){
                                return [
                                    'model'  => 'Model',
                                    'custom' => 'Personalizado',
                                ];
                            })
                            ->columnSpan([
                                'lg' => 6
                            ]),

                        Select::make('panel_id')
                            ->label('Painel')
                            ->required()
                            ->options(function (){
                                return [
                                    'admin'             => 'Administração',
                                    'Grupooliveiraneto' => 'Grupo Oliveira Neto',
                                    'movelveiculos'     => 'Movel Veículos',
                                    'Bydconquista'      => 'BYD Conquista',
                                ];
                            })
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        Select::make('guard_name')
                            ->label('Guard Name')
                            ->default('web')
                            ->options(function (){
                                return [
                                    'web' => 'web',
                                ];
                            })
                            ->columnSpan([
                                'lg' => 3
                            ]),


                        TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->columnSpan([
                                'lg' => 12
                            ]),

                        TextInput::make('descricao')
                            ->label('Descrição')
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('className')
                            ->label('ClassName')
                            ->required(function (Get $get){
                                if($get('tipo') == 'model'){
                                    return true;
                                }
                                return false;
                            })
                            ->disabled(function (Get $get, Set $set){
                                if($get('tipo') == 'custom'){
                                    $set('className', '');
                                    return true;
                                }
                                return false;
                            })
                            ->columnSpan([
                                'lg' => 6
                            ]),

                        TextInput::make('action')
                            ->label('Action')
                            ->required(function (Get $get){
                                if($get('tipo') == 'model'){
                                    return true;
                                }
                                return false;
                            })
                            ->disabled(function (Get $get, Set $set){
                                if($get('tipo') == 'custom'){
                                    $set('action', '');
                                    return true;
                                }
                                return false;
                            })
                            ->datalist([
                                'viewAny'   => 'viewAny',
                                'view'      => 'view',
                                'create'    => 'create',
                                'update'    => 'update',
                                'delete'    => 'delete',
                                'restore'   => 'restore',
                                'forceDelete'    => 'forceDelete',
                                'forceDeleteAny' => 'forceDeleteAny',
                                'deleteAny'      => 'deleteAny',
                                'restoreAny'     => 'restoreAny',
                                'replicate'      => 'replicate',
                                'reorder'        => 'reorder',
                            ])
                            ->columnSpan([
                                'lg' => 6
                            ]),





                    ])->columns(12),
            ]);
    }
}
