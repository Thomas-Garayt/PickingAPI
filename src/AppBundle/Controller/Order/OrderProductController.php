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
use AppBundle\Form\Type\Order\OrderProductType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Request\ParamFetcher;
use \Doctrine\Common\Collections\ArrayCollection;

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


    /**
     * @Operation(
     *     tags={"OrderProduct"},
     *     summary="Partialy update the informations of an order product.",
     *     @SWG\Parameter(
     *         name="body", in="body", required=true,
     *         @Model(type=OrderProductType::class)
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="OK",
     *         @Model(type="\AppBundle\Entity\Order\OrderProduct")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Not Found"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Bad Request"
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"base", "order-product"})
     * @Rest\Patch("/orderproducts/{id}")
     */
    public function patchOrderProductsAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $order = $em->getRepository(OrderProduct::class)
            ->find($request->get('id'));

        if (empty($order)) {
            throw new NotFoundHttpException($this->trans('order_product.error.notFound'));
        }

        $form = $this->createForm(OrderProductType::class, $order, []);

        $form->submit($request->request->all(), false);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();

            return $order;
        } else {
            return $form;
        }
    }
}