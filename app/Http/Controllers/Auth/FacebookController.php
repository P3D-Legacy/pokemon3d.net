<?php

namespace App\Http\Controllers\Auth;

use App\Achievements\User\AssociatedFacebook;
use App\Http\Controllers\Controller;
use App\Models\FacebookAccount;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class FacebookController extends Controller
{
    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();

            $userProfile = [
                'id' => $facebookUser->id,
                'name' => $facebookUser->name,
                'email' => $facebookUser->email,
                'avatar' => $facebookUser->avatar,
            ];

            // Check if FacebookAccount is already registered
            $facebookAccount = FacebookAccount::where('id', $facebookUser->id)->first();

            // if it does not exist and is guest
            if (! $facebookAccount && auth()->guest()) {
                session()->flash(
                    'flash.banner',
                    'Discord account association not found with any P3D account. Log in with your P3D account to associate.'
                );
                session()->flash('flash.bannerStyle', 'danger');

                return redirect()->route('login');
            }

            $facebookAccountHasUser = $facebookAccount ? $facebookAccount->user : null;

            // if account is not associated with a user and is guest
            if (auth()->guest() && ! $facebookAccountHasUser) {
                return redirect()
                    ->route('login')
                    ->withError('You are not logged in and user was not found.');
            } elseif ($facebookAccountHasUser && auth()->guest()) {
                // if account is not associated with a user and is not guest
                Auth::login($facebookAccountHasUser);

                return redirect()->route('dashboard');
            }

            // if user is logged in and discord account has a user
            if (auth()->user() && $facebookAccountHasUser) {
                // check if authenticated user is not the same as discord account user
                if (auth()->id() !== $facebookAccountHasUser->id) {
                    session()->flash('flash.banner', 'This Facebook account is associated with another P3D account.');
                    session()->flash('flash.bannerStyle', 'warning');

                    return redirect()->route('profile.show');
                }

                // check if account is deleted then restore
                if ($facebookAccount->trashed()) {
                    $facebookAccount->restore();

                    return redirect()->route('profile.show');
                }

                Auth::login($facebookAccountHasUser);

                return redirect()->route('dashboard');
            }

            // Create new facebook account
            $userProfile['user_id'] = auth()->id();
            $userProfile['verified_at'] = now();
            FacebookAccount::create($userProfile);
            auth()
                ->user()
                ->unlock(new AssociatedFacebook());

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
