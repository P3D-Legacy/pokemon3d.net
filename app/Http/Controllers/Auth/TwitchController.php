<?php

namespace App\Http\Controllers\Auth;

use App\Models\TwitchAccount;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Exception\ClientException;
use App\Achievements\User\AssociatedTwitch;
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
     * @return \Illuminate\Http\Response
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

            // Check if user exists with email
            $twitchAccount = TwitchAccount::where(
                'id',
                $twitchUser->id
            )->first();
            if (!$twitchAccount && auth()->guest()) {
                return redirect()
                    ->route('login')
                    ->withError(
                        'Twitch account association not found with any P3D account.'
                    );
            }

            $user = $twitchAccount ? $twitchAccount->user : null;
            if (auth()->user() && $user) {
                if (auth()->user()->id !== $user->id) {
                    request()
                        ->session()
                        ->flash(
                            'flash.banner',
                            'This Twitch account is associated with another P3D account.'
                        );
                    request()
                        ->session()
                        ->flash('flash.bannerStyle', 'warning');
                    return redirect()->route('profile.show');
                }
                Auth::login($user);
                return redirect()->route('dashboard');
            }

            if (auth()->guest() && !$user) {
                return redirect()
                    ->route('login')
                    ->withError(
                        'You are not logged in and user was not found.'
                    );
            }

            // Create new twitch account
            $user = auth()->user();
            $userProfile['user_id'] = $user->id;
            $userProfile['verified_at'] = now();
            TwitchAccount::create($userProfile);
            $user->unlock(new AssociatedTwitch());
            return redirect()->route('profile.show');
        } catch (InvalidStateException $e) {
            return redirect()
                ->route('home')
                ->withError(
                    'Something went wrong with Twitch login. Please try again.'
                );
        } catch (ClientException $e) {
            return redirect()
                ->route('home')
                ->withError(
                    'Something went wrong with Twitch login. Please try again.'
                );
        }

        return redirect()->route('dashboard');
    }
}
