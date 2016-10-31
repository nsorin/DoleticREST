<?php

namespace UABundle\Controller;


use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use UABundle\Entity\Delivery;
use UABundle\Entity\Project;
use UABundle\Entity\Task;
use UABundle\Form\DeliveryType;

class DeliveryController extends FOSRestController
{

    /**
     * Get all the deliveries
     * @return array
     *
     * @ApiDoc(
     *  section="Delivery",
     *  description="Get all deliveries",
     *  statusCodes={
     *         200="Returned when successful"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "need validations" = "#ff0000"
     *  }
     * )
     *
     * @View()
     * @Get("/deliveries")
     */
    public function getDeliveriesAction()
    {

        $deliveries = $this->getDoctrine()->getRepository("UABundle:Delivery")
            ->findAll();

        return array('deliveries' => $deliveries);
    }

    /**
     * Get all the deliveries at the end of a task
     * @param Task $task
     * @return array
     *
     * @ApiDoc(
     *  section="Delivery",
     *  description="Get all deliveries at the end of a task",
     *  statusCodes={
     *         200="Returned when successful"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "need validations" = "#ff0000"
     *  }
     * )
     *
     * @View()
     * @ParamConverter("task", class="UABundle:Task")
     * @Get("/deliveries/task/{id}", requirements={"id" = "\d+"})
     */
    public function getDeliveriesByTaskAction(Task $task)
    {

        $deliveries = $this->getDoctrine()->getRepository("UABundle:Delivery")
            ->findBy(['task' => $task]);

        return array('deliveries' => $deliveries);
    }

    /**
     * Get all the deliveries at the end of a task
     * @param Project $project
     * @return array
     *
     * @ApiDoc(
     *  section="Delivery",
     *  description="Get all deliveries at the end of a task",
     *  statusCodes={
     *         200="Returned when successful"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "need validations" = "#ff0000"
     *  }
     * )
     *
     * @View()
     * @ParamConverter("project", class="UABundle:Project")
     * @Get("/deliveries/project/{id}", requirements={"id" = "\d+"})
     */
    public function getDeliveriesByProjectAction(Project $project)
    {

        $deliveries = $this->getDoctrine()->getRepository("UABundle:Delivery")
            ->findByProject($project);

        return array('deliveries' => $deliveries);
    }

    /**
     * Get a delivery by ID
     * @param Delivery $delivery
     * @return array
     *
     * @ApiDoc(
     *  section="Delivery",
     *  description="Get a delivery",
     *  requirements={
     *      {
     *          "name"="delivery",
     *          "dataType"="string",
     *          "requirement"="*",
     *          "description"="delivery id"
     *      }
     *  },
     *  statusCodes={
     *         200="Returned when successful"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "need validations" = "#ff0000"
     *  }
     * )
     *
     * @View()
     * @ParamConverter("delivery", class="UABundle:Delivery")
     * @Get("/delivery/{id}", requirements={"id" = "\d+"})
     */
    public function getDeliveryAction(Delivery $delivery)
    {

        return array('delivery' => $delivery);

    }

    /**
     * Create a new Delivery
     * @var Request $request
     * @return View|array
     *
     * @ApiDoc(
     *  section="Delivery",
     *  description="Create a new Delivery",
     *  input="UABundle\Form\DeliveryType",
     *  output="UABundle\Entity\Delivery",
     *  statusCodes={
     *         200="Returned when successful"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "need validations" = "#ff0000"
     *  },
     *  views = { "premium" }
     * )
     *
     * @View()
     * @Post("/delivery")
     */
    public function postDeliveryAction(Request $request)
    {
        $delivery = new Delivery();
        $form = $this->createForm(new DeliveryType(), $delivery);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($delivery);
            $em->flush();

            return array("delivery" => $delivery);

        }

        return array(
            'form' => $form,
        );
    }

    /**
     * Edit a Delivery
     * Put action
     * @var Request $request
     * @var Delivery $delivery
     * @return array
     *
     * @ApiDoc(
     *  section="Delivery",
     *  description="Edit a Delivery",
     *  requirements={
     *      {
     *          "name"="delivery",
     *          "dataType"="string",
     *          "requirement"="*",
     *          "description"="delivery id"
     *      }
     *  },
     *  input="UABundle\Form\DeliveryType",
     *  output="UABundle\Entity\Delivery",
     *  statusCodes={
     *         200="Returned when successful"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "need validations" = "#ff0000"
     *  },
     *  views = { "premium" }
     * )
     *
     * @View()
     * @ParamConverter("delivery", class="UABundle:Delivery")
     * @Post("/delivery/{id}")
     */
    public function putDeliveryAction(Request $request, Delivery $delivery)
    {
        $form = $this->createForm(new DeliveryType(), $delivery);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($delivery);
            $em->flush();

            return array("delivery" => $delivery);
        }

        return array(
            'form' => $form,
        );
    }

    /**
     * Delete a Delivery
     * Delete action
     * @var Delivery $delivery
     * @return array
     *
     * @View()
     * @ParamConverter("delivery", class="UABundle:Delivery")
     * @Delete("/delivery/{id}")
     */
    public function deleteDeliveryAction(Delivery $delivery)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($delivery);
        $em->flush();

        return array("status" => "Deleted");
    }

}