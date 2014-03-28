<?php
namespace RoundZero;

class TokenAuth extends \Slim\Middleware
{
    protected $tokenService;

    public function __construct($tokenService)
    {
        $this->tokenService = $tokenService;
    }

    public function call()
    {
        $tokenId = $this->app->request->params('token');
        $env = $this->app->environment();

        if ($tokenId) {
            if ($token = $this->tokenService->findById($tokenId)) {
                $this->app->user = $token->user;

            } else {
                $this->app->response->setStatus(403);
                echo json_encode(array(
                    'error' => "Invalid token",
                ));
                return;
            }
        // Todo: ensure /v1/users is POST.
        } elseif ($env['PATH_INFO'] != '/v1/tokens/authenticate' &&  $env['PATH_INFO'] != '/v1/users') {
            $this->app->response->setStatus(403);
            echo json_encode(array(
                'error' => "Authentication required",
            ));
            return;
        }

        $this->next->call();
    }
}
