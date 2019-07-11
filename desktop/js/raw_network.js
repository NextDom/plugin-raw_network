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

/**
 * Add command in the list
 */
function addCmdToTable(cmdData) {
  if (!isset(cmdData)) {
    var cmdData = { configuration: {} };
  }
  if (!isset(cmdData.configuration)) {
    cmdData.configuration = {};
  }
  var cmdRow =
    '<tr class="cmd" data-cmd_id="' + init(cmdData.id) + '">' +
    '  <td>' +
    '    <span class="cmdAttr" data-l1key="id"></span>' +
    '    <span class="cmdAttr hidden" data-l1key="type"></span>' +
    '    <span class="cmdAttr hidden" data-l1key="subType"></span>' +
    '  </td>' +
    '  <td><input class="cmdAttr form-control input-sm" data-l1key="name" placeholder="{{Nom}}"></td>' +
    '  <td><select class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="packetProtocol"><option value="udp">{{UDP}}</option><option value="tcp">{{TCP}}</option></select></td>' +
    '  <td><textarea class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="packetData"></textarea></td>' +
    '  <td><span><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" data-size="mini" data-label-text="{{Afficher}}" checked=""></span></td>' +
    '  <td>' +
    '    <a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fa fa-cogs"></i></a>' +
    '    <a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>' +
    '    <i class="fa fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i>' +
    '  </td>' +
    '</tr>';
  $('#table_cmd tbody').append(cmdRow);
  $('#table_cmd tbody tr:last').setValues(cmdData, ".cmdAttr");
  if (isset(cmdData.type)) {
    $('#table_cmd tbody tr:last .cmdAttr[data-l1key=type]').value(init(cmdData.type));
  }
  jeedom.cmd.changeType($('#table_cmd tbody tr:last'), init(cmdData.subType));
}

/**
 * Show add command wizard in modal
 */
$(".btn.cmdAction").click(function() {
  $('#md_modal').dialog({
    title: "Assistant d'ajout de commande",
    MaxWidth: 800,
    MaxHeight: 800
  });
  $('#md_modal').load('index.php?v=d&plugin=raw_network&modal=add_cmd&id=' + $('.eqLogicAttr[data-l1key=id]').value()).dialog('open');
});

/**
 * Show icon choice modal
 */
$('.btn#choose-icon').on('click', function() {
  chooseIcon(function(choosedIcon) {
    $('.eqLogicAttr[data-l1key=configuration][data-l2key=icon]')
      .empty()
      .append(choosedIcon);
  });
});
