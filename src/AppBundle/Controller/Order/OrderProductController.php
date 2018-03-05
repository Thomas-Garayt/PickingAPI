<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 05/03/18
 * Time: 18:22
 */

namespace AppBundle\Controller\Order;


use AppBundle\Controller\ControllerBase;
use AppBundle\Entity\Order\OrderProduct;
use Symfony\Component\HttpFoundation\Request;

// Exception
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderProductController extends ControllerBase {

    /**
     * @Operation(
     *     tags={"OrderProduct"},
     *     summary="Get order products list by order identifier.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type="\AppBundle\Entity\Order\OrderProduct")
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"base", "order_product", "order", "product"})
     * @Rest\Get("/orderproducts/order/{orderId}")
     */
    public function getOrderProductsByOrderIdAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository(OrderProduct::class)->findByOrderId($request->get('orderId'));

        if (empty($products)) {
            throw new NotFoundHttpException($this->trans('order_product.error.notFound'));
        }

        return $products;
    }


    /**
     * @Operation(
     *     tags={"OrderProduct"},
     *     summary="Get order products by product identifier.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type="\AppBundle\Entity\Order\OrderProduct")
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"base", "order_product", "order", "product"})
     * @Rest\Get("/orderproducts/product/{productId}")
     */
    public function getOrderProductsByProductIdAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository(OrderProduct::class)->findByProductId($request->get('productId'));

        if (empty($products)) {
            throw new NotFoundHttpException($this->trans('order_product.error.notFound'));
        }

        return $products;
    }


    /**
     * @Operation(
     *     tags={"OrderProduct"},
     *     summary="Get uncompleted order products list.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type="\AppBundle\Entity\Order\OrderProduct")
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"base", "order_product", "order", "product"})
     * @Rest\Get("/orderproducts/uncompleted")
     */
    public function getOrderProductsUncompletedAction() {

        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository(OrderProduct::class)->findByUncomplete(1);

        if (empty($products)) {
            throw new NotFoundHttpException($this->trans('order_product.error.notFound'));
        }

        return $products;
    }
}