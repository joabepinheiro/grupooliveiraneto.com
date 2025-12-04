<?php

namespace App\Filament\Grupooliveiraneto\Resources\Users\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Grid::make([])
                    ->schema([
                        Section::make('')
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('avatar')
                                    ->label('')
                                    ->avatar()
                                    ->placeholder('Enviar foto')
                                    ->imageEditor()
                                    ->alignCenter()
                                    ->disk('local')
                                    ->columnSpanFull(),
                            ]),


                        Section::make('')
                            ->schema([
                                CheckboxList::make('roles')
                                    ->required()
                                    ->columnSpanFull()
                                    ->label('Funções')
                                    ->relationship(
                                        'roles',
                                        'name',
                                    # modifyQueryUsing: fn (Builder $query) => $query->whereNot('name', '=', 'super_admin')
                                    ),
                            ])
                    ])
                    ->columnSpan([
                        'lg' => 4
                    ]),

                Section::make('')
                    ->schema([

                        TextInput::make('first_name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan([
                                'lg' => 6
                            ]),

                        TextInput::make('last_name')
                            ->label('Sobrenome')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan([
                                'lg' => 6
                            ]),

                        TextInput::make('email')
                            ->label('E-mail')
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->email()
                            ->maxLength(255)
                            ->columnSpan([
                                'lg' => 12
                            ]),

                        TextInput::make('username')
                            ->label('Username')
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->maxLength(255)
                            ->columnSpan([
                                'lg' => 12
                            ]),

                        TextInput::make('password')
                            ->label('Senha')
                            ->revealable()
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->maxLength(255)
                            ->columnSpan([
                                'lg' => 6
                            ]),

                        TextInput::make('password_confirmation')
                            ->label('Confirmar Senha')
                            ->revealable()
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->maxLength(255)
                            ->same('password')
                            ->columnSpan([
                                'lg' => 6
                            ]),


                        Select::make('empresas')
                            ->multiple()
                            ->required()
                            ->preload()
                            ->relationship(name: 'empresas', titleAttribute: 'nome')
                            ->label('Empresas')
                            ->searchable()
                            ->live()
                            ->columnSpanFull(),
                    ])
                    ->columns(12)
                    ->columnSpan([
                        'lg' => 8
                    ]),
            ]);
    }
}
