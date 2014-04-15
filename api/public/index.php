<?php
require_once __DIR__ . "/../vendor/autoload.php";
$config = require __DIR__ . "/../config.php";

$entityManager = null;
use RoundZero\TokenAuth;

$db = new PDO(
    'mysql:host=' . $config['db']['host'] . ';' .
    'dbname=' . $config['db']['name'] . ';' .
    'charset=utf8',
    $config['db']['username'],
    $config['db']['password']
);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

$userService = new RoundZero\Service\User($db);
$groupService = new RoundZero\Service\Group($db);
$membershipService = new RoundZero\Service\Membership($db);
$tokenService = new RoundZero\Service\Token($db);
$roundService = new RoundZero\Service\Round($db);
$orderService = new RoundZero\Service\Order($db);

$app = new \Slim\Slim();
$app->add(new TokenAuth($tokenService));

$app->response->headers->set('Content-Type', 'application/json');

$app->error(function (\Exception $e) use ($app) {
    echo json_encode(array(
        'error' => 'Application error',
    ));
});

$app->notFound(function () use ($app) {
    echo json_encode(array(
        'error' => 'Not found',
    ));
});

$app->options('/v1/tokens/authenticate', function () use ($app) {
    $app->response->setStatus('Allow', 'POST');
});

$app->post('/v1/tokens/authenticate', function () use ($userService, $tokenService, $app) {
    $login = json_decode($app->request->getBody());
    if ($user = $userService->findByLogin($login->email, $login->password)) {
        $id = $tokenService->create($user->id);
        $app->response->setStatus(201);
        $app->response->headers->set('Location', '/v1/tokens/' . $id);
        echo json_encode($tokenService->findById($id));
    } else {
        $app->response->setStatus(404);
        echo json_encode(array(
            'error' => "Incorrect login",
        ));
    }
});

$app->options('/v1/tokens/:id', function () use ($app) {
    $app->response->setStatus('Allow', 'GET,DELETE');
});

$app->get('/v1/tokens/:id', function ($id) use ($tokenService, $app) {
    if ($token = $tokenService->findById($id)) {
        echo json_encode($token);
    } else {
        $app->response->setStatus(404);
        echo json_encode(array('error' => "Token $id not found"));
    }
});

$app->delete('/v1/tokens/:id', function ($id) use ($tokenService, $app) {
    if ($token = $tokenService->findById($id)) {
        $tokenService->delete($id);
        $app->response->setStatus(204);
    } else {
        $app->response->setStatus(404);
        echo json_encode(array('error' => "Token $id not found"));
    }
});

// Users

$app->options('/v1/users', function () use ($app) {
    $app->response->setStatus('Allow', 'GET,POST');
});

$app->get('/v1/users', function () use ($userService, $app) {
    echo json_encode($userService->findAll());
});

$app->get('/v1/users/suggestions/:partial', function ($partial) use ($userService, $app) {
    $groupId = $app->request->params('groupId');
    echo json_encode($userService->findSuggestions($partial, $groupId));
});

$app->post('/v1/users', function () use ($userService, $app) {
    $user = json_decode($app->request->getBody());
    $id = $userService->insert($user);

    $app->response->setStatus(201);
    $app->response->headers->set('Location', '/v1/users/' . $id);
    echo json_encode($userService->findById($id));
});

$app->options('/v1/users/:id', function () use ($app) {
    $app->response->setStatus('Allow', 'GET,PUT,DELETE');
});

$app->get('/v1/users/:id', function ($id) use ($userService, $app) {
    if ($user = $userService->findById($id, true)) {
        echo json_encode($user);
    } else {
        $app->response->setStatus(404);
        echo json_encode(array('error' => "User $id not found"));
    }
});

$app->put('/v1/users/:id', function ($id) use ($userService, $app) {
    $user = json_decode($app->request->getBody());
    $user->id = $id;
    if ($userService->findById($id)) {
        // Check if user being edited is logged in user.
        if ($id == $app->user->id) {
            $userService->update($user);
            echo json_encode($userService->findById($id));
        } else {
            $app->response->setStatus(403);
            echo json_encode(array(
                'error' => "Not authorised to modify other users",
            ));
        }
    } else {
        $app->response->setStatus(404);
        echo json_encode(array('error' => "User $id not found"));
    }
});

