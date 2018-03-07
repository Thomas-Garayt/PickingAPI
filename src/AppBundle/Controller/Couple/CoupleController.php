<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 06/03/18
 * Time: 22:06
 */

namespace AppBundle\Controller\Couple;


use AppBundle\Controller\ControllerBase;
use AppBundle\Entity\Couple\Couple;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Request\ParamFetcher;
use \Doctrine\Common\Collections\ArrayCollection;

class CoupleController extends ControllerBase {

    /**
     * @Operation(
     *     tags={"Couple"},
     *     summary="Get the list of couple.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type="\AppBundle\Entity\Couple\Couple")
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"base", "couple", "product"})
     * @Rest\Get("/couples");
     */
    public function getCouplesAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $couples = $em->getRepository(Couple::class)->findAll();

        return $couples;
    }


    /**
     * @Operation(
     *     tags={"Couple"},
     *     summary="Get a couple by identifier.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type="\AppBundle\Entity\Couple\Couple")
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"base", "couple", "product"})
     * @Rest\Get("/couples/{id}")
     */
    public function getCoupleAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $couple = $em->getRepository(Couple::class)->find($request->get('id'));

        if (empty($couple)) {
            throw new NotFoundHttpException($this->trans('couple.error.notFound'));
        }

        return $couple;
    }


}
