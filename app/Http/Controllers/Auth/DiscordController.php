<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Actions\Fortify\CreateNewUser;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Exception\ClientException;
use Laravel\Socialite\Two\InvalidStateException;

class DiscordController extends Controller
{
    /**
     * Redirect the user to the Discord authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('discord')->redirect();
    }

    /**
     * Obtain the user information from Discord.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        try {
            $discordUser = Socialite::driver('discord')->user();
            $userProfile = [
                'username' => $discordUser->name,
                'email' => $discordUser->email,
            ];

            // Check if user exists with email
            $user = User::where('email', $discordUser->email)->first();
            if ($user) {
                Auth::login($user);
                return redirect()->route('dashboard');
            }

            // Create a new user if not found
            $creator = new CreateNewUser();
            $createdUser = $creator->create($userProfile);
            // Login the new user
            Auth::login($createdUser);
        } catch (InvalidStateException $e) {
            return redirect()->route('home')->withError('Something went wrong with Discord login. Please try again.');
        } catch (ClientException $e) {
            return redirect()->route('home')->withError('Something went wrong with Discord login. Please try again.');
        }

        return redirect()->route('dashboard');
    }
}
