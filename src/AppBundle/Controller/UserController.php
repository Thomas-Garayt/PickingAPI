<?php

namespace AppBundle\Controller;

use Symfony\Component\Validator\Constraints\NotBlank;
use \AppBundle\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Request\ParamFetcher;

// Exception
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

// Entity
use AppBundle\Entity\User;
use AppBundle\Entity\User\UserChangePassword;

// Form
use AppBundle\Form\Type\UserType;

class UserController extends ControllerBase {

    /**
     * @Operation(
     *     tags={"User"},
     *     summary="Get the list of users.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type=User::class, groups={"base", "user"})
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"base", "user"})
     * @Rest\Get("/users")
     */
    public function getUsersAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findAll();

        return $users;
    }

    /**
     * @Operation(
     *     tags={"User"},
     *     summary="Get a user by identifier.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type=User::class, groups={"base", "user"})
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"base", "user"})
     * @Rest\Get("/users/{id}")
     */
    public function getUserAction(Request $request) {
        $user = $this->getDoctrine()->getManager()
                ->getRepository(User::class)
                ->find($request->get('id'));

        if (empty($user)) {
            throw $this->getUserNotFoundException();
        }

        return $user;
    }

    /**
     * @Operation(
     *     tags={"User"},
     *     summary="Add a new user.",
     *     @SWG\Parameter(
     *         name="body", in="body", required=true,
     *         @Model(type=UserType::class)
     *     ),
     *     @SWG\Response(
     *         response="201",
     *         description="Created",
     *         @Model(type=User::class, groups={"base", "user"})
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Bad Request"
     *     )
     * )
     *
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"base", "user"})
     * @Rest\Post("/users")
     */
    public function postUsersAction(Request $request) {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, ['validation_groups' => ['Default', 'New']]);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $encoder = $this->get('security.password_encoder');
            $encodedPassword = $encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($encodedPassword);

            $em = $this->getDoctrine()->getManager();

            $em->persist($user);
            $em->flush();
            return $user;
        } else {
            return $form;
        }
    }

    /**
     * @Operation(
     *     tags={"User"},
     *     summary="Remove a user.",
     *     @SWG\Response(
     *         response="204",
     *         description="Returned when successful"
     *     )
     * )
     *
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/users/{id}")
     */
    public function removeUserAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($request->get('id'));

        if (empty($user)) {
            throw $this->getUserNotFoundException();
        }
        $em->remove($user);
        $em->flush();
    }

    /**
     * @Operation(
     *     tags={"User"},
     *     summary="Partialy update the informations of a user.",
     *     @SWG\Parameter(
     *         name="body", in="body", required=true,
     *         @Model(type=UserType::class)
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type=User::class, groups={"base", "user"})
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"base", "user"})
     * @Rest\Patch("/users/{id}")
     */
    public function patchUserAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($request->get('id'));

        if (empty($user)) {
            throw $this->getUserNotFoundException();
        }

        $form = $this->createForm(UserType::class, $user);

        $form->submit($request->request->all(), false);

        if ($form->isValid()) {

            if (!empty($user->getPlainPassword())) {
                $encoder = $this->get('security.password_encoder');
                $encoded = $encoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($encoded);
            }

            $em->merge($user);
            $em->flush();
            return $user;
        } else {
            return $form;
        }
    }

    private function getUserNotFoundException() {
        return new NotFoundHttpException($this->trans('user.error.notFound'));
    }

}
