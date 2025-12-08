<?php

namespace Framework\Auth;

use App\Configuration;
use Framework\Core\App;
use Framework\Core\IAuthenticator;
use Framework\Core\IIdentity;
use Framework\Http\Session;
use RuntimeException;

abstract class SessionAuthenticator implements IAuthenticator
{
    private App $app;
    private Session $session;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->session = $this->app->getSession();
    }

    abstract protected function authenticate(string $username, string $password): ?IIdentity;

    public function login(string $username, string $password): bool
    {
        $identity = $this->authenticate($username, $password);

        if ($identity instanceof IIdentity) {
            $this->session->set(Configuration::IDENTITY_SESSION_KEY, $identity);
            return true;
        }

        if ($identity !== null) {
            throw new RuntimeException('Authenticated identity must implement IIdentity interface.');
        }

        return false;
    }

    public function logout(): void
    {
        $this->session->destroy();
    }

    public function getUser(): AppUser
    {
        $identity = $this->session->get(Configuration::IDENTITY_SESSION_KEY);

        if ($identity !== null && !($identity instanceof IIdentity)) {
            throw new RuntimeException('Stored identity must implement IIdentity interface.');
        }

        // AppUser = Framework\Auth\AppUser (sme v tom istom namespace)
        return new AppUser($identity);
    }
}
