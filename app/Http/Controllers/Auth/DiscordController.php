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
            $discordAccount = DiscordAccount::where('did', $userProfile['id'])->first();
            $user = $discordAccount ? $discordAccount->user : null;
            if ($user) {
                Auth::login($user);
                return redirect()->route('dashboard');
            } else {
                // User not found
                return redirect()->route('login')->withError('Discord account association not found.');
            }
        } catch (InvalidStateException $e) {
            return redirect()->route('home')->withError('Something went wrong with Discord login. Please try again.');
        } catch (ClientException $e) {
            return redirect()->route('home')->withError('Something went wrong with Discord login. Please try again.');
        }

        return redirect()->route('dashboard');
    }
}
