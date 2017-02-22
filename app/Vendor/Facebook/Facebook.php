<?php
/**
 * Created by PhpStorm.
 * User: Stanislovas
 * Date: 2017-02-19
 * Time: 8:27
 */

namespace App\Vendor\Facebook;


class Facebook extends \Facebook\Facebook
{
    /**
     * Returns the redirect login helper.
     *
     * @return FacebookRedirectLoginHelper
     */
    public function getRedirectLoginHelper()
    {
        return new FacebookRedirectLoginHelper(
            $this->getOAuth2Client(),
            $this->persistentDataHandler,
            $this->urlDetectionHandler,
            $this->pseudoRandomStringGenerator
        );
    }
}