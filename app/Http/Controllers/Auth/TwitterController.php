<?php

namespace App\Http\Controllers\Auth;

use App\Achievements\User\AssociatedTwitter;
use App\Http\Controllers\Controller;
use App\Models\TwitterAccount;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class TwitterController extends Controller
{
    /**
     * Redirect the user to the Twitter authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('twitter')->redirect();
    }

    /**
     * Obtain the user information from Twitter.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        try {
            $twitterUser = Socialite::driver('twitter')->user();

            if ($twitterUser->user['suspended']) {
                return redirect()
                    ->route('login')
                    ->withError('Twitter user is suspended.');
            }

            $userProfile = [
                'id' => $twitterUser->id,
                'username' => $twitterUser->nickname,
                'name' => $twitterUser->name,
                'email' => $twitterUser->email,
                'avatar' => $twitterUser->avatar,
            ];

            // Check if user exists with email
            $twitterAccount = TwitterAccount::where(
                'id',
                $twitterUser->id
            )->first();
            if (! $twitterAccount && auth()->guest()) {
                return redirect()
                    ->route('login')
                    ->withError(
                        'Twitter account association not found with any P3D account.'
                    );
            }

            $user = $twitterAccount ? $twitterAccount->user : null;
            if (auth()->user() && $user) {
                if (auth()->user()->id !== $user->id) {
                    request()
                        ->session()
                        ->flash(
                            'flash.banner',
                            'This Twitter account is associated with another P3D account.'
                        );
                    request()
                        ->session()
                        ->flash('flash.bannerStyle', 'warning');

                    return redirect()->route('profile.show');
                }
                Auth::login($user);

                return redirect()->route('dashboard');
            }

            if (auth()->guest() && ! $user) {
                return redirect()
                    ->route('login')
                    ->withError(
                        'You are not logged in and user was not found.'
                    );
            }

            // Create new twitter account
            $user = auth()->user();
            $userProfile['user_id'] = $user->id;
            $userProfile['verified_at'] = now();
            TwitterAccount::create($userProfile);
            $user->unlock(new AssociatedTwitter());

            return redirect()->route('profile.show');
        } catch (InvalidStateException $e) {
            return redirect()
                ->route('home')
                ->withError(
                    'Something went wrong with Twitter login. Please try again.'
                );
        } catch (ClientException $e) {
            return redirect()
                ->route('home')
                ->withError(
                    'Something went wrong with Twitter login. Please try again.'
                );
        }

        return redirect()->route('dashboard');
    }
}
