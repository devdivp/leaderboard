<?php

namespace App\Controller;

use App\Http\Request;
use App\Http\Response;

class LeaderboardController extends Controller
{
    /**
     * Handles the request
     *
     * @param Request $request
     * @return Response
     */
    public function execute(Request $request): Response
    {
        if ($request->getEndpoint() === "leaderboard" && $request->getMethod() === "GET") {
            return $this->indexAction($request);
        } elseif ($request->getEndpoint() === "leaderboard/points/plus" && $request->getMethod() === "GET") {
            return $this->plusAction($request);
        } elseif ($request->getEndpoint() === "leaderboard/points/minus" && $request->getMethod() === "GET") {
            return $this->minusAction($request);
        } elseif ($request->getEndpoint() === "leaderboard/user" && $request->getMethod() === "GET") {
            return $this->infoAction($request);
        } else {
            return $this->response(['Error' => true, 'ErrorMessage' => 'Method not allowed.'], 'application/json', 405);
        }
    }

    /**
     * Generates Leaderboard
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        $leaderboard = $this->em()->getRepository('App\Entity\User')
            ->getLeaderboardData();

        $data = $leaderboard ? $leaderboard : '{}';

        return $this->response($data, 'application/json', 200);
    }

    /**
     * Increment points
     *
     * @param Request $request
     * @return Response
     */
    public function plusAction(Request $request): Response
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

        $points = $userById->getPoints();
        $newPoints = ++$points;
        $userById->setPoints($newPoints);

        $this->em()->persist($userById);
        $this->em()->flush();

        return $this->response(['success' => true], 'application/json', 200);
    }

    /**
     * Decrement points
     *
     * @param Request $request
     * @return Response
     */
    public function minusAction(Request $request): Response
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

        $points = $userById->getPoints();

        if ($points === 0) {
            return $this->response(['error' => true, 'errorBag' => 'Points reached the minimum limit of 0.'], 'application/json', 400);
        }

        $newPoints = --$points;
        $userById->setPoints($newPoints);

        $this->em()->persist($userById);
        $this->em()->flush();

        return $this->response(['success' => true], 'application/json', 200);
    }

    /**
     * Leaderboard's User info.
     *
     * @param Request $request
     * @return Response
     */
    public function infoAction(Request $request): Response
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

        $data = [
            'id' => $userById->getId(),
            'name' => $userById->getName(),
            'age' => $userById->getAge(),
            'points' => $userById->getPoints(),
            'address' => $userById->getAddress()
        ];

        return $this->response($data, 'application/json', 200);
    }
}
