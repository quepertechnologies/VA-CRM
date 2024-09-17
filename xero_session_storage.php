<?php
class XEROSessionStorage
{
    function __construct()
    {
        if (!isset($_SESSION)) {
            $this->init_session();
        }
    }

    public function init_session()
    {
        session_start();
    }

    public function getSession()
    {
        return array_key_exists('xero_oauth2', $_SESSION) ? $_SESSION['xero_oauth2'] : null;
    }

    public function setToken($token, $expires, $tenantId, $refreshToken, $idToken)
    {
        $_SESSION['xero_oauth2'] = [
            'token' => $token,
            'expires' => $expires ? $expires : null,
            'tenant_id' => $tenantId,
            'refresh_token' => $refreshToken,
            'id_token' => $idToken
        ];
    }

    public function getToken()
    {
        // If it doesn't exist or is expired, return null
        if (
            empty($this->getSession())
            || (array_key_exists('xero_oauth2', $_SESSION) && $_SESSION['xero_oauth2']['expires'] !== null
                && $_SESSION['xero_oauth2']['expires'] <= time())
        ) {
            return null;
        }
        return $this->getSession();
    }

    public function getAccessToken()
    {
        return $_SESSION['xero_oauth2']['token'];
    }

    public function getRefreshToken()
    {
        return $_SESSION['xero_oauth2']['refresh_token'];
    }

    public function getExpires()
    {
        return $_SESSION['xero_oauth2']['expires'];
    }

    public function getXeroTenantId()
    {
        return $_SESSION['xero_oauth2']['tenant_id'];
    }

    public function getIdToken()
    {
        return $_SESSION['xero_oauth2']['id_token'];
    }

    public function isAccessTokenExpired()
    {
        if (!empty($this->getSession())) {
            if (time() > $this->getExpires()) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }
}
