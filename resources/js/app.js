import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
$(document).ready(function () {
    //     let event_lelang_id = $('#event_lelang_id').val();
    //     let peserta_npl_id = $('#peserta_npl_id').val();
    //     let lot_item_id = $('#lot_item_id').val();
    //     console.log(lot_item_id);

    // $.ajax({
    //     method: 'post',
    //     url: '/log-bidding',
    //     data: {
    //         event_lelang_id: event_lelang_id,
    //         lot_item_id: lot_item_id,
    //     },
    //     success: function (res){
    //         console.log(res);
    //         $.each(res, function (key, value) {
    //             console.log(value);
    //             $('#messages').prepend('<div class="mb-3 px-3 py-2" style="background-color: green; color: white; border-radius: 10px"><h5 class="mb-0">'+ value.email+' '+ ': ' + value.harga_bidding+'</h5></div>');
    //         }); 
    //     }
    // });

    // Simpan value harga bidding dan konversi ke tipe data float
    let kelipatanBidding = parseFloat($('#harga_bidding').val());

    $(document).on('click', '#send_message', function (e) {
        e.preventDefault();

        let email = $('#email').val();
        let event_lelang_id = $('#event_lelang_id').val();
        let peserta_npl_id = $('#peserta_npl_id').val();
        let lot_item_id = $('#lot_item_id').val();
        let npl_id = $('#npl_id').val();

        // Dapatkan nilai harga bidding saat ini dan tambahkan kelipatan bidding
        let harga_bidding = parseFloat($('#harga_bidding').val()) + kelipatanBidding;

        // Update nilai input harga_bidding
        $('#harga_bidding').val(harga_bidding);

        console.log(email);
        console.log(event_lelang_id);
        console.log(peserta_npl_id);
        console.log(lot_item_id);
        console.log(npl_id);
        console.log(harga_bidding);

        $.ajax({
            method: 'post',
            url: '/send-bidding',
            data: {
                email: email,
                event_lelang_id: event_lelang_id,
                peserta_npl_id: peserta_npl_id,
                lot_item_id: lot_item_id,
                npl_id: npl_id,
                harga_bidding: harga_bidding
            },
            success: function (res) {
                // Lakukan sesuatu dengan respon dari AJAX jika diperlukan
            }
        });
    });

    // button stop bidding
    $(document).on('click', '#stop-bidding', function (e) {
        e.preventDefault();

        swal({
                title: "Anda Yakin Akan Menghentikan Lelang Ini?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((win) => {
                if (win) {
                    let event_lelang_id = $('#event_lelang_id').val();
                    let lot_item_id = $('#lot_item_id').val();
                    $.ajax({
                        method: 'post',
                        url: '/search-pemenang-event',
                        data: {
                            event_lelang_id: event_lelang_id,
                            lot_item_id: lot_item_id,
                        },
                        success: function (res) {
                            // console.log(res);
                            swal("Pemenang Dari LOT Ini Adalah " + res.email + " dengan harga " + res.harga_bidding + "!", {
                                icon: "success",
                                buttons: {
                                    confirm: {
                                        text: "Next Bidding",
                                    },
                                },
                            }).then((nextlot) => {
                                if (nextlot) {
                                    $.ajax({
                                        method: 'post',
                                        url: '/next-lot',
                                        data: {
                                            event_lelang_id: event_lelang_id,
                                            lot_item_id: lot_item_id,
                                        },
                                        success: function (res) {
                                            window.open(event_lelang_id, '_blank');
                                        }
                                    });
                                }
                            });

                        }
                    });
                }
            });
    });
});


window.Echo.channel('chat')
    .listen('.message', (e) => {
        // $('#messages').append('<div class=" bg-success "><div class=" mb-2 fs-3">'+e.email+'</strong>'+ ': ' + e.harga_bidding+'</div></div>');
        $('#messages').prepend('<div class="mb-3 px-3 py-2" style="background-color: green; color: white; border-radius: 10px"><h5 class="mb-0">' + e.email + ' ' + ': ' + e.harga_bidding + '</h5></div>');
        $('#message').val('');
    });
