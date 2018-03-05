<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 05/03/18
 * Time: 18:01
 */

namespace AppBundle\Controller\Order;


use AppBundle\Controller\ControllerBase;
use AppBundle\Entity\Order\Order;
use Symfony\Component\HttpFoundation\Request;

// Exception
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderController extends ControllerBase {

    /**
     * @Operation(
     *     tags={"Order"},
     *     summary="Get the list of order.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type="\AppBundle\Entity\Order\Order")
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"base", "order", "customer"})
     * @Rest\Get("/orders");
     */
    public function getOrdersAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $orders = $em->getRepository(Order::class)->findAll();

        return $orders;
    }


    /**
     * @Operation(
     *     tags={"Order"},
     *     summary="Get an order by identifier.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type="\AppBundle\Entity\Order\Order")
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"base", "order", "customer"})
     * @Rest\Get("/orders/{id}")
     */
    public function getOrderAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository(Order::class)->find($request->get('id'));

        if (empty($order)) {
            throw new NotFoundHttpException($this->trans('order.error.notFound'));
        }

        return $order;
    }


    /**
     * @Operation(
     *     tags={"Order"},
     *     summary="Get an order by status.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type="\AppBundle\Entity\Order\Order")
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"base", "order", "customer"})
     * @Rest\Get("/orders/status/{name}")
     */
    public function getOrdersByStatusAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository(Order::class)->findByStatus($request->get('name'));

        if (empty($order)) {
            throw new NotFoundHttpException($this->trans('order.error.notFound'));
        }

        return $order;
    }

}