<?php

namespace App\Http\Controllers\Auth;

use App\Models\FacebookAccount;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Exception\ClientException;
use App\Achievements\User\AssociatedFacebook;
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
     * @return \Illuminate\Http\Response
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

            // Check if user exists with email
            $facebookAccount = FacebookAccount::where('id', $facebookUser->id)->first();
            if (!$facebookAccount && auth()->guest()) {
                return redirect()
                    ->route('login')
                    ->withError('Facebook account association not found with any P3D account.');
            }

            $user = $facebookAccount ? $facebookAccount->user : null;
            if (auth()->user() && $user) {
                if (auth()->user()->id !== $user->id) {
                    request()
                        ->session()
                        ->flash('flash.banner', 'This Facebook account is associated with another P3D account.');
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
                    ->withError('You are not logged in and user was not found.');
            }

            // Create new facebook account
            $user = auth()->user();
            $userProfile['user_id'] = $user->id;
            $userProfile['verified_at'] = now();
            FacebookAccount::create($userProfile);
            $user->unlock(new AssociatedFacebook());
            return redirect()->route('profile.show');
        } catch (InvalidStateException $e) {
            return redirect()
                ->route('home')
                ->withError('Something went wrong with Facebook login. Please try again.');
        } catch (ClientException $e) {
            return redirect()
                ->route('home')
                ->withError('Something went wrong with Facebook login. Please try again.');
        }

        return redirect()->route('dashboard');
    }
}
