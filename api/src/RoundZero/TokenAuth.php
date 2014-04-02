<?php
namespace RoundZero;

use RoundZero\Service\Token;

class TokenAuth extends \Slim\Middleware
{
    /**
     * @var Token
     */
    protected $tokenService;

    /**
     * Public constructor
     * 
     * @param Token $tokenService
     */
    public function __construct(Token $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    public function call()
    {
        $tokenId = $this->app->request->params('token');

        if ($tokenId) {
            // Token supplied - check validity.
            if ($token = $this->tokenService->findById($tokenId)) {
                $this->app->user = $token->user;

            } else {
                $this->app->response->setStatus(403);
                echo json_encode(array(
                    'error' => "Invalid token",
                ));
                return;
            }
        } elseif ($this->requiresAuth()) {
            $this->app->response->setStatus(403);
            echo json_encode(array(
                'error' => "Authentication required",
            ));
            return;
        }

        $this->next->call();
    }

    /**
     * Determine whether current request requires token authentication.
     * 
     * @return bool
     */
    protected function requiresAuth()
    {
        $env = $this->app->environment();

        if ($env['REQUEST_METHOD'] == 'OPTIONS'
                || $env['PATH_INFO'] == '/v1/tokens/authenticate'
                || ($env['PATH_INFO'] == '/v1/users' && $env['REQUEST_METHOD'] == 'POST')) {
            return false;
        }
        return true;
    }
}
