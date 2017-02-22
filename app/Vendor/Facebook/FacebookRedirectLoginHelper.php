<?php
/**
 * Created by PhpStorm.
 * User: Stanislovas
 * Date: 2017-02-19
 * Time: 8:55
 */

namespace App\Vendor\Facebook;


use Facebook\Exceptions\FacebookSDKException;

class FacebookRedirectLoginHelper extends \Facebook\Helpers\FacebookRedirectLoginHelper
{
    /**
     * Validate the request against a cross-site request forgery.
     *
     * @throws FacebookSDKException
     */
    protected function validateCsrf()
    {
        $state = $this->getState();
        if (!$state) {
            throw new FacebookSDKException('Cross-site request forgery validation failed. Required GET param "state" missing.');
        }
        $savedState = $this->persistentDataHandler->get('state');

        if (!$savedState) {
            $savedState = request()->get('state');
        }

        if (!$savedState) {
            throw new FacebookSDKException('Cross-site request forgery validation failed. Required param "state" missing from persistent data.');
        }

        if (\hash_equals($savedState, $state)) {
            return;
        }

        throw new FacebookSDKException('Cross-site request forgery validation failed. The "state" param from the URL and session do not match.');
    }
}