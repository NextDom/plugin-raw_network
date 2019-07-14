<!--
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
-->
<style>
    #add-cmd-form label {
        float: right;
    }

    #add-cmd-form textarea {
        width: 100%;
        height: 15rem;
    }

    #add-cmd-form .error {
        border: dashed red !important;
    }
</style>
<form id="add-cmd-form">
    <div class="row form-group">
        <div class="col-sm-3">
            <label for="cmd-name" class="control-label">{{Nom}}</label>
        </div>
        <div class="col-sm-4">
            <input id="cmd-name" class="form-control" type="text"/>
        </div>
    </div>
    <div class="row form-group">
        <div class="col-sm-3">
            <label for="cmd-packet-protocol" class="control-label">{{Protocole}}</label>
        </div>
        <div class="col-sm-4">
            <select id="cmd-packet-protocol" class="form-control">
                <option value="udp">{{UDP}}</option>
                <option value="tcp">{{TCP}}</option>
            </select>
        </div>
    </div>
    <div class="row form-group">
        <div class="col-sm-3">
            <label for="cmd-packet-data" class="control-label">{{Données}}</label>
        </div>
        <div class="col-sm-4">
            <textarea id="cmd-packet-data" class="form-control"></textarea>
        </div>
        <div class="col-sm-5">
            <textarea id="cmd-packet-data-render" class="form-control" disabled="disabled"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-5">
            <button class="btn btn-success" id="add-cmd">{{Ajouter}}</button>
        </div>
        <div class="col-sm-4">
            <button class="btn btn-warning" id="try-cmd">{{Tester}}</button>
        </div>
    </div>
</form>
<script>
  var eqLogicId = <?php echo init('id'); ?>;

  /**
   * Get ASCII representation of hexadecimal data
   *
   * @param hexData Raw data
   *
   * @returns {string} ASCII representation
   */
  function getConvertedData(hexData) {
    // Remove spaces
    hexData = hexData.replace(/\s+/g, '');
    // Add missing char
    if (hexData.length % 2 === 1) {
      hexData += '0';
    }
    var result = '';
    for (var i = 0; i < hexData.length - 1; i += 2) {
      result += String.fromCharCode(parseInt(hexData[i] + hexData[i + 1], 16));
    }
    return result;
  }

  /**
   * Check if string contains Hexadecimal data (accepts space)
   *
   * @returns {boolean} True if string is valid
   */
  function isValidHexString() {
    var inputHexString = $('#add-cmd-form #cmd-packet-data');
    var hexData = inputHexString.val().toUpperCase();
    if (/^[ 0-9A-F]+$/.test(hexData)) {
      inputHexString.removeClass('error');
      $('#cmd-packet-data-render').val(getConvertedData(hexData));
      return true;
    }
    else {
      inputHexString.addClass('error');
    }
    return false;
  }

  /**
   * Check data from form
   *
   * @returns {boolean} True if data in form is valid
   */
  function isValidData() {
    var cmdNameInput = $('#add-cmd-form #cmd-name');
    if (cmdNameInput.val() !== '') {
      cmdNameInput.removeClass('error');
      if ($(isValidHexString())) {
        return true;
      }
    }
    else {
      cmdNameInput.addClass('error');
    }
    return false;
  }

  /**
   * Add command in the list
   */
  $('#add-cmd').click(function (e) {
    e.preventDefault();
    var cmdName = $('#add-cmd-form #cmd-name').val();
    var cmdPacketProtocol = $('#add-cmd-form #cmd-packet-protocol').val();
    var cmdPacketData = $('#add-cmd-form #cmd-packet-data').val();
    if (isValidData()) {
      var cmdData = {
        name: cmdName,
        type: 'action',
        subType: 'other',
        configuration: {
          packetProtocol: cmdPacketProtocol,
          packetData: cmdPacketData
        }
      };
      addCmdToTable(cmdData);
      $('#md_modal').dialog('close');
    }
  });

  /**
   * Show ASCII representation
   */
  $('#cmd-packet-data').keyup(isValidHexString);

  /**
   * Try to send packet
   */
  $('#try-cmd').click(function (e) {
    e.preventDefault();
    if (isValidHexString()) {
      $.showLoading();
      $.ajax({
        type: 'POST',
        url: 'plugins/raw_network/core/ajax/raw_network.ajax.php',
        data: {
          action: 'test',
          eqLogic_id: eqLogicId,
          protocol: $('#add-cmd-form #cmd-packet-protocol').val(),
          data: $('#add-cmd-form #cmd-packet-data').val()
        },
        dataType: 'json',
        error: function (request, status, error) {
          handleAjaxError(request, status, error);
        },
        success: function (data) {
          console.log(data);
          if (data.state !== 'ok') {
            $('#div_alert').showAlert({message: data.result, level: 'danger'});
            return;
          }
          else {
            if (data.result === true) {
              $('#div_alert').showAlert({message: '{{Envoyé}}', level: 'success'});
            }
          }
          $.hideLoading();
        }
      });
    }
  });
</script>
