/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

$(function(){
    $('#logoutLink').click(function(e){
        e.preventDefault();
        $(this).attr('disabled', true);
        $('#logoutForm').submit();
    });

    $('#followUserBtn').click(function(e){
        e.preventDefault();

        const userId = $(this).data('follow');

        $.ajax({
            type: 'POST',
            url: '/api/user/follow/' + userId,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Authorization': 'Bearer ' + $('meta[name="api_token"]').attr('content'),
            },
            dataType: 'json',
            error: function(XMLHttpRequest,textStatus,errorThrown)
            {
                // TODO: エラーならどうするべきか
            }
        }).done(function(data){
            if (data.status === 'failed') { return false; }

            const followBtn = $('#followUserBtn');
            if(data.following === true) {
                followBtn.removeClass('btn-primary').addClass('btn-secondary');
                followBtn.text('フォローを外す');
            } else {
                followBtn.removeClass('btn-secondary').addClass('btn-primary');
                followBtn.text('フォローする');
            }
        });

        return false;
    });
});