<?php

/*
* This file is part of the NextDom software (https://github.com/NextDom or http://nextdom.github.io).
* Copyright (c) 2018 NextDom.
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, version 2.
*
* This program is distributed in the hope that it will be useful, but
* WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
* General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program. If not, see <http://www.gnu.org/licenses/>.
*/


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
