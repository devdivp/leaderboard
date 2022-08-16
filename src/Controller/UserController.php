<?php

namespace App\Controller;

use App\Entity\User;
use App\Http\Request;
use App\Http\Response;

class UserController extends Controller
{
    /**
     * Handles the request
     *
     * @param Request $request
     * @return Response
     */
    public function execute(Request $request): Response
    {
        if ($request->getEndpoint() === "users" && $request->getMethod() === "GET") {
            return $this->indexAction($request);
        } elseif ($request->getEndpoint() === "users/create" && $request->getMethod() === "POST") {
            return $this->createAction($request);
        } elseif ($request->getEndpoint() === "users/delete" && $request->getMethod() === "DELETE") {
            return $this->deleteAction($request);
        } else {
            return $this->response(['error' => true, 'errorBag' => 'Method not allowed.'], 'application/json', 405);
        }
    }

    /**
     * Lists all Users data.
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        $userByUserId = $this->em()->getRepository('App\Entity\User')
            ->findAll();

        $data = $userByUserId ? $userByUserId->getData() : '{}';

        return $this->response($data, 'application/json', 200);
    }

    /**
     * Saves User data.
     *
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $input = json_decode($request->getInput(), true);
        if (empty($input)) {
            return $this->response(['error' => true, 'errorBag' => 'Request fields are required.'], 'application/json', 400);
        }

        $input = $this->sanitize($input);
        $required = ['name', 'age', 'address'];
        $errors = $this->validate($required, $input);

        if (count($errors) > 0) {
            return $this->response(['error' => true, 'errorBag' => $errors], 'application/json', 400);
        }

        $user = new User();
        $user->setName($input['name']);
        $user->setAge($input['age']);
        $user->setPoints(0);
        $user->setAddress($input['address']);

        $this->em()->persist($user);
        $this->em()->flush();

        $data = [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'age' => $user->getAge(),
            'points' => $user->getPoints(),
            'address' => $user->getAddress(),
            'createdAt' => $user->getCreatedAt(),
            'updatedAt' => $user->getUpdatedAt()
        ];
        return $this->response($data, 'application/json', 201);
    }

    /**
     * Deletes User data.
     *
     * @param Request $request
     * @return Response
     */
    public function deleteAction(Request $request): Response
    {
        $id = $request->getUrlParam('id');
        if (!$id) {
            return $this->response(['error' => true, 'errorBag' => 'URL parameter \'id\' not found.'], 'application/json', 400);
        }

        $userById = $this->em()->getRepository('App\Entity\User')
            ->findOneBy(['id' => $id]);
        if (!$userById) {
            return $this->response(['error' => true, 'errorBag' => 'User not found.'], 'application/json', 400);
        }

        $this->em()->remove($userById);
        $this->em()->flush();

        return $this->response(['success' => true], 'application/json', 200);
    }

    private function validate(array $required, array $input): array
    {
        $errorBag = [];

        foreach ($required as $r) {
            if (!isset($input[$r])) {
                $errorBag[] = "The '$r' field is required.";
            }
        }

        if (isset($input['name']) && !is_string($input['name'])) {
            $errorBag[] = "The 'name' field value must be a valid string.";
        }

        if (isset($input['age']) && (!is_int($input['age']) || !($input['age'] > 0))) {
            $errorBag[] = "The 'age' field value must be a valid integer and greater than 0.";
        }

        if (isset($input['points']) && (!is_int($input['points']) || !($input['points'] > 0))) {
            $errorBag[] = "The 'points' field value must be a valid integer and greater than 0.";
        }

        if (isset($input['address']) && !is_string($input['address'])) {
            $errorBag[] = "The 'address' field value must be a valid string.";
        }

        return $errorBag;
    }
}
