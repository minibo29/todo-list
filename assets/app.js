/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import './styles/global.scss';

// import './bootstrap';

// start the Stimulus application
const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');

// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

$(document).ready(function() {


  $('.list-item .delete').click(function(e) {
    e.preventDefault();
    if (e.target !== this)
      return;

    const $element = $(e.target);
    const $list_item = $element.closest('.list-item');
    const item_id = $list_item.data('id');
    const csrf_token = $list_item.data('csrf_token');
    const url = '/task-list/' + item_id;

    $list_item.addClass('loading');
    $.ajax({
      url: url,
      type: 'DELETE',
      data: { _token: csrf_token }
    }).then((response) => {
      console.log(response);
      $list_item.remove();
    }).catch((error) => {
      console.log(error);
      $list_item.removeClass('loading');
    });
  });

  $('.list-item').click( function (e) {
    e.preventDefault();
    if (e.target !== this)
      return;
    const $list_item = $(e.target);
    const item_id = $list_item.data('id');
    const csrf_token = $list_item.data('csrf_token');
    const url = '/task-list/' + item_id + '/edit';
    const done = $list_item.hasClass('checked');

    console.log(done);
    $list_item.addClass('loading');

    $.ajax({
      url: url,
      type: 'post',
      data: { done: + !done, _token: csrf_token}
    }).then((response) => {
      $list_item.removeClass('loading');
      if (done) {
        $list_item.removeClass('checked')
      } else {
        $list_item.addClass('checked')
      }
    }).catch((error) => {
      $list_item.removeClass('loading');
    });



    // $list_item.addClass('loading');
    // $.ajax({
    //   url: url,
    //   type: 'POST',
    //   data: { _token: csrf_token}
    // }).then((response) => {
    //   console.log(response);
    //   $list_item.remove();
    // }).catch((error) => {
    //   console.log(error);
    //   $list_item.removeClass('loading');
    // });
  });

});
