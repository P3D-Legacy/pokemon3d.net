<?php

namespace App\Actions\Fortify;

use App\Rules\OlderThan;
use App\Rules\YoungerThan;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  mixed  $user
     */
    public function update($user, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'alpha_dash:ascii', 'alpha_num:ascii', 'different:email', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id), 'indisposable', 'spam_mail'],
            'gender' => ['required', 'numeric'],
            'location' => ['nullable', 'max:255'],
            'about' => ['nullable', 'max:255'],
            'birthdate' => ['required', 'date_format:d-m-Y', new OlderThan, new YoungerThan],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:3072'],
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        if ($input['email'] !== $user->email && $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user
                ->forceFill([
                    'name' => $input['name'],
                    //'username' => $input['username'],
                    'gender' => $input['gender'],
                    'location' => $input['location'],
                    'about' => $input['about'],
                    'birthdate' => Carbon::parse($input['birthdate'])->format('Y-m-d'),
                    'email' => $input['email'],
                ])
                ->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  mixed  $user
     */
    protected function updateVerifiedUser($user, array $input): void
    {
        $user
            ->forceFill([
                'name' => $input['name'],
                'username' => $input['username'],
                'gender' => $input['gender'],
                'location' => $input['location'],
                'about' => $input['about'],
                'birthdate' => Carbon::parse($input['birthdate'])->format('Y-m-d'),
                'email' => $input['email'],
                'email_verified_at' => null,
            ])
            ->save();

        $user->sendEmailVerificationNotification();
    }
}
