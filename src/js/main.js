/* global $ */
import '../../node_modules/bootstrap-sass/assets/javascripts/bootstrap.js';
import 'babel-polyfill'
import { fixedEncodeURIComponent as e } from './util';

//import _ from 'lodash'

// index.html ファイルをコピーする
//require('file-loader?name=../../dist/[name].[ext]!../index.html');

console.log('%c 🌈 Laboradian.com 🌈 %c http://laboradian.com ',
  'background: #2383BF; color: #fff; font-size: 1.4em;',
  'background: #e3e3e3; color: #000; margin-bottom: 1px; padding-top: 4px; padding-bottom: 1px;');

window.onload = () => {

  $('#btnAddRecords').on('click', () => {
    $('#outputAddRecords').html('<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>');
    fetch('?action-type=add', { method: 'GET' })
      .then((response) => {
        if(response.ok) {
          $('#outputAddRecords').text('完了');
        } else {
          $('#outputAddRecords').text('失敗');
        }
      });
  });

  $('#btnClearRecords').on('click', () => {
    $('#outputClearRecords').text('<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>');
    fetch('?action-type=clear', { method: 'GET' })
      .then((response) => {
        if(response.ok) {
          $('#outputClearRecords').text('完了');
        } else {
          $('#outputClearRecords').text('失敗');
        }
      });
  });

  $('#btnGetRecords').on('click', () => {
    $('#outputGetRecords').text('<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>');
    fetch('?action-type=get', { method: 'GET' })
      .then((response) => {
        //console.log(response);
        if(response.ok) {
          const contentType = response.headers.get("content-type");
          if(contentType && contentType.indexOf("application/json") !== -1) {
            return response.json().then((json) => {
              // process your JSON further
              let output = '<table class="table table-bordered"><tbody><tr><th>name</th><th>colour</th><th>calories</th></tr>';
              json.records.forEach((record) => {
                output += `<tr><td>${e(record.name)}</td><td>${e(record.colour)}</td><td class="num">${e(record.calories)}</td></tr>`;
              });
              output += '</tbody></table>';
              $('#outputGetRecords').html(output);
            });
          } else {
            $('#outputGetRecords').text('失敗(not json)');
          }
        } else {
          $('#outputGetRecords').text('失敗');
        }
      })
    ;
  });
};
