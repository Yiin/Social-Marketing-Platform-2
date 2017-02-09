<?php

namespace App\Interfaces;

use App\Models\Queue;
use App\Models\SocialMediaService;

/**
 * Interface SocialMediaServiceInterface
 * @package App\Interfaces
 */
interface SocialMediaServiceInterface
{
    /**
     * @param $username
     * @param $password
     * @return boolean
     */
    public function login($username, $password);

    /**
     * @return array
     */
    public function groups();

    /**
     * @param array $postData
     * @param SocialMediaService $socialMediaService
     * @return Queue
     */
    public function post(array $postData, SocialMediaService $socialMediaService);
}