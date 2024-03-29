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

class raw_networkCmd extends cmd
{
    /**
     * Execute the command
     *
     * @param array $_options Execution options
     *
     * @return bool True on success
     *
     * @throws Exception
     */
    public function execute($_options = array()): bool
    {
        $eqLogic = $this->getEqLogic();
        if (is_object($eqLogic)) {
            $eqLogicConfiguration = $eqLogic->getConfiguration(['ip', 'port']);
            if ($eqLogic->checkEqLogicConfiguration($eqLogicConfiguration)) {
                $packetInformations = $this->getConfiguration(['packetData', 'packetProtocol']);
                if (array_key_exists('packetData', $packetInformations) && array_key_exists('packetProtocol', $packetInformations)) {
                    $result = raw_network_utils::sendPacket($eqLogicConfiguration['ip'], $eqLogicConfiguration['port'], $packetInformations['packetProtocol'], $packetInformations['packetData']);
                    if ($result) {
                        usleep(config::byKey('timeout', 'raw_network', 300));
                        return true;
                    }
                    return false;
                }
            } else {
                throw new Exception(__('Bad EqLogic configuration.'));
            }
        } else {
            throw new Exception(__('EqLogic linked to command not found.'));
        }
    }
}