$app->delete('/v1/users/:id', function ($id) use ($userService, $app) {
    if ($user = $userService->findById($id)) {
        // Check if user being deleted is logged in user.
        if ($id == $app->user->id) {
            $userService->delete($id);
            $app->response->setStatus(204);
        } else {
            $app->response->setStatus(403);
            echo json_encode(array(
                'error' => "Not authorised to remove other users",
            ));
        }
    } else {
        $app->response->setStatus(404);
        echo json_encode(array('error' => "User $id not found"));
    }
});


$app->get('/v1/users/:id/rounds', function ($id) use ($entityManager, $app) {
    if ($user = $entityManager->getRepository('RoundZero\Entity\User')->find($id)) {
        $results = array();
        foreach ($user->getRounds() as $round) {
            $results[] = $round->toArray();
        }

        echo json_encode($results);
    } else {
        $app->response->setStatus(404);
        echo json_encode(array(
            'error' => "User $id not found",
        ));
    }
});

$app->options('/v1/users/email/:email', function () use ($app) {
    $app->response->setStatus('Allow', 'GET');
});

$app->get('/v1/users/email/:email', function ($email) use ($userService, $app) {
    if ($user = $userService->findByEmail($email)) {
        echo json_encode($user);
    } else {
        $app->response->setStatus(404);
        echo json_encode(array('error' => "User $id not found"));
    }
});

// Groups

$app->options('/v1/groups', function () use ($app) {
    $app->response->setStatus('Allow', 'GET,POST');
});

$app->get('/v1/groups', function () use ($groupService, $app) {
    echo json_encode($groupService->findAll());
});

$app->post('/v1/groups', function () use ($groupService, $app) {
    $group = json_decode($app->request->getBody());
    $id = $groupService->insert($group);

    $app->response->setStatus(201);
    $app->response->headers->set('Location', '/v1/groups/' . $id);
    echo json_encode($groupService->findById($id, true));
});

$app->options('/v1/groups/:id', function () use ($app) {
    $app->response->setStatus('Allow', 'GET,PUT,DELETE');
});

$app->get('/v1/groups/:id', function ($id) use ($groupService, $app) {
    if ($group = $groupService->findById($id, true)) {
        echo json_encode($group);
    } else {
        $app->response->setStatus(404);
        echo json_encode(array('error' => "Group $id not found"));
    }
});

$app->put('/v1/groups/:id', function ($id) use ($groupService, $app) {
    $group = json_decode($app->request->getBody());
    $group->id = $id;
    if ($groupService->update($group)) {
        echo json_encode($groupService->findById($id, true));
    } else {
        $app->response->setStatus(404);
        echo json_encode(array('error' => "Group $id not found"));
    }
});

$app->delete('/v1/groups/:id', function ($id) use ($groupService, $app) {
    if ($group = $groupService->findById($id)) {
        $groupService->delete($id);
        $app->response->setStatus(204);
    } else {
        $app->response->setStatus(404);
        echo json_encode(array('error' => "Group $id not found"));
    }
});

// Memberships

$app->options('/v1/memberships', function () use ($app) {
    $app->response->setStatus('Allow', 'POST');
});

$app->post('/v1/memberships', function () use ($membershipService, $app) {
    $membership = json_decode($app->request->getBody());
    $id = $membershipService->insert($membership);

    $app->response->setStatus(201);
    $app->response->headers->set('Location', '/v1/memberships/' . $id);
    echo json_encode($membershipService->findById($id, true));
});

$app->options('/v1/memberships/:id', function () use ($app) {
    $app->response->setStatus('Allow', 'GET,DELETE');
});

$app->get('/v1/memberships/:id', function ($id) use ($membershipService, $app) {
    if ($membership = $membershipService->findById($id)) {
        echo json_encode($membership);
    } else {
        $app->response->setStatus(404);
        echo json_encode(array('error' => "Membership $id not found"));
    }
});

