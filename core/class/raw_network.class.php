<?php

/* -------------------------------------------------------------------- */
/* Copyright (C) 2018 - 2019 - NextDom - www.nextdom.org                */
/* This file is part of nextdom.                                        */
/*                                                                      */
/* nextdom is free software: you can redistribute it and/or modify      */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation, either version 3 of the License, or    */
/* (at your option) any later version.                                  */
/*                                                                      */
/* nextdom is distributed in the hope that it will be useful,           */
/* but WITHOUT ANY WARRANTY; without even the implied warranty of       */
/* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        */
/* GNU General Public License for more details.                         */
/*                                                                      */
/* You should have received a copy of the GNU General Public License    */
/* along with nextdom.  If not, see <http://www.gnu.org/licenses/>.     */
/* -------------------------------------------------------------------- */


require_once __DIR__ . '/../../../../core/php/core.inc.php';
require_once 'raw_networkCmd.class.php';
require_once __DIR__ . '/../php/raw_network_utils.inc.php';

class raw_network extends eqLogic
{
    /**
     * Check if IP and port is valid
     *
     * @param array $eqLogicConfiguration Data from eqLogic
     *
     * @return bool True if parameters are valid
     *
     * @throws Exception
     */
    public function checkEqLogicConfiguration($eqLogicConfiguration): bool
    {
        // Check configuration data exists
        if (!array_key_exists('ip', $eqLogicConfiguration) || !array_key_exists('port', $eqLogicConfiguration)) {
            return false;
        }
        raw_network_utils::checkValidIp($eqLogicConfiguration['ip']);
        raw_network_utils::checkValidPort($eqLogicConfiguration['port']);
        return true;
    }

    /**
     * Initialize eqLogic data
     */
    public function preInsert()
    {
        $this->setConfiguration('icon', '<i class="icon fa fa-star"></i>');
    }

    /**
     * Validate data
     */
    public function preUpdate()
    {
        raw_network_utils::checkValidIp($this->getConfiguration('ip'));
        raw_network_utils::checkValidPort($this->getConfiguration('port'));
    }
}
