<?php

namespace App\Controllers;

use App\Core\Controller;
use GuzzleHttp\Client;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController extends Controller
{
    private $logtoEndpoint;
    private $appId;
    private $appSecret;
    private $redirectUri;
    private $client;

    public function __construct()
    {
        parent::__construct();
        
        $this->logtoEndpoint = $_ENV['LOGTO_ENDPOINT'];
        $this->appId = $_ENV['LOGTO_APP_ID'];
        $this->appSecret = $_ENV['LOGTO_APP_SECRET'];
        $this->redirectUri = $_ENV['BASE_URL'] . '/callback';
        
        $this->client = new Client([
            'base_uri' => $this->logtoEndpoint,
            'timeout' => 5.0,
        ]);
    }

    public function login()
    {
        // Generate random state for CSRF protection
        $state = bin2hex(random_bytes(16));
        $_SESSION['oauth_state'] = $state;

        // Generate random code verifier and challenge for PKCE
        $codeVerifier = bin2hex(random_bytes(32));
        $codeChallenge = strtr(base64_encode(hash('sha256', $codeVerifier, true)), '+/', '-_');
        $_SESSION['code_verifier'] = $codeVerifier;

        // Build authorization URL
        $params = http_build_query([
            'client_id' => $this->appId,
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
            'scope' => 'openid profile',
            'state' => $state,
            'code_challenge' => $codeChallenge,
            'code_challenge_method' => 'S256'
        ]);

        // Redirect to Logto login page
        $this->redirect($this->logtoEndpoint . '/oidc/auth?' . $params);
    }

    public function callback()
    {
        try {
            // Verify state to prevent CSRF
            $state = $_GET['state'] ?? '';
            if ($state !== ($_SESSION['oauth_state'] ?? '')) {
                throw new \Exception('Invalid state parameter');
            }

            // Exchange code for tokens
            $code = $_GET['code'] ?? '';
            $response = $this->client->post('/oidc/token', [
                'form_params' => [
                    'client_id' => $this->appId,
                    'client_secret' => $this->appSecret,
                    'code' => $code,
                    'redirect_uri' => $this->redirectUri,
                    'grant_type' => 'authorization_code',
                    'code_verifier' => $_SESSION['code_verifier']
                ]
            ]);

            $tokens = json_decode($response->getBody(), true);

            // Verify and decode ID token
            $idToken = $tokens['id_token'];
            $decodedToken = JWT::decode($idToken, new Key($this->appSecret, 'HS256'));

            // Store user information in session
            $_SESSION['user'] = [
                'id' => $decodedToken->sub,
                'name' => $decodedToken->name ?? '',
                'email' => $decodedToken->email ?? '',
                'access_token' => $tokens['access_token'],
                'refresh_token' => $tokens['refresh_token'] ?? null
            ];

            // Clean up session variables
            unset($_SESSION['oauth_state']);
            unset($_SESSION['code_verifier']);

            // Redirect to home page
            $this->redirect('/');
        } catch (\Exception $e) {
            // Handle authentication errors
            error_log('Authentication error: ' . $e->getMessage());
            $this->view('error', [
                'title' => 'Authentication Error',
                'message' => 'An error occurred during authentication. Please try again.'
            ]);
        }
    }

    public function logout()
    {
        // Clear session
        session_destroy();
        
        // Build logout URL
        $params = http_build_query([
            'client_id' => $this->appId,
            'post_logout_redirect_uri' => $_ENV['BASE_URL']
        ]);

        // Redirect to Logto logout endpoint
        $this->redirect($this->logtoEndpoint . '/oidc/logout?' . $params);
    }
}
