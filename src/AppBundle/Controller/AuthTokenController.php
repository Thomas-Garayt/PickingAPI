<?php

namespace AppBundle\Controller;

use \AppBundle\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use Nelmio\ApiDocBundle\Annotation\Operation;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use \AppBundle\Entity\User;
use \AppBundle\Entity\AuthToken;
use \AppBundle\Entity\Credentials;
use \AppBundle\Form\Type\CredentialsType;

/**
 * The controller in charge of authenticate the user and give him a Authentication Token.
 * This token will be used in each request made by the user.
 */
class AuthTokenController extends ControllerBase {

    /**
     * @Operation(
     *     tags={"AuthToken"},
     *     summary="Get a Token to Authenticate the user that can be used access the rest of the api.",
     *     @SWG\Parameter(
     *         name="body", in="body", required=true,
     *         @Model(type=CredentialsType::class)
     *     ),
     *     @SWG\Response(
     *         response="201",
     *         description="Successfully Created",
     *         @Model(type=AuthToken::class, groups={"base", "auth-token"})
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Bad Request"
     *     )
     * )
     *
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"base", "auth-token"})
     * @Rest\Post("/auth-tokens")
     */
    public function postAuthTokensAction(Request $request) {
        $credentials = new Credentials();
        $form = $this->createForm(CredentialsType::class, $credentials);

        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        $em = $this->getDoctrine()->getManager();

        if (filter_var($credentials->getLogin(), FILTER_VALIDATE_EMAIL)) {
            $user = $em->getRepository(User::class)
                    ->findOneByEmail($credentials->getLogin());
        } else {
            $user = $em->getRepository(User::class)
                    ->findOneByUsername($credentials->getLogin());
        }

        // If user doesn't exist or password is invalid.
        if (!$user || !$this->isPasswordValid($user, $credentials)) {
            throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException($this->trans('auth.error.invalidCredentials'));
        }

        $authToken = $this->createToken($user);

        $em->persist($authToken);
        $em->flush();

        return $authToken;
    }

    /**
     * @Operation(
     *     tags={"AuthToken"},
     *     summary="Remove a specific Auth Token to logout the user from the api.",
     *     @SWG\Response(
     *         response="204",
     *         description="No Content"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Bad Request"
     *     )
     * )
     *
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/auth-tokens/{id}")
     */
    public function removeAuthTokenAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $authToken = $em->getRepository(AuthToken::class)
                ->find($request->get('id'));
        /* @var $authToken AuthToken */

        $connectedToken = $this->get('security.token_storage')->getToken();
        $connectedUser = $connectedToken ? $connectedToken->getUser() : null;

        if ($authToken && $connectedUser && $authToken->getUser()->getId() === $connectedUser->getId()) {
            $em->remove($authToken);
            $em->flush();
        } else {
            throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException();
        }
    }

    private function isPasswordValid(\AppBundle\Entity\User $user, Credentials $credentials) {
        $encoder = $this->get('security.password_encoder');
        return $encoder->isPasswordValid($user, $credentials->getPassword());
    }

    private function createToken(\AppBundle\Entity\User $user) {

        // Delete all old token of the user
        $this->cleanTokenOfUser();

        $authToken = new AuthToken();
        $authToken->setValue(base64_encode(random_bytes(50)));
        $authToken->setCreatedAt(new \DateTime('now'));
        $authToken->setUser($user);
        //$authToken->setSecurityContext($this->get('security_context')->makeSecurityContext($user));

        return $authToken;
    }

    private function cleanTokenOfUser() {
        $em = $this->getDoctrine()->getManager();

        $authToken = $em->getRepository(AuthToken::class)->findAll();
        /* @var $authToken AuthToken[] */

        $now = new \DateTime();
        foreach ($authToken as $token) {
            if ($token->getCreatedAt()->diff($now)->days > 1) {
                $em->remove($token);
                $em->flush();
            }
        }
    }

}
