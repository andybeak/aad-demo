<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Socialite;
use Auth;
use Log;

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
    protected $redirectTo = '/protected';

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
     * Redirect the user to the Azure authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('azure')->redirect();
    }

    /**
     * Obtain the user information from Azure.
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function handleProviderCallback()
    {
        try {

            $azureUser = Socialite::with('azure')->user();

        } catch (\Exception $e) {

            Log::error(__METHOD__ . ' : ' . $e->getMessage());

            return redirect()->route('login');

        }

        $authUser = $this->findOrCreateUser($azureUser);

        Auth::login($authUser, true);

        return redirect()->route('protected');

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect()->guest(route( 'home' ));
    }

    /**
     * @param \SocialiteProviders\Manager\OAuth2\User $user
     * @return User
     * @throws \Exception
     */
    private function findOrCreateUser(\SocialiteProviders\Manager\OAuth2\User $user): User
    {
        $authUser = User::where('azure_id', $user->id)->first();

        if ($authUser) {

            return $authUser;

        }

        $newUser = User::create([
            'name' => $user->name,
            'email' => $user->email,
            'azure_id' => $user->id,
            'password' => bin2hex(random_bytes(16))
        ]);

        return $newUser;
    }
}
