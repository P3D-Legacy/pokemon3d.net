<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\RedirectResponse;
use App\Achievements\User\AssociatedDiscord;
use App\Http\Controllers\Controller;
use App\Models\DiscordAccount;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
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
     */
    public function handleProviderCallback(): RedirectResponse
    {
        try {
            $discordUser = Socialite::driver('discord')->user();

            if (! $discordUser->user['verified']) {
                session()->flash('flash.banner', 'Discord user not verified.');
                session()->flash('flash.bannerStyle', 'danger');

                return redirect()->route('login');
            }

            $userProfile = [
                'id' => $discordUser->id,
                'username' => $discordUser->name,
                'email' => $discordUser->email,
                'avatar' => $discordUser->avatar,
                'discriminator' => $discordUser->user['discriminator'],
            ];

            // Check if DiscordAccount is already registered
            $discordAccount = DiscordAccount::withTrashed()
                ->where('id', $discordUser->id)
                ->first();

            // if it does not exist and is guest
            if (! $discordAccount && auth()->guest()) {
                session()->flash(
                    'flash.banner',
                    'Discord account association not found with any P3D account. Log in with your P3D account to associate.'
                );
                session()->flash('flash.bannerStyle', 'danger');

                return redirect()->route('login');
            }

            $discordAccountHasUser = $discordAccount ? $discordAccount->user : null;

            // if discord account is not associated with a user and is guest
            if (! $discordAccountHasUser && auth()->guest()) {
                session()->flash('flash.banner', 'You are not logged in and user was not found.');
                session()->flash('flash.bannerStyle', 'danger');

                return redirect()->route('login');
            } elseif ($discordAccountHasUser && auth()->guest()) {
                // if discord account is not associated with a user and is not guest
                Auth::login($discordAccountHasUser);

                return redirect()->route('dashboard');
            }

            // if user is logged in and discord account has a user
            if (auth()->user() && $discordAccountHasUser) {
                // check if authenticated user is not the same as discord account user
                if (auth()->id() !== $discordAccountHasUser->id) {
                    session()->flash('flash.banner', 'This Discord account is associated with another P3D account.');
                    session()->flash('flash.bannerStyle', 'warning');

                    return redirect()->route('profile.show');
                }

                // check if discord account is deleted then restore
                if ($discordAccount->trashed()) {
                    $discordAccount->restore();

                    return redirect()->route('profile.show');
                }

                Auth::login($discordAccountHasUser);

                return redirect()->route('dashboard');
            }

            // Create new discord account
            $userProfile['user_id'] = auth()->id();
            $userProfile['verified_at'] = now();
            DiscordAccount::create($userProfile);
            auth()
                ->user()
                ->unlock(new AssociatedDiscord());

            return redirect()->route('profile.show');
        } catch (InvalidStateException $e) {
            session()->flash('flash.banner', 'Something went wrong with Discord login. Please try again.');
            session()->flash('flash.bannerStyle', 'danger');

            return redirect()->route('home');
        } catch (ClientException $e) {
            session()->flash('flash.banner', 'Something went wrong with Discord login. Please try again.');
            session()->flash('flash.bannerStyle', 'danger');

            return redirect()->route('home');
        }

        return redirect()->route('dashboard');
    }
}
