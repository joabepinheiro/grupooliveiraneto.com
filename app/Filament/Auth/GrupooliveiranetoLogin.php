<?php
namespace App\Filament\Auth;

use Filament\Auth\Pages\Login;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Validation\ValidationException;

class GrupooliveiranetoLogin extends Login
{
    public function getHeading(): string|Htmlable
    {
        $this->extraBodyAttributes = [
            'class' => 'page-login page-login-grupooliveiraneto'
        ];

        return __('');
    }

    protected function getEmailFormComponent(): \Filament\Schemas\Components\Component
    {
        return TextInput::make('username')
            ->label(__('Usuário / E-mail'))
            ->required()
            ->autocomplete('username')
            ->autofocus();
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        $username_column = filter_var($data['username'], FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        return [
            $username_column => $data['username'],
            'password' => $data['password'],
        ];
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.username' => __('filament-panels::auth/pages/login.messages.failed'),
        ]);
    }
}
