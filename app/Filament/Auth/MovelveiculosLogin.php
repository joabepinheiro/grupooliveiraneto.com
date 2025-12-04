<?php
namespace App\Filament\Auth;

use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Pages\Login;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Validation\ValidationException;

class MovelveiculosLogin extends Login
{
    public function getHeading(): string|Htmlable
    {
        $this->extraBodyAttributes = ['class' => 'page-login page-movelveiculos'];
        return __('');
    }

    protected function getEmailFormComponent(): \Filament\Schemas\Components\Component
    {
        return TextInput::make('username') // Nome do campo 'username'
        ->label(__('Usuário / E-mail')) // Altere o rótulo
        ->required()
            ->autocomplete()
            ->autofocus();
    }

    // Exemplo do que você faria na sua classe CustomLogin.php
    protected function getCredentialsFromFormData(array $data): array
    {
        // Verifica se o valor do campo 'username' parece um e-mail
        $username_column = filter_var($data['username'], FILTER_VALIDATE_EMAIL)
            ? 'email' // Se for um email, usa 'email' como chave
            : 'username'; // Se não for, usa 'username' ou o nome do seu campo de username

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
