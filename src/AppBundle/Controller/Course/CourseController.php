<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 08/03/18
 * Time: 09:48
 */

namespace AppBundle\Controller\Course;


// Required dependencies for Controller and Annotations
use \AppBundle\Controller\ControllerBase;
use AppBundle\Form\Type\Course\CourseType;
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
use AppBundle\Entity\Course\Course;
use AppBundle\Entity\Preparation\Preparation;

class CourseController extends ControllerBase {


    /**
     * @Operation(
     *     tags={"Course"},
     *     summary="Get next step course by identifier.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type="\AppBundle\Entity\Course\Course")
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"base", "course", "product-position"})
     * @Rest\Get("/courses/{preparationId}")
     */
    public function getNextStepCourseAction(Request $request) {

        $em = $this->getDoctrine()->getManager();


        $preparation = $em->getRepository(Preparation::class)->find($request->get('preparationId'));


        if(empty($preparation)) {
            throw new NotFoundHttpException($this->trans('preparation.error.notFound'));
        }


        $course = $em->getRepository(Course::class)->findBy(
            array(
                'preparation' => $preparation,
                'stepValidated' => false,
            ),
            array("createdAt" => "ASC"),
            1
        );

        if (empty($course)) {
            throw new NotFoundHttpException($this->trans('course.error.notFound'));
        }

        return $course;
    }


    /**
     * @Operation(
     *     tags={"Course"},
     *     summary="Partialy update the informations of a course.",
     *     @SWG\Parameter(
     *         name="body", in="body", required=true,
     *         @Model(type=CourseType::class)
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="OK",
     *         @Model(type="\AppBundle\Entity\Course\Course")
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
     * @Rest\View(serializerGroups={"base", "course"})
     * @Rest\Patch("/courses/update/{id}")
     */
    public function patchCourseAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $course = $em->getRepository(Course::class)
            ->find($request->get('id'));

        if (empty($course)) {
            throw new NotFoundHttpException($this->trans('course.error.notFound'));
        }

        $form = $this->createForm(CourseType::class, $course, []);

        $form->submit($request->request->all(), false);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($course);
            $em->flush();

            return $course;
        } else {
            return $form;
        }
    }



}