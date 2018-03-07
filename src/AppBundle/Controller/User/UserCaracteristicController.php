<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 07/03/18
 * Time: 12:32
 */

namespace AppBundle\Controller\User;

// Required dependencies for Controller and Annotations
use \AppBundle\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Request\ParamFetcher;
use \Doctrine\Common\Collections\ArrayCollection;

// Exception
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// Entity
use AppBundle\Entity\User\UserCaracteristic;

class UserCaracteristicController extends ControllerBase {

    /**
     * @Operation(
     *     tags={"UserCaracteristic"},
     *     summary="Get the list of User caracteristic.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type="\AppBundle\Entity\User\UserCaracteristic")
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"base", "user-caracteristic"})
     * @Rest\Get("/usercaracteristics");
     */
    public function getUserCaracteristicsAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $uc = $em->getRepository(UserCaracteristic::class)->findAll();

        return $uc;
    }


    /**
     * @Operation(
     *     tags={"UserCaracteristic"},
     *     summary="Get an User Caracteristic by identifier.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type="\AppBundle\Entity\User\UserCaracteristic")
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"base", "user-caracteristic"})
     * @Rest\Get("/usercaracteristics/{id}")
     */
    public function getUserCaracteristicAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $uc = $em->getRepository(UserCaracteristic::class)->find($request->get('id'));

        if (empty($uc)) {
            throw new NotFoundHttpException($this->trans('user_caracteristic.error.notFound'));
        }

        return $uc;
    }


    /**
     * @Operation(
     *     tags={"UserCaracteristic"},
     *     summary="Update User stamina by identifier.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type="\AppBundle\Entity\User\UserCaracteristic")
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"base", "user-caracteristic"})
     * @Rest\Get("/usercaracteristics/update/stamina/{id}")
     */
    public function getUpdateUserStaminaAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $uc = $em->getRepository(UserCaracteristic::class)->find($request->get('id'));

        if (empty($uc)) {
            throw new NotFoundHttpException($this->trans('user_caracteristic.error.notFound'));
        }


        $currentStamina = $this->calculateCurrentStamina($uc);

        $uc->setCurrentStamina($currentStamina);

        $em->persist($uc);
        $em->flush();

        return $uc;
    }


    /**
     * @param $usercaracteristic
     * @return mixed
     */
    private function calculateCurrentStamina($usercaracteristic) {

        $stamina = $usercaracteristic->getCurrentStamina() * ($usercaracteristic->getStaminaCoefficient() /100);

        $stamina = round($stamina, 0, PHP_ROUND_HALF_DOWN);

        return $stamina;
    }

}