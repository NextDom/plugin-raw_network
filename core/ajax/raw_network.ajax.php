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

try {
    require_once __DIR__ . '/../../../../core/php/core.inc.php';
    require_once __DIR__ . '/../php/raw_network_utils.inc.php';

    include_file('core', 'authentification', 'php');

    if (!isConnect('admin')) {
        throw new Exception(__('401 - Accès non autorisé', __FILE__));
    }

    ajax::init();

    /**
     * Test packet
     */
    if (init('action') == 'test') {
        $result = false;
        $eqLogicId = init('eqLogic_id');
        /** @var raw_network $eqLogic */
        $eqLogic = eqLogic::byId($eqLogicId);
        if (is_object($eqLogic) && get_class($eqLogic) === 'raw_network') {
            $eqLogicConfiguration = $eqLogic->getConfiguration(['ip', 'port']);
            $eqLogic->checkEqLogicConfiguration($eqLogicConfiguration);
            $protocol = init('protocol');
            $data = init('data');
            $result = raw_network_utils::sendPacket($eqLogicConfiguration['ip'], $eqLogicConfiguration['port'], $protocol, $data);
        }
        ajax::success($result);
    }

    throw new Exception(__('Aucune méthode correspondante à : ', __FILE__) . init('action'));
} catch (Exception $e) {
    ajax::error(displayExeption($e), $e->getCode());
}
