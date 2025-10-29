<?php

namespace App\Socialite;

use GuzzleHttp\RequestOptions;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\User;

class CustomOAuthProvider extends AbstractProvider
{
    protected $openidConfig = null;

    protected function getOpenIdConfig()
    {
        if ($this->openidConfig === null) {
            $response = $this->getHttpClient()->get(settings('auth.oauth.discovery_url', config('settings.auth.oauth.discovery_url')));
            $this->openidConfig = json_decode((string) $response->getBody(), true);
        }

        return $this->openidConfig;
    }

    protected function getAuthUrl($state)
    {
        $config = $this->getOpenIdConfig();

        return $this->buildAuthUrlFromBase($config['authorization_endpoint'], $state);
    }

    protected function getTokenUrl()
    {
        $config = $this->getOpenIdConfig();

        return $config['token_endpoint'];
    }

    protected function getUserByToken($token)
    {
        $config = $this->getOpenIdConfig();
        $response = $this->getHttpClient()->get($config['userinfo_endpoint'], [
            RequestOptions::HEADERS => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id' => $user[settings('auth.oauth.fields.id', config('settings.auth.oauth.fields.id'))],
            'name' => $user[settings('auth.oauth.fields.name', config('settings.auth.oauth.fields.name'))],
            'email' => $user[settings('auth.oauth.fields.email', config('settings.auth.oauth.fields.email'))],
        ]);
    }
}
