<?php

namespace App\Http\Controllers;

use App\User;
use App\LinkedSocialAccount;
use Faker\Provider\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Laravel\Socialite\Two\User as ProviderUser;
use Illuminate\Support\Facades\Storage;

class SocialAccountsService
{
    //
    /**
     * Find or create user instance by provider user instance and provider name.
     *
     * @param ProviderUser $providerUser
     * @param string $provider
     *
     * @return User
     */
    public function findOrCreate(ProviderUser $providerUser, string $provider): User
    {
        $linkedSocialAccount = LinkedSocialAccount::where('provider_name', $provider)
            ->where('provider_id', $providerUser->getId())
            ->first();
        if ($linkedSocialAccount) {
            return $linkedSocialAccount->user;
        } else {
            $user = null;
            if ($email = $providerUser->getEmail()) {
                $user = User::where('email', $email)->first();
            }
            if (!$user) {
                $fileContents = file_get_contents($providerUser->getAvatar());
                $new_avatar_url = Str::random(16).'.jpg';
                Storage::put("public/profiles/{$new_avatar_url}", $fileContents);
                //dd($fileContents);
                //dd($providerUser->getAvatar());
                $names = explode(' ', $providerUser->getName());
                $user = User::create([
                    'first_name' => $names[0],
                    'last_name' => $names[1],
                    'email' => $providerUser->getEmail(),
                    'local' => '',
                    'avatar' => $new_avatar_url,
                    'messaging_token' => ''
                ]);
            }
            $user->linkedSocialAccounts()->create([
                'provider_id' => $providerUser->getId(),
                'provider_name' => $provider,
            ]);
            return $user;
        }
    }
}
