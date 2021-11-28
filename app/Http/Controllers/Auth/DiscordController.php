<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Actions\Fortify\CreateNewUser;
use App\Models\DiscordAccount;
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
            
            if (!$discordUser->user['verified']) {
                return redirect()->route('login')->withError('Discord user not verified.');
            }

            $userProfile = [
                'id' => $discordUser->id,
                'username' => $discordUser->name,
                'email' => $discordUser->email,
                'avatar' => $discordUser->avatar,
                'discriminator' => $discordUser->user['discriminator'],
            ];

            // Check if user exists with email
            $discordAccount = DiscordAccount::where('id', $discordUser->id)->first();
            if (!$discordAccount) {
                return redirect()->route('login')->withError('Discord account association not found with any P3D account.');
            }

            $user = $discordAccount ? $discordAccount->user : null;
            if ($user) {
                Auth::login($user);
                return redirect()->route('dashboard');
            }

            if (auth()->guest() && !$user) {
                return redirect()->route('login')->withError('You are not logged in and user was not found.');
            }

            // Create new discord account
            $user = auth()->user();
            $userProfile['user_id'] = $user->id;
            $userProfile['verified_at'] = now();
            DiscordAccount::create($userProfile);
            return redirect()->route('profile.show');

        } catch (InvalidStateException $e) {
            return redirect()->route('home')->withError('Something went wrong with Discord login. Please try again.');
        } catch (ClientException $e) {
            return redirect()->route('home')->withError('Something went wrong with Discord login. Please try again.');
        }

        return redirect()->route('dashboard');
    }
}
