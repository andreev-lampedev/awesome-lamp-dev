<?php

namespace App\Http\Controllers\Auth;

use App\Events\NewUserSignedUp;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse as HttpRedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @return RedirectResponse
     * @throws BindingResolutionException
     */
    public function redirectToProvider()
    {
        session()->flash('url.intended', url()->previous());

        return Socialite::driver('github')->redirect();
    }

    /**
     * @return HttpRedirectResponse
     * @throws BindingResolutionException
     */
    public function handleProviderCallback()
    {
        $user = $this->createOrUpdateUser(Socialite::driver('github')->user());

        if ($user->wasRecentlyCreated) {
            event(new NewUserSignedUp($user));
        }

        Auth::login($user, false);

        return redirect()->intended();
    }

    /**
     * @param mixed $socialiteUser
     * @return mixed
     */
    private function createOrUpdateUser($socialiteUser)
    {
        if (is_null($user = User::forSocialiteUser($socialiteUser))) {
            return User::create($this->socialiteUserAttributes($socialiteUser));
        }

        $user->update($this->socialiteUserAttributes($socialiteUser));

        return $user;
    }

    /**
     * @param mixed $socialiteUser
     * @return array
     */
    private function socialiteUserAttributes($socialiteUser)
    {
        return [
            'name' => $socialiteUser->getName() ?: $socialiteUser->getNickname(),
            'email' => $socialiteUser->getEmail(),
            'avatar' => $socialiteUser->getAvatar(),
            'github_username' => $socialiteUser->getNickname(),
            'github_user_id' => $socialiteUser->getId(),
        ];
    }
}
