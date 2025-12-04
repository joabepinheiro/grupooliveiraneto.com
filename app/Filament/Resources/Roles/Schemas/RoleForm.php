<?php

namespace App\Filament\Resources\Roles\Schemas;

use App\Models\Permission;
use Filament\Facades\Filament;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('')
                    ->schema([

                        TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan([
                                'lg' => 6
                            ]),

                        Select::make('panel_id')
                            ->label('Painel')
                            ->required()
                            ->options([
                                'admin'                 => 'Administrador',
                                'grupooliveiraneto'     => 'Grupo Oliveira Neto',
                                'movelveiculos'         => 'Movel Veículos',
                                'bydconquista'          => 'BYD Conquista',
                            ])
                            ->disabled(function ($operation){
                                if($operation == 'edit'){
                                    return true;
                                }
                                return false;
                            })
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        Select::make('guard_name')
                            ->label('Guard Name')
                            ->required()
                            ->options([
                                'web' => 'web',
                            ])
                            ->default('web')
                            ->columnSpan([
                                'lg' => 3
                            ]),
                    ]),

                Tabs::make('Tabs')
                    ->tabs([

                        Tabs\Tab::make('Modelos')
                            ->badge(function ($record){
                                if($record){
                                    return $record->permissions->where('tipo', '=', 'model')->count();
                                }
                                return null;
                            })
                            ->schema([
                                Grid::make()
                                    ->schema(function (Get $get) {
                                        return Permission::where('panel_id', $get('panel_id'))
                                            ->where('tipo','=', 'model')
                                            ->distinct()
                                            ->pluck('className', 'className')
                                            ->sort()
                                            ->map(function ($className) use ($get) {

                                                $fieldKey = str_replace('\\', '_', $className);

                                                return Section::make($className::getModelLabel())
                                                    ->description($className)
                                                    ->schema([
                                                        CheckboxList::make('model.' . $fieldKey)
                                                            ->hiddenLabel(true)
                                                            ->columns(4)
                                                            ->bulkToggleable()
                                                            ->options(
                                                                Permission::where('className', $className)
                                                                    ->where('panel_id', $get('panel_id'))
                                                                    ->pluck('titulo', 'id')
                                                            )
                                                            ->columnSpanFull()
                                                    ])
                                                    ->columnSpanFull();
                                            })
                                            ->toArray();
                                    })
                                    ->columnSpanFull()
                            ]),

                        Tabs\Tab::make('Outros')
                            ->badge(function ($record){
                                if($record){
                                    return $record->permissions()->where('tipo', '=', 'custom')->count();
                                }
                                return null;
                            })
                            ->schema([
                                Grid::make()
                                    ->schema(function (Get $get) {

                                        return Permission::where('panel_id', $get('panel_id'))
                                            ->where('tipo', 'custom')
                                            ->get()
                                            ->map(function ($permission) use ($get) {
                                                return  Checkbox::make('custom.' . $permission->id)
                                                    ->label($permission->descricao)
                                                    ->columns(4)
                                                    ->columnSpanFull();
                                            })
                                            ->toArray();
                                    })
                                    ->columnSpanFull()
                            ]),
                    ]),



            ]);
    }
}
