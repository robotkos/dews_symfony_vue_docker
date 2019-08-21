<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\PostService;
use App\Service\UsersService;
use Doctrine\Common\Persistence\ObjectManager;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;

//ToDo Uncomment
///**
// * Class ApiPostController
// * @package App\Controller
// * @IsGranted("IS_AUTHENTICATED_FULLY")
// */
final class ApiUserController extends AbstractController
{
    /** @var SerializerInterface */
    private $serializer;

    /** @var PostService */
    private $userService;

    /** @var User */
    private $userEntity;

    /** @var ObjectManager */
    private $manager;

    /**
     * ApiPostController constructor.
     * @param SerializerInterface $serializer
     * @param UsersService $userService
     */
    public function __construct(SerializerInterface $serializer, UsersService $userService, ObjectManager $manager)
    {
        $this->serializer = $serializer;
        $this->userService = $userService;
        $this->manager = $manager;
    }

    /**
     * @Route("/api/users/get_all", name="get_all_users")
     * @return JsonResponse
     */
    public function getAllUsersAction(): JsonResponse
    {
        $userEntities = $this->userService->getAll();
        $users = $this->getUsersFromEntities($userEntities);
        $data = $this->serializer->serialize($users, 'json');
        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @Rest\Post("/api/users/create", name="createUser")
     * @param Request $request
     * @return JsonResponse
     */
    public function createUserAction(Request $request): JsonResponse
    {
        $validator = new EmailValidator();
        $email = $request->request->get('email');
        $displayName = $request->request->get('display_name');
        $pass = $request->request->get('password');
        $pass_repeat = $request->request->get('repeat_password');
        if ($pass === $pass_repeat && $validator->isValid($email, new RFCValidation())) {
            try {
                $userEntity = new User();
                $userEntity->setLogin($email);
                $userEntity->setUName($displayName);
                $userEntity->setPlainPassword($pass);
                $userEntity->setRoles(['ROLE_FOO']);
                $this->manager->persist($userEntity);
                $this->manager->flush();
            } catch (\Throwable $exception) {
                return new JsonResponse('Cant save new data', 500, [], true);
            }
            return new JsonResponse('Saved', 200, [], true);
        }
        return new JsonResponse('Incorrect input data', 500, [], true);
    }

    /**
     * @Rest\Post("/api/users/edit", name="editUser")
     * @param Request $request
     * @return JsonResponse
     */
    public function editUserAction(Request $request): JsonResponse
    {
        $validator = new EmailValidator();
        $email = $request->request->get('email');
        $displayName = $request->request->get('display_name');
        $pass = $request->request->get('password');
        $pass_repeat = $request->request->get('repeat_password');
        $data = $this->userService->findeByEmail($email)[0];
        if (!$data) {
            return new JsonResponse('Not found', 404, [], true);
        }
        if ($pass === $pass_repeat && $validator->isValid($email, new RFCValidation())) {
            try {
                $data->setLogin($email);
                $data->set($displayName);
                $data->setPlainPassword($pass);
                $data->setRoles(['ROLE_FOO']);
                $this->manager->persist($data);
                $this->manager->flush();
            } catch (\Throwable $exception) {
                return new JsonResponse('Cant save new data', 500, [], true);
            }
            return new JsonResponse('Saved', 200, [], true);
        }
        return new JsonResponse('Incorrect input data', 500, [], true);
    }

    /**
     * @param array $userEntities
     * @return array
     */
    private function getUsersFromEntities(array $userEntities): array
    {
        $result = [];
        foreach ($userEntities as $userEntity) {
            $this->userEntity = $userEntity;
            $result[] = [
                'id' => $this->userEntity->getId(),
                'userName' => $this->userEntity->getUsername()
            ];
        }
        return $result;
    }
}