<?php
namespace RoundZero;

class TokenAuth extends \Slim\Middleware
{
    protected $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function call()
    {
        $tokenId = $this->app->request->params('token');
        $env = $this->app->environment();

        if ($tokenId) {
            $token = $this->entityManager->getRepository('RoundZero\Entity\Token')->find($tokenId);
            if ($token) {
                $env['user'] = $token->getUser();

            } else {
                $this->app->response->setStatus(403);
                echo json_encode(array(
                    'error' => "Invalid token",
                ));
                return;
            }
        } elseif ($env['PATH_INFO'] != '/v1/authenticate') {
            $this->app->response->setStatus(403);
            echo json_encode(array(
                'error' => "Authentication required",
            ));
            return;
        }

        $this->next->call();
    }
}
