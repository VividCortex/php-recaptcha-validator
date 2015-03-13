<?php

namespace VividCortex\RecaptchaValidator;

/**
 * Interface ValidatorInterface.
 *
 * @author Ismael Ambrosi<ismael@vividcortex.com>
 */
interface ValidatorInterface
{
    /**
     * @param string $response
     * @param string $clientIp
     *
     * @return boolean
     */
    public function validate($response, $clientIp);
}
