<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Base controller extended by all controllers.
 * It contains some generic methods.
 *
 */
class ControllerBase extends Controller {

    protected function trans($msg, $params = null) {
        $trans = $this->get('translator')->trans($msg);

        if ($params) {
            foreach ($params as $k => $value) {
                $trans = str_replace($k, $value, $trans);
            }
        }

        return $trans;
    }

    /**
     * Check if the user is allowed to access a resource with a specific level.
     * If the user is not, throw a "403 Forbiden" exception.
     * @param type $resource The resource to check. (see: Resource)
     * @param type $accessLevel The access level. (see: AccessLevel)
     */
    protected function checkRight($resource, $accessLevel) {
        $this->get('security_context')->checkRight($resource, $accessLevel);
    }

    /**
     * Check if the user is allowed to access a resource with a specific level.
     * @param type $resource The resource to check. (see: Resource)
     * @param type $accessLevel The access level. (see: AccessLevel)
     * @return TRUE if the user is allowed, else FALSE.
     */
    protected function isAllowed($resource, $accessLevel) {
        return $this->get('security_context')->isGranted($resource, $accessLevel);
    }

}
