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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');

if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}

$timeout = config::byKey('timeout', 'raw_network', null);
if ($timeout === null || !is_numeric($timeout)) {
    config::save('timeout', 300, 'raw_network');
}

?>

<form class="form-horizontal">
    <fieldset>
        <div class="form-group">
            <label class="col-lg-3 control-label">{{Temps entre deux commandes (en millisecondes}}</label>
            <div class="col-lg-9">
                <input type="text" class="configKey form-control" data-l1key="timeout" />
            </div>
        </div>
    </fieldset>
</form>
