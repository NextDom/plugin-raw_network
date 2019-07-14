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

define('MIN_TCP_PORT', 0);
define('MAX_TCP_PORT', 65535);
define('UDP_PACKET', 'udp');
define('TCP_PACKET', 'tcp');

class raw_network_utils
{
    /**
     * Check if IP is valid or throw exception
     *
     * @param string $ipToCheck IP to check
     *
     * @throws Exception
     */
    public static function checkValidIp(string $ipToCheck)
    {
        // Validate field
        if (!filter_var($ipToCheck, FILTER_VALIDATE_IP)) {
            throw new Exception(__('Adresse IP incorrect', __FILE__));
        }
    }

    /**
     * Check if port is valid or throw exception
     *
     * @param int|string $portToCheck Port to check
     *
     * @throws Exception
     */
    public static function checkValidPort($portToCheck)
    {
        $port = intval($portToCheck);
        if ($portToCheck !== strval($port)) {
            throw new Exception(__('Numéro de port incorect', __FILE__));
        }
        if ($port <= MIN_TCP_PORT && $port > MAX_TCP_PORT) {
            throw new Exception(__('Numéro de port incorect', __FILE__));
        }
    }

    /**
     * Send a packet
     *
     * @param string $ip Target IP
     * @param string|int $port Target port
     * @param string $protocol Protocol type
     * @param string $data Data to send
     *
     * @return bool True on success
     */
    public static function sendPacket(string $ip, $port, string $protocol, string $data): bool
    {
        if ($protocol === UDP_PACKET) {
            return self::sendUdpPacket($ip, intval($port), $data);
        } elseif ($protocol === TCP_PACKET) {
            return self::sendTcpPacket($ip, intval($port), $data);
        }
        return false;
    }

    /**
     * Send UDP packet
     *
     * @param string $ip Target IP
     * @param int $port Target port
     * @param string $msg Message to send (has string)
     *
     * @return bool True when message sent
     *
     * @throws Exception
     */
    private static function sendUdpPacket(string $ip, int $port, string $msg): bool
    {
        $rawMsg = self::preparePacketData($msg);
        $socketRes = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        if ($socketRes !== false) {
            $rawMsgLength = strlen($rawMsg);
            if (socket_sendto($socketRes, $rawMsg, $rawMsgLength, 0, $ip, $port) !== false) {
                socket_close($socketRes);
                return true;
            } else {
                throw new Exception('Connection error');
            }
        } else {
            throw new Exception('Socket error');
        }
    }

    /**
     * Prepare data to send
     * Convert string to raw data
     *
     * @param string $dbPacketData Hexadecimal data in string
     *
     * @return bool|string Prepared data
     */
    private static function preparePacketData(string $dbPacketData)
    {
        $result = str_replace(' ', '', $dbPacketData);
        return hex2bin($result);
    }

    /**
     * Send TCP packet
     *
     * @param string $ip Target IP
     * @param int $port Target port
     * @param string $msg Message to send (has string)
     *
     * @return bool True when message sent
     *
     * @throws Exception
     */
    private static function sendTcpPacket(string $ip, int $port, string $msg): bool
    {
        $rawMsg = self::preparePacketData($msg);
        $socketRes = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socketRes !== false) {
            $rawMsgLength = strlen($rawMsg);
            if (socket_connect($socketRes, $ip, $port) !== false) {
                socket_write($socketRes, $rawMsg, $rawMsgLength);
                socket_close($socketRes);
                return true;
            } else {
                throw new Exception('Connection error');
            }
        } else {
            throw new Exception('Socket error');
        }
    }
}