$app->delete('/v1/memberships/:id', function ($id) use ($membershipService, $app) {
    if ($membership = $membershipService->findById($id)) {
        $membershipService->delete($id);
        $app->response->setStatus(204);
    } else {
        $app->response->setStatus(404);
        echo json_encode(array('error' => "Membership $id not found"));
    }
});


// Rounds

$app->options('/v1/rounds', function () use ($app) {
    $app->response->setStatus('Allow', 'POST');
});

$app->post('/v1/rounds', function () use ($roundService, $app) {
    $round = json_decode($app->request->getBody());
    $id = $roundService->insert($round);

    $app->response->setStatus(201);
    $app->response->headers->set('Location', '/v1/rounds/' . $id);
    echo json_encode($roundService->findById($id));
});

$app->options('/v1/rounds/:id', function () use ($app) {
    $app->response->setStatus('Allow', 'GET,DELETE');
});

$app->get('/v1/rounds/:id', function ($id) use ($roundService, $app) {
    if ($round = $roundService->findById($id)) {
        echo json_encode($round);
    } else {
        $app->response->setStatus(404);
        echo json_encode(array('error' => "Round $id not found"));
    }
});

$app->delete('/v1/rounds/:id', function ($id) use ($roundService, $app) {
    if ($round = $roundService->findById($id)) {
        $roundService->delete($id);
        $app->response->setStatus(204);
    } else {
        $app->response->setStatus(404);
        echo json_encode(array('error' => "Round $id not found"));
    }
});

// Orders

$app->options('/v1/rounds/:roundId/orders', function () use ($app) {
    $app->response->setStatus('Allow', 'GET,POST');
});

$app->get('/v1/rounds/:roundId/orders', function ($roundId) use ($roundService, $orderService, $app) {
    if ($round = $roundService->findById($roundId)) {
        echo json_encode($orderService->findAllForRound($roundId));
    } else {
        $app->response->setStatus(404);
        echo json_encode(array('error' => "Round $roundId not found"));
    }
});

$app->post('/v1/rounds/:roundId/orders', function ($roundId) use ($roundService, $orderService, $app) {
    $order = json_decode($app->request->getBody());
    if ($round = $roundService->findById($roundId)) {
        $id = $orderService->insert($order);
        $app->response->setStatus(201);
        echo json_encode($orderService->findById($id));
    } else {
        $app->response->setStatus(404);
        echo json_encode(array('error' => "Round $roundId not found"));
    }
});

$app->get('/v1/rounds/:roundId/orders/:id', function ($roundId, $id) use ($orderService, $app) {
    if ($order = $orderService->findById($id)) {
        echo json_encode($order);
    } else {
        $app->response->setStatus(404);
        echo json_encode(array('error' => "Order $id not found"));
    }
});

$app->put('/v1/rounds/:roundId/orders/:id', function ($roundId, $id) use ($orderService, $app) {
    $order = json_decode($app->request->getBody());
    if ($order = $orderService->findById($id)) {
        $orderService->update($order);
        echo json_encode($orderService->findById($id));
    } else {
        $app->response->setStatus(404);
        echo json_encode(array('error' => "Order $id not found"));
    }
});

$app->options('/v1/rounds/:roundId/orders/:id', function () use ($app) {
    $app->response->setStatus('Allow', 'GET');
});

$app->delete('/v1/rounds/:roundId/orders/:id', function ($roundId, $id) use ($orderService, $app) {
    if ($order = $orderService->findById($id)) {
        $orderService->delete($id);
        $app->response->setStatus(204);
    } else {
        $app->response->setStatus(404);
        echo json_encode(array('error' => "Order $id not found"));
    }
});

$app->options('/v1/reset-password', function () use ($app) {
    $app->response->setStatus('Allow', 'POST');
});

$app->post('/v1/reset-password', function () use ($userService, $app) {
    $data = json_decode($app->request->getBody());
    if ($user = $userService->findByEmail($data->email)) {
        $userService->resetPassword($user);
        $app->response->setStatus(204);
    } else {
        $app->response->setStatus(404);
        echo json_encode(array('error' => "User with email address $email not found"));
    }
});

$app->run();
