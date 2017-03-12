<?php

namespace App\Services;

use App\Components\DashboardBlock;
use App\Modules\Errors\Models\ErrorLog;

/**
 * Class DashboardService
 * @package App\Services
 */
class DashboardService
{
    /**
     * @var array
     */
    private $blocks = [];

    /**
     * DashboardService constructor.
     */
    public function __construct()
    {
        
    }

    /**
     * @param DashboardBlock $block
     */
    public function addBlock(DashboardBlock $block)
    {
        $this->blocks [] = $block;
    }

    /**
     * @return array
     */
    public function getBlocks()
    {
        return $this->blocks;
    }
}