<?php
require_once "../bootstrap.php";

$app = new \Slim\Slim();
$app->response->headers->set('Content-Type', 'application/json');

// Users

$app->get('/v1/users', function () use ($entityManager, $app) {
    $users = $entityManager->getRepository('User')->findAll();
    $results = array();
    foreach ($users as $user) {
        $results[] = $user->toArray();
    }
    echo json_encode($results);
});

$app->post('/v1/users', function () use ($entityManager, $app) {
    $user = new User();
    $user->setName($app->request->params('name'));
    $user->setCreated(new DateTime(date('Y-m-d H:i:s')));

    $entityManager->persist($user);
    $entityManager->flush();

    echo json_encode($user->toArray());
});

$app->get('/v1/users/:id', function ($id) use ($entityManager, $app) {
    if ($user = $entityManager->getRepository('User')->find($id)) {
        echo json_encode($user->toArray());
    } else {
        $app->response->setStatus(404);
        echo json_encode(array(
            'error' => "User $id not found",
        ));
    }
});

$app->put('/v1/users/:id', function ($id) use ($entityManager, $app) {
    if ($user = $entityManager->getRepository('User')->find($id)) {
        $user->setName($app->request->params('name'));
        $entityManager->persist($user);
        $entityManager->flush();

        echo json_encode($user->toArray());
    } else {
        $app->response->setStatus(404);
        echo json_encode(array(
            'error' => "User $id not found",
        ));
    }
});

$app->delete('/v1/users/:id', function ($id) use ($entityManager, $app) {
    if ($user = $entityManager->getRepository('User')->find($id)) {
        $entityManager->remove($user);
        $entityManager->flush();

        echo json_encode(true);
    } else {
        $app->response->setStatus(404);
        echo json_encode(array(
            'error' => "User $id not found",
        ));
    }
});

// Rounds

$app->get('/v1/rounds', function () use ($entityManager, $app) {
    $rounds = $entityManager->getRepository('Round')->findAll();
    $results = array();
    foreach ($rounds as $round) {
        $results[] = $round->toArray();
    }
    echo json_encode($results);
});

$app->post('/v1/rounds', function () use ($entityManager, $app) {
    $userId = $app->request->params('creator');

    if ($user = $entityManager->getRepository('User')->find($userId)) {
        $round = new Round();
        $round->setCreator($user);
        $round->setCreated(new DateTime(date('Y-m-d H:i:s')));

        $entityManager->persist($round);
        $entityManager->flush();

        echo json_encode($round->toArray());
    } else {
        $app->response->setStatus(400);
        echo json_encode(array(
            'error' => "User $userId not found",
        ));
    }
});

$app->get('/v1/rounds/:id', function ($id) use ($entityManager, $app) {
    if ($round = $entityManager->getRepository('Round')->find($id)) {
        echo json_encode($round->toArray());
    } else {
        $app->response->setStatus(404);
        echo json_encode(array(
            'error' => "Round $id not found",
        ));
    }
});

$app->put('/v1/rounds/:id', function ($id) use ($entityManager, $app) {
    if ($round = $entityManager->getRepository('Round')->find($id)) {
        $userId = $app->request->params('creator');
        if ($user = $entityManager->getRepository('User')->find($userId)) {
            $round->setCreator($user);
            $entityManager->persist($round);
            $entityManager->flush();

            echo json_encode($round->toArray());
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

$app->delete('/v1/rounds/:id', function ($id) use ($entityManager, $app) {
    if ($round = $entityManager->getRepository('Round')->find($id)) {
        $entityManager->remove($round);
        $entityManager->flush();

        echo json_encode(true);
    } else {
        $app->response->setStatus(404);
        echo json_encode(array(
            'error' => "Round $id not found",
        ));
    }
});

$app->get('/v1/rounds/:id/recipients', function ($id) use ($entityManager, $app) {
    if ($round = $entityManager->getRepository('Round')->find($id)) {
        $results = array();
        foreach ($round->getRecipients() as $user) {
            $results[] = $user->toArray();
        }

        echo json_encode($results);
    } else {
        $app->response->setStatus(404);
        echo json_encode(array(
            'error' => "Round $id not found",
        ));
    }
});

$app->put('/v1/rounds/:id/recipients/:userId', function ($id, $userId) use ($entityManager, $app) {
    if ($round = $entityManager->getRepository('Round')->find($id)) {
        if ($user = $entityManager->getRepository('User')->find($userId)) {
            $round->addRecipient($user);
            $entityManager->persist($round);
            $entityManager->flush();

            echo json_encode(true);
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

$app->delete('/v1/rounds/:id/recipients/:userId', function ($id, $userId) use ($entityManager, $app) {
    if ($round = $entityManager->getRepository('Round')->find($id)) {
        if ($user = $entityManager->getRepository('User')->find($userId)) {
            $round->removeRecipient($user);
            $entityManager->persist($round);
            $entityManager->flush();

            echo json_encode(true);
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

// Groups

$app->get('/v1/groups', function () use ($entityManager, $app) {
    $groups = $entityManager->getRepository('Group')->findAll();
    $results = array();
    foreach ($groups as $group) {
        $results[] = $group->toArray();
    }
    echo json_encode($results);
});

$app->post('/v1/groups', function () use ($entityManager, $app) {
    $group = new Group();
    $group->setName($app->request->params('name'));
    $group->setCreated(new DateTime(date('Y-m-d H:i:s')));

    $entityManager->persist($group);
    $entityManager->flush();

    echo json_encode($group->toArray());
});

$app->get('/v1/groups/:id', function ($id) use ($entityManager, $app) {
    $group = $entityManager->getRepository('Group')->find($id);
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
    $group = $entityManager->getRepository('Group')->find($id);
    if ($group) {
        $group->setName($app->request->params('name'));
        $entityManager->persist($group);
        $entityManager->flush();

        echo json_encode($group->toArray());
    } else {
        $app->response->setStatus(404);
        echo json_encode(array(
            'error' => "Group $id not found",
        ));
    }
});

$app->delete('/v1/groups/:id', function ($id) use ($entityManager, $app) {
    $group = $entityManager->getRepository('Group')->find($id);
    if ($group) {
        $entityManager->remove($group);
        $entityManager->flush();

        echo json_encode(true);
    } else {
        $app->response->setStatus(404);
        echo json_encode(array(
            'error' => "Group $id not found",
        ));
    }
});

$app->get('/v1/groups/:id/members', function ($id) use ($entityManager, $app) {
    if ($group = $entityManager->getRepository('Group')->find($id)) {
        $results = array();
        foreach ($group->getMembers() as $user) {
            $results[] = $user->toArray();
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
    if ($group = $entityManager->getRepository('Group')->find($id)) {
        if ($user = $entityManager->getRepository('User')->find($userId)) {
            $group->addMember($user);
            $entityManager->persist($group);
            $entityManager->flush();

            echo json_encode(true);
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
    if ($group = $entityManager->getRepository('Group')->find($id)) {
        if ($user = $entityManager->getRepository('User')->find($userId)) {
            $group->removeMember($user);
            $entityManager->persist($group);
            $entityManager->flush();

            echo json_encode(true);
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

$app->run();
