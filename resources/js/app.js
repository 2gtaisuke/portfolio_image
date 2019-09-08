/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

$(function(){
    // フォーム送信
    $('#createBoardSubmit').click(function() {
        $(this).attr('disabled', true);
        $('#board-form').submit();
    });

    // ポップアップ表示
    $('[data-toggle="popover"]').popover({
        trigger: 'hover'
    });

    // モーダルの禁止
    $('#deleteBoard').click(function(e){
        e.preventDefault();
    });

    // 掲示板削除フォーム送信
    $('.delete-board-button').click(function(){
        $(this).attr('disabled', true);
        $('#deleteBoardForm-' + $(this).data('id')).submit();
    });

    $('#logoutLink').click(function(e){
        e.preventDefault();
        $(this).attr('disabled', true);
        $('#logoutForm').submit();
    });
});