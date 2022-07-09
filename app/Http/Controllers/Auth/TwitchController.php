<?php

namespace App\Http\Controllers\Auth;

use App\Achievements\User\AssociatedTwitch;
use App\Http\Controllers\Controller;
use App\Models\TwitchAccount;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class TwitchController extends Controller
{
    /**
     * Redirect the user to the Twitch authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('twitch')->redirect();
    }

    /**
     * Obtain the user information from Twitch.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback()
    {
        try {
            $twitchUser = Socialite::driver('twitch')->user();

            $userProfile = [
                'id' => $twitchUser->id,
                'name' => $twitchUser->name,
                'username' => $twitchUser->nickname,
                'email' => $twitchUser->email,
                'avatar' => $twitchUser->avatar,
            ];

            // Check if TwitchAccount is already registered
            $twitchAccount = TwitchAccount::where('id', $twitchUser->id)->first();

            // if it does not exist and is guest
            if (!$twitchAccount && auth()->guest()) {
                session()->flash(
                    'flash.banner',
                    'Discord account association not found with any P3D account. Log in with your P3D account to associate.'
                );
                session()->flash('flash.bannerStyle', 'danger');

                return redirect()->route('login');
            }

            $twitchAccountHasUser = $twitchAccount ? $twitchAccount->user : null;

            // if account is not associated with a user and is guest
            if (auth()->guest() && !$twitchAccountHasUser) {
                return redirect()
                    ->route('login')
                    ->withError('You are not logged in and user was not found.');
            } elseif ($twitchAccountHasUser && auth()->guest()) {
                // if account is not associated with a user and is not guest
                Auth::login($twitchAccountHasUser);

                return redirect()->route('dashboard');
            }

            // if user is logged in and discord account has a user
            if (auth()->user() && $twitchAccountHasUser) {
                // check if authenticated user is not the same as discord account user
                if (auth()->id() !== $twitchAccountHasUser->id) {
                    session()->flash('flash.banner', 'This Twitch account is associated with another P3D account.');
                    session()->flash('flash.bannerStyle', 'warning');

                    return redirect()->route('profile.show');
                }

                // check if discord account is deleted then restore
                if ($twitchAccount->trashed()) {
                    $twitchAccount->restore();

                    return redirect()->route('profile.show');
                }

                Auth::login($twitchAccountHasUser);

                return redirect()->route('dashboard');
            }

            // Create new twitch account
            $userProfile['user_id'] = auth()->id();
            $userProfile['verified_at'] = now();
            TwitchAccount::create($userProfile);
            auth()
                ->user()
                ->unlock(new AssociatedTwitch());

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
