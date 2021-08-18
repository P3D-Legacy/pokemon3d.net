<?php

namespace App\Actions\Fortify;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class UpdateUserPassword implements UpdatesUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and update the user's password.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    public function update($user, array $input)
    {
        $rules = [
            'current_password' => ['required', 'string'],
            'password' => ['required', 'min:10', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/', 'confirmed'],
        ];
        $messages = [
            'password.regex' => 'The password must contain at least one uppercase character, one number, and one special character.',
        ];
        Validator::make($input, $rules, $messages)
            ->after(function ($validator) use ($user, $input) {
                if (! isset($input['current_password']) || ! Hash::check($input['current_password'], $user->password)) {
                    $validator->errors()->add('current_password', __('The provided password does not match your current password.'));
                }
        })->validateWithBag('updatePassword');

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}
