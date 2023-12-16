<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Stats\UserRegistrationStats;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'alpha_dash:ascii', 'alpha_num:ascii', 'different:email', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'spam_mail'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'username' => $input['username'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        $user->giveConsentTo(config('app.required_consent'), [
            'text' => config('app.consents')[config('app.required_consent')],
        ]);

        UserRegistrationStats::increase();

        return $user;
    }
}
