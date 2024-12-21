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
     */
    public function handleProviderCallback(): \Illuminate\Http\RedirectResponse
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

            // Check if account is already registered
            $twitterAccount = TwitterAccount::where('id', $twitterUser->id)->first();

            // if it does not exist and is guest
            if (! $twitterAccount && auth()->guest()) {
                session()->flash(
                    'flash.banner',
                    'Twitter account association not found with any P3D account. Log in with your P3D account to associate.'
                );
                session()->flash('flash.bannerStyle', 'danger');

                return redirect()->route('login');
            }

            $twitterAccountHasUser = $twitterAccount ? $twitterAccount->user : null;

            // if account is not associated with a user and is guest
            if (auth()->guest() && ! $twitterAccountHasUser) {
                session()->flash('flash.banner', 'You are not logged in and user was not found.');
                session()->flash('flash.bannerStyle', 'danger');

                return redirect()->route('login');
            } elseif ($twitterAccountHasUser && auth()->guest()) {
                // if account is not associated with a user and is not guest
                Auth::login($twitterAccountHasUser);

                return redirect()->route('dashboard');
            }

            // if user is logged in and account has a user
            if (auth()->user() && $twitterAccountHasUser) {
                // check if authenticated user is not the same as account user
                if (auth()->id() !== $twitterAccountHasUser->id) {
                    session()->flash('flash.banner', 'This Twitter account is associated with another P3D account.');
                    session()->flash('flash.bannerStyle', 'warning');

                    return redirect()->route('profile.show');
                }

                // check if account is deleted then restore
                if ($twitterAccount->trashed()) {
                    $twitterAccount->restore();

                    return redirect()->route('profile.show');
                }

                Auth::login($twitterAccountHasUser);

                return redirect()->route('dashboard');
            }

            // Create new twitter account
            $userProfile['user_id'] = auth()->id();
            $userProfile['verified_at'] = now();
            TwitterAccount::create($userProfile);
            auth()
                ->user()
                ->unlock(new AssociatedTwitter);

            return redirect()->route('profile.show');
        } catch (InvalidStateException $e) {
            return redirect()
                ->route('home')
                ->withError('Something went wrong with Twitter login. Please try again.');
        } catch (ClientException $e) {
            return redirect()
                ->route('home')
                ->withError('Something went wrong with Twitter login. Please try again.');
        }

        return redirect()->route('dashboard');
    }
}
