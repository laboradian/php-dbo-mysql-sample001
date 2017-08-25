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
    $('#loadingAddRecords').removeClass('hidden');

    const data = new FormData();
    data.append('action-type', 'add');
    data.append('csrf_token', $('#csrf_token').val());

    fetch('', { method: 'POST', body: data, credentials: 'same-origin' })
      .then((response) => {
        $('#loadingAddRecords').addClass('hidden');
        if(response.ok) {
          const contentType = response.headers.get("content-type");
          if(contentType && contentType.indexOf("application/json") !== -1) {
            return response.json().then((json) => {
              // process your JSON further
              if (json.result === 'success') {
                $('#outputAddRecords').text('完了');
              } else {
                $('#outputAddRecords').text('失敗(サーバー側でエラー)');
              }
            });
          } else {
            $('#outputAddRecords').text('失敗(not json)');
          }
        } else {
          $('#outputAddRecords').text('失敗');
        }
      });
  });

  $('#btnClearRecords').on('click', () => {
    $('#loadingClearRecords').removeClass('hidden');

    const data = new FormData();
    data.append('action-type', 'clear');
    data.append('csrf_token', $('#csrf_token').val());

    fetch('', { method: 'POST', body: data, credentials: 'same-origin' })
      .then((response) => {
        $('#loadingClearRecords').addClass('hidden');
        if(response.ok) {
          const contentType = response.headers.get("content-type");
          if(contentType && contentType.indexOf("application/json") !== -1) {
            return response.json().then((json) => {
              // process your JSON further
              if (json.result === 'success') {
                $('#outputClearRecords').text('完了');
              } else {
                $('#outputClearRecords').text('失敗(サーバー側でエラー)');
              }
            });
          } else {
            $('#outputClearRecords').text('失敗(not json)');
          }
        } else {
          $('#outputClearRecords').text('失敗');
        }
      });
  });

  $('#btnGetRecords').on('click', () => {
    $('#loadingGetRecords').removeClass('hidden');

    const data = new FormData();
    data.append('action-type', 'get');
    data.append('csrf_token', $('#csrf_token').val());

    fetch('', { method: 'POST', body: data, credentials: 'same-origin' })
      .then((response) => {
        $('#loadingGetRecords').addClass('hidden');
        if(response.ok) {
          const contentType = response.headers.get("content-type");
          if(contentType && contentType.indexOf("application/json") !== -1) {
            return response.json().then((json) => {
              if (json.result === 'success') {
                // process your JSON further
                let output = '<table class="table table-bordered"><tbody><tr><th>name</th><th>colour</th><th>calories</th></tr>';
                json.records.forEach((record) => {
                  output += `<tr><td>${e(record.name)}</td><td>${e(record.colour)}</td><td class="num">${e(record.calories)}</td></tr>`;
                });
                output += '</tbody></table>';
                $('#outputGetRecords').html(output);
              } else {
                $('#outputGetRecords').text('失敗(サーバー側でエラー)');
              }
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
