import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
$(document).ready(function(){

    $(document).on('click','#send_message',function (e){
        e.preventDefault();

        let email = $('#email').val();
        let event_lelang_id = $('#event_lelang_id').val();
        let peserta_npl_id = $('#peserta_npl_id').val();
        let lot_id = $('#lot_id').val();
        let npl_id = $('#npl_id').val();
        let harga_bidding = $('#harga_bidding').val();
        console.log(email);
        console.log(event_lelang_id);
        console.log(peserta_npl_id);
        console.log(lot_id);
        console.log(npl_id);
        console.log(harga_bidding);


        $.ajax({
            method:'post',
            url:'/send-bidding',
            data:{email:email, event_lelang_id:event_lelang_id, peserta_npl_id:peserta_npl_id, lot_id:lot_id, npl_id:npl_id, harga_bidding:harga_bidding},
            success:function(res){
                //
            }
        });

    });
});

window.Echo.channel('chat')
    .listen('.message',(e)=>{
        // $('#messages').append('<div class=" bg-success "><div class=" mb-2 fs-3">'+e.email+'</strong>'+ ': ' + e.harga_bidding+'</div></div>');
        $('#messages').append('<div class="mb-3 px-3 py-2" style="background-color: green; color: white; border-radius: 10px"><h5 class="mb-0">'+e.email+' '+ ': ' + e.harga_bidding+'</h5></div>');
        $('#message').val('');
    });