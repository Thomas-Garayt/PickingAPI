<?php

namespace AppBundle\Controller\Notification;

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
use AppBundle\Entity\Notification\Notification;
use AppBundle\Entity\Product\ProductPosition;

// Form
use AppBundle\Form\Type\Notification\NotificationType;

class NotificationController extends ControllerBase {

    /**
     * @Operation(
     *     tags={"Notification"},
     *     summary="Get the list of notification.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type="\AppBundle\Entity\Notification\Notification")
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"base", "notification"})
     * @Rest\Get("/notifications");
     */
    public function getNotificationsAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $notifications = $em->getRepository(Notification::class)->findAll();

        return $notifications;
    }

    /**
     * @Operation(
     *     tags={"Notification"},
     *     summary="Get a notification by identifier.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type="\AppBundle\Entity\Notification\Notification")
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"base", "notification"})
     * @Rest\Get("/notifications/{id}")
     */
    public function getNotificationAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $notification = $em->getRepository(Notification::class)->find($request->get('id'));

        if (empty($notification)) {
            throw new NotFoundHttpException($this->trans('notification.error.notFound'));
        }

        return $notification;
    }

    /**
     * @Operation(
     *     tags={"Notification"},
     *     summary="Add a new notification.",
     *     @SWG\Parameter(
     *         name="body", in="body", required=true,
     *         @Model(type=NotificationType::class)
     *     ),
     *     @SWG\Response(
     *         response="201",
     *         description="Created",
     *         @Model(type="\AppBundle\Entity\Notification\Notification")
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Bad Request"
     *     )
     * )
     *
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"base", "notification"})
     * @Rest\Post("/productposition/{id}/notifications")
     */
    public function postNotificationsAction(Request $request) {

        $notification = new Notification();
        $form = $this->createForm(NotificationType::class, $notification);

        $em = $this->getDoctrine()->getManager();

        $form->submit($request->request->all());

        if ($form->isValid()) {


            $productPosition = $em->getRepository(ProductPosition::class)->findOneById($request->get('id'));

            if(!$productPosition) {
                throw new NotFoundHttpException($this->trans('productposition.error.notFound'));
            }

            $notification->setProductPosition($productPosition);
            $em->persist($notification);
            $em->flush();

            return $notification;
        } else {
            return $form;
        }
    }

    /**
     * @Operation(
     *     tags={"Notification"},
     *     summary="Remove a notification.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful"
     *     )
     * )
     *
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/notifications/{id}")
     */
    public function removeNotificationAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $notification = $em->getRepository(Notification::class)->find($request->get('id'));

        if ($notification) {
            $em->remove($notification);
            $em->flush();
        }
    }

    /**
     * @Operation(
     *     tags={"Notification"},
     *     summary="Partialy update the informations of a notification.",
     *     @SWG\Parameter(
     *         name="body", in="body", required=true,
     *         @Model(type=NotificationType::class)
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="OK",
     *         @Model(type="\AppBundle\Entity\Notification\Notification")
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
     * @Rest\View(serializerGroups={"base", "notification"})
     * @Rest\Patch("/notifications/{id}")
     */
    public function patchNotificationAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $notification = $em->getRepository(Notification::class)
                ->find($request->get('id'));

        if (empty($notification)) {
            throw new NotFoundHttpException($this->trans('notification.error.notFound'));
        }

        $form = $this->createForm(NotificationType::class, $notification, []);

        $form->submit($request->request->all(), false);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($notification);
            $em->flush();

            return $notification;
        } else {
            return $form;
        }
    }

}
