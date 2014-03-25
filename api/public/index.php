<?php
require_once __DIR__ . "/../bootstrap.php";

use RoundZero\ValidateException;
use RoundZero\TokenAuth;
use RoundZero\Entity\Token;
use RoundZero\Entity\Group;
use RoundZero\Entity\Round;
use RoundZero\Entity\User;
use RoundZero\Service\User as UserService;

$app = new \Slim\Slim();
$app->add(new TokenAuth($entityManager));

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

$app->post('/v1/authorize', function () use ($entityManager, $app) {
    $user = $entityManager->getRepository('RoundZero\Entity\User')->findOneBy(array(
        'email' => $app->request->params('email')
    ));
    if ($user && $user->authenticate($app->request->params('password'))) {
        // Delete old token(s) for user.
        $qb = $entityManager->createQueryBuilder();
        $qb->delete('RoundZero\Entity\Token', 't');
        $qb->andWhere($qb->expr()->eq('t.user', ':user'));
        $qb->setParameter(':user', $user);
        $qb->getQuery()->getResult();

        // Generate new token.
        $token = new Token();
        $token->setUser($user);
        $entityManager->persist($token);
        $entityManager->flush();

        echo json_encode(array(
            'tokenId' => $token->getId(),
            'user' => $user->toArray(),
        ));
    } else {
        $app->response->setStatus(404);
        echo json_encode(array(
            'error' => "Incorrect login",
        ));
    }
});

$app->post('/v1/unauthorize', function () use ($entityManager, $app) {
    $token = $entityManager->getRepository('RoundZero\Entity\Token')->find($app->request->params('token'));
    if ($user && $user->authenticate($app->request->params('password'))) {
        $entityManager->remove($token);
        $entityManager->flush();

        $app->response->setStatus(204);
    } else {
        $app->response->setStatus(403);
        echo json_encode(array(
            'error' => "Invalid token",
        ));
    }
});

// Users

$app->get('/v1/users', function () use ($entityManager, $app) {
    $users = $entityManager->getRepository('RoundZero\Entity\User')->findAll();
    $results = array();
    foreach ($users as $user) {
        $results[] = $user->toArray();
    }
    echo json_encode($results);
});

$app->post('/v1/users', function () use ($entityManager, $app) {
    $user = new User();
    $user->setName($app->request->params('name'));
    $user->setEmail($app->request->params('email'));
    $user->setPassword($app->request->params('password'));

    try {
        $entityManager->persist($user);
        $entityManager->flush();

        $app->response->setStatus(201);
        $app->response->headers->set('Location', '/v1/users/' . $user->getId());
        echo json_encode($user->toArray());

    } catch (ValidateException $e) {
        $app->response->setStatus(400);
        echo json_encode(array(
            'error' => $e->getMessage(),
        ));
    }
});

$app->get('/v1/users/:id', function ($id) use ($entityManager, $app) {
    if ($user = $entityManager->getRepository('RoundZero\Entity\User')->find($id)) {
        echo json_encode($user->toArray());
    } else {
        $app->response->setStatus(404);
        echo json_encode(array(
            'error' => "User $id not found",
        ));
    }
});

$app->put('/v1/users/:id', function ($id) use ($entityManager, $app) {
    if ($user = $entityManager->getRepository('RoundZero\Entity\User')->find($id)) {
        // Check if user being edited is logged in user.
        if (1 || $user == $app->user) {
            if ($name = $app->request->params('name')) {
                $user->setName($name);
            }
            if ($email = $app->request->params('email')) {
                $user->setEmail($email);
            }
            if ($password = $app->request->params('password')) {
                $user->setPassword($password);
            }

            try {
                $entityManager->persist($user);
                $entityManager->flush();

                echo json_encode($user->toArray());

            } catch (ValidateException $e) {
                $app->response->setStatus(400);
                echo json_encode(array(
                    'error' => $e->getMessage(),
                ));
            }
        } else {
            $app->response->setStatus(403);
            echo json_encode(array(
                'error' => "Not authorised to modify other users",
            ));
        }
    } else {
        $app->response->setStatus(404);
        echo json_encode(array(
            'error' => "User $id not found",
        ));
    }
});

$app->delete('/v1/users/:id', function ($id) use ($entityManager, $app) {
    if ($user = $entityManager->getRepository('RoundZero\Entity\User')->find($id)) {
        // Check if user being deleted is logged in user.
        if ($user == $app->user) {
            $entityManager->remove($user);
            $entityManager->flush();

            $app->response->setStatus(204);
        } else {
            $app->response->setStatus(403);
            echo json_encode(array(
                'error' => "Not authorised to remove other users",
            ));
        }
    } else {
        $app->response->setStatus(404);
        echo json_encode(array(
            'error' => "User $id not found",
        ));
    }
});

$app->get('/v1/users/:id/memberships', function ($id) use ($entityManager, $app) {
    if ($user = $entityManager->getRepository('RoundZero\Entity\User')->find($id)) {
        $results = array();
        $userService = new UserService($entityManager);

        foreach ($user->getGroups() as $group) {
            $results[] = array(
                'group' => $group->toArray(),
                'stats' => $userService->getStats($user, $group),
            );
        }

        echo json_encode($results);
    } else {
        $app->response->setStatus(404);
        echo json_encode(array(
            'error' => "User $id not found",
        ));
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

$app->get('/v1/user/:id/order-preferences', function ($id) use ($entityManager, $app) {
    if ($user = $entityManager->getRepository('RoundZero\Entity\User')->find($id)) {
        $results = array();
        foreach ($user->getOrderPreferences() as $order) {
            $results[] = $order->toArray();
        }

        echo json_encode($results);
    } else {
        $app->response->setStatus(404);
        echo json_encode(array(
            'error' => "User $id not found",
        ));
    }
});

$app->post('/v1/user/:id/order-preferences', function () use ($entityManager, $app) {
    if ($user = $entityManager->getRepository('RoundZero\Entity\User')->find($id)) {
        $orderPreference = new OrderPreference();
        $orderPreference->setUser($user);
        $orderPreference->setType($app->request->params('type'));
        $orderPreference->setSugars($app->request->params('sugars'));
        $orderPreference->setMilk($app->request->params('notes'));

        try {
            $entityManager->persist($orderPreference);
            $entityManager->flush();

            $app->response->setStatus(201);
            $app->response->headers->set('Location', '/v1/order-preferences/' . $orderPreference->getId());
            echo json_encode($order->toArray());

        } catch (ValidateException $e) {
            $app->response->setStatus(400);
            echo json_encode(array(
                'error' => $e->getMessage(),
            ));
        }
    } else {
        $app->response->setStatus(404);
        echo json_encode(array(
            'error' => "User $id not found",
        ));
    }
});

$app->get('/v1/order-preferences/:id', function ($id) use ($entityManager, $app) {
    $orderPreference = $entityManager->getRepository('RoundZero\Entity\OrderPreference')->find($id);
    if ($orderPreference) {
        echo json_encode($orderPreference->toArray());
    } else {
        $app->response->setStatus(404);
        echo json_encode(array(
            'error' => "OrderPreference $id not found",
        ));
    }
});

$app->put('/v1/order-preferences/:id', function ($id) use ($entityManager, $app) {
    $orderPreference = $entityManager->getRepository('RoundZero\Entity\OrderPreference')->find($id);
    if ($orderPreference) {
        $orderPreference->setType($app->request->params('type'));
        $orderPreference->setSugars($app->request->params('sugars'));
        $orderPreference->setMilk($app->request->params('notes'));

        try {
            $entityManager->persist($orderPreference);
            $entityManager->flush();

            echo json_encode($orderPreference->toArray());

        } catch (ValidateException $e) {
            $app->response->setStatus(400);
            echo json_encode(array(
                'error' => $e->getMessage(),
            ));
        }
    } else {
        $app->response->setStatus(404);
        echo json_encode(array(
            'error' => "OrderPreference $id not found",
        ));
    }
});

$app->delete('/v1/order-preferences/:id', function ($id) use ($entityManager, $app) {
    $orderPreference = $entityManager->getRepository('RoundZero\Entity\OrderPreference')->find($id);
    if ($orderPreference) {
        $entityManager->remove($orderPreference);
        $entityManager->flush();

        $app->response->setStatus(204);
    } else {
        $app->response->setStatus(404);
        echo json_encode(array(
            'error' => "OrderPreference $id not found",
        ));
    }
});


// Groups

$app->get('/v1/groups', function () use ($entityManager, $app) {
    $groups = $entityManager->getRepository('RoundZero\Entity\Group')->findAll();
    $results = array();
    foreach ($groups as $group) {
        $results[] = $group->toArray();
    }
    echo json_encode($results);
});

$app->post('/v1/groups', function () use ($entityManager, $app) {
    $group = new Group();
    $group->setName($app->request->params('name'));

    // Add current user by default.
    $group->addMember($app->user);

    try {
        $entityManager->persist($group);
        $entityManager->flush();

        $app->response->setStatus(201);
        $app->response->headers->set('Location', '/v1/groups/' . $group->getId());
        echo json_encode($group->toArray());

    } catch (ValidateException $e) {
        $app->response->setStatus(400);
        echo json_encode(array(
            'error' => $e->getMessage(),
        ));
    }
});

$app->get('/v1/groups/:id', function ($id) use ($entityManager, $app) {
    $group = $entityManager->getRepository('RoundZero\Entity\Group')->find($id);
    if ($group) {
        echo json_encode($group->toArray());
    } else {
        $app->response->setStatus(404);
        echo json_encode(array(
            'error' => "Group $id not found",
        ));
    }
});

$app->put('/v1/groups/:id', function ($id) use ($entityManager, $app) {
    $group = $entityManager->getRepository('RoundZero\Entity\Group')->find($id);
    if ($group) {
        $group->setName($app->request->params('name'));

        try {
            $entityManager->persist($group);
            $entityManager->flush();

            echo json_encode($group->toArray());

        } catch (ValidateException $e) {
            $app->response->setStatus(400);
            echo json_encode(array(
                'error' => $e->getMessage(),
            ));
        }
    } else {
        $app->response->setStatus(404);
        echo json_encode(array(
            'error' => "Group $id not found",
        ));
    }
});

$app->delete('/v1/groups/:id', function ($id) use ($entityManager, $app) {
    $group = $entityManager->getRepository('RoundZero\Entity\Group')->find($id);
    if ($group) {
        $entityManager->remove($group);
        $entityManager->flush();

        $app->response->setStatus(204);
    } else {
        $app->response->setStatus(404);
        echo json_encode(array(
            'error' => "Group $id not found",
        ));
    }
});

$app->get('/v1/groups/:id/memberships', function ($id) use ($entityManager, $app) {
    if ($group = $entityManager->getRepository('RoundZero\Entity\Group')->find($id)) {
        $results = array();
        $userService = new UserService($entityManager);

        foreach ($group->getMembers() as $user) {
            $results[] = array(
                'user' => $user->toArray(),
                'stats' => $userService->getStats($user, $group),
            );
        }

        echo json_encode($results);
    } else {
        $app->response->setStatus(404);
        echo json_encode(array(
            'error' => "Group $id not found",
        ));
    }
});

$app->put('/v1/groups/:id/members/:userId', function ($id, $userId) use ($entityManager, $app) {
    if ($group = $entityManager->getRepository('RoundZero\Entity\Group')->find($id)) {
        if ($user = $entityManager->getRepository('RoundZero\Entity\User')->find($userId)) {
            $group->addMember($user);
            $entityManager->persist($group);
            $entityManager->flush();

            $app->response->setStatus(204);
        } else {
            $app->response->setStatus(400);
            echo json_encode(array(
                'error' => "User $userId not found",
            ));
        }
    } else {
        $app->response->setStatus(404);
        echo json_encode(array(
            'error' => "Group $id not found",
        ));
    }
});

$app->delete('/v1/groups/:id/members/:userId', function ($id, $userId) use ($entityManager, $app) {
    if ($group = $entityManager->getRepository('RoundZero\Entity\Group')->find($id)) {
        if ($user = $entityManager->getRepository('RoundZero\Entity\User')->find($userId)) {
            $group->removeMember($user);
            $entityManager->persist($group);
            $entityManager->flush();

            $app->response->setStatus(204);
        } else {
            $app->response->setStatus(404);
            echo json_encode(array(
                'error' => "User $userId not found",
            ));
        }
    } else {
        $app->response->setStatus(404);
        echo json_encode(array(
            'error' => "Group $id not found",
        ));
    }
});

// Rounds

$app->get('/v1/groups/:id/rounds', function () use ($entityManager, $app) {
    if ($group = $entityManager->getRepository('RoundZero\Entity\Group')->find($id)) {
        $results = array();
        foreach ($group->getRounds() as $round) {
            $results[] = $round->toArray();
        }

        echo json_encode($results);

    } else {
        $app->response->setStatus(404);
        echo json_encode(array(
            'error' => "Group $id not found",
        ));
    }
});

$app->post('/v1/groups/:id/rounds', function () use ($entityManager, $app) {
    if ($group = $entityManager->getRepository('RoundZero\Entity\Group')->find($id)) {
        $round = new Round();
        $round->setCreator($app->user);
        $round->setGroup($group);

        try {
            $entityManager->persist($round);
            $entityManager->flush();

            echo json_encode($round->toArray());

        } catch (ValidateException $e) {
            $app->response->setStatus(400);
            echo json_encode(array(
                'error' => $e->getMessage(),
            ));
        }

        echo json_encode($results);

    } else {
        $app->response->setStatus(404);
        echo json_encode(array(
            'error' => "Group $id not found",
        ));
    }
});

$app->get('/v1/rounds/:id', function ($id) use ($entityManager, $app) {
    if ($round = $entityManager->getRepository('RoundZero\Entity\Round')->find($id)) {
        echo json_encode($round->toArray());
    } else {
        $app->response->setStatus(404);
        echo json_encode(array(
            'error' => "Round $id not found",
        ));
    }
});

$app->delete('/v1/rounds/:id', function ($id) use ($entityManager, $app) {
    if ($round = $entityManager->getRepository('RoundZero\Entity\Round')->find($id)) {
        if ($round->getUser() == $app->user) {
            $entityManager->remove($round);
            $entityManager->flush();

            $app->response->setStatus(204);
        } else {
            $app->response->setStatus(404);
            echo json_encode(array(
                'error' => "Not authorised to remove other users' rounds",
            ));
        }
    } else {
        $app->response->setStatus(404);
        echo json_encode(array(
            'error' => "Round $id not found",
        ));
    }
});

$app->get('/v1/rounds/:id/orders', function ($id) use ($entityManager, $app) {
    if ($round = $entityManager->getRepository('RoundZero\Entity\Round')->find($id)) {
        $results = array();
        foreach ($round->getOrders() as $order) {
            $results[] = $order->toArray();
        }

        echo json_encode($results);
    } else {
        $app->response->setStatus(404);
        echo json_encode(array(
            'error' => "Round $id not found",
        ));
    }
});

$app->post('/v1/rounds/:id/orders', function () use ($entityManager, $app) {
    if ($round = $entityManager->getRepository('RoundZero\Entity\Round')->find($id)) {
        $userId = $app->request->params('user');
        if ($user = $entityManager->getRepository('RoundZero\Entity\User')->find($userId)) {
            $order = new Order();
            $order->setRound($round);
            $order->setUser($user);
            $order->setType($app->request->params('type'));
            $order->setSugars($app->request->params('sugars'));
            $order->setMilk($app->request->params('notes'));

            try {
                $entityManager->persist($order);
                $entityManager->flush();

                $app->response->setStatus(201);
                $app->response->headers->set('Location', '/v1/orders/' . $order->getId());
                echo json_encode($order->toArray());

            } catch (ValidateException $e) {
                $app->response->setStatus(400);
                echo json_encode(array(
                    'error' => $e->getMessage(),
                ));
            }
        } else {
            $app->response->setStatus(400);
            echo json_encode(array(
                'error' => "User $userId not found",
            ));
        }
    } else {
        $app->response->setStatus(404);
        echo json_encode(array(
            'error' => "Round $id not found",
        ));
    }
});

$app->get('/v1/orders/:id', function ($id) use ($entityManager, $app) {
    $order = $entityManager->getRepository('RoundZero\Entity\Order')->find($id);
    if ($order) {
        echo json_encode($order->toArray());
    } else {
        $app->response->setStatus(404);
        echo json_encode(array(
            'error' => "Order $id not found",
        ));
    }
});

$app->put('/v1/orders/:id', function ($id) use ($entityManager, $app) {
    $order = $entityManager->getRepository('RoundZero\Entity\Order')->find($id);
    if ($order) {
        $order->setType($app->request->params('type'));
        $order->setSugars($app->request->params('sugars'));
        $order->setMilk($app->request->params('notes'));

        try {
            $entityManager->persist($order);
            $entityManager->flush();

            echo json_encode($order->toArray());

        } catch (ValidateException $e) {
            $app->response->setStatus(400);
            echo json_encode(array(
                'error' => $e->getMessage(),
            ));
        }
    } else {
        $app->response->setStatus(404);
        echo json_encode(array(
            'error' => "Order $id not found",
        ));
    }
});

$app->delete('/v1/orders/:id', function ($id) use ($entityManager, $app) {
    $order = $entityManager->getRepository('RoundZero\Entity\Order')->find($id);
    if ($order) {
        $entityManager->remove($order);
        $entityManager->flush();

        $app->response->setStatus(204);
    } else {
        $app->response->setStatus(404);
        echo json_encode(array(
            'error' => "Order $id not found",
        ));
    }
});

$app->run();
