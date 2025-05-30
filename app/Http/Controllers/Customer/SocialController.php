<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller; 
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;

class SocialController extends Controller
{
    public function redirectGoogle() {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback() {
        $googleUser = Socialite::driver('google')->stateless()->user();
        return $this->loginOrCreateUser($googleUser, 'google');
    }
    

    public function redirectFacebook() {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $fbUser = Socialite::driver('facebook')->user();
            return $this->loginOrCreateUser($fbUser, 'facebook');
        } catch (\Exception $e) {
            return redirect()->route('customer.login')->with('error', 'Đã xảy ra lỗi khi đăng nhập với Facebook.');
        }
    }
    
    

    private function loginOrCreateUser($providerUser, $provider) {
        $user = User::updateOrCreate([
            'email' => $providerUser->getEmail(),
        ], [
            'name' => $providerUser->getName(),
            'avatar' => $providerUser->getAvatar(),
            'password' => bcrypt(Str::random(24)), // random password
            'role_id' => 2,
        ]);

        Auth::login($user);

        return redirect()->route('customer.home');
    }
}
