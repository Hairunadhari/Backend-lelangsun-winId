import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
$(document).ready(function () {
    let email = $('#email').val();
    let event_lelang_id = $('#event_lelang_id').val();
    let peserta_npl_id = $('#peserta_npl_id').val();
    let lot_item_id = $('#lot_item_id').val();
    let npl_id = $('#npl_id').val();
    //     console.log(peserta_npl_id);

    //     // <------------------------------------------------ FUNGSI LIAT STORY BIDDING --------------------------------------------------->

       $.ajax({
           method: 'post',
           url: '/log-bidding',
           data: {
               event_lelang_id: event_lelang_id,
               lot_item_id: lot_item_id,
           },
           success: function (res){
               console.log(res);
               $.each(res, function (key, value) {
                   $('#log-bid').prepend('<div class="mb-3 px-3 py-2" style="background-color: green; color: white; border-radius: 10px"><h5 class="mb-0">'+ value.email+' '+ ': ' + value.harga_bidding+'</h5></div>');
                   $('#log-bid-user').prepend('<div class="mb-3 px-3 py-2" style="background-color: green; color: white; border-radius: 10px"><h5 class="mb-0">'+ value.email+' '+ ': ' + value.harga_bidding+'</h5></div>');
               }); 
           }
       });

        // <------------------------------------------------ FUNGSI BUTTON START BID ------------------------------------------------------->

        // kode tampilkan button bidding, stop dan timer
        const startBidButton = document.getElementById('start-bid');
        const conBidElement = document.getElementById('con-bid');
        startBidButton.addEventListener('click', function() {
            conBidElement.style.display = 'block';
            startBidButton.style.display = 'none';
            $.ajax({
                method: 'post',
                url: '/open-button',
                data: {
                    button: 'open',
                },
                success: function (res){

                }
            });
        });
        // tutup kode tampilkan button bidding, stop dan timer

        // <------------------------------------------------ FUNGSI KETIKA TIMER BERJALAN ------------------------------------------------------->
        let countdown; // variabel untuk menyimpan interval timer
        let isTimerRunning = false; // false karena timer belom berjalan
        // // Fungsi untuk mengubah format waktu menjadi menit:detik
        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = seconds % 60;
            return `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
        }


        function toggleTimer() { // <-- Fungsi untuk memulai timer
            if (!isTimerRunning) { // <-- apakah timer false
              let seconds = 30; // set timer ke 30 detik
              const timerElement = document.getElementById('timer');
              timerElement.textContent = formatTime(seconds);

              countdown = setInterval(() => {
                seconds--; // kurangi second 1
                if (seconds >= 0) {
                  timerElement.textContent = formatTime(seconds);
                } else { // <-- ketika timer sudah habis
                  clearInterval(countdown); // Hentikan timer saat mencapai 0
                //   alert("Waktu habis");
                  timer_habis();
                  isTimerRunning = false; // Setel timer ke non-berjalan setelah selesai
                  document.getElementById('start_bid').disabled = false; // Aktifkan kembali tombol "Start Bid"
                }
              }, 1000);

              isTimerRunning = true; // Setel timer ke berjalan
              document.getElementById('start_bid').disabled = true; // Nonaktifkan tombol "Start Bid" saat timer berjalan
            }
          }      

        const sendMessage = document.getElementById('send_bidding');
        sendMessage.addEventListener('click', toggleTimer);

        function timer_habis() {
            $.ajax({
                method: 'post',
                url: '/search-pemenang-event',
                data: {
                    event_lelang_id: event_lelang_id,
                    lot_item_id: lot_item_id,
                },
                success: function (res) {
                    // console.log(res);
                    const message = res.email ? "Pemenang Dari LOT Ini Adalah " + res.email + " dengan harga " + res.harga_bidding + "!" : "LOT tidak memiliki pemenang.";
                    swal(message,{
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
                                    console.log(res);
                                    if (res.lot_item.length > 0) {
                                        window.location.href = '/bidding-event-lelang/'+event_lelang_id+'?lot=' + res.lot_item[0].id;
                                    } else {
                                        window.location.href = '/event-lelang';
                                    }
                                },

                            });
                        }
                    });

                }
            });
        }

        // Simpan value harga bidding dan konversi ke tipe data float
        let kelipatanBidding = parseFloat($('#harga_bidding').val());

        // <------------------------------------------------ FUNGSI KLIK BIDDING ------------------------------------------------------->
        $(document).on('click', '#send_bidding', function (e) {
            e.preventDefault();

            let email = $('#email').val();
            let event_lelang_id = $('#event_lelang_id').val();
            let peserta_npl_id = $('#peserta_npl_id').val();
            let lot_item_id = $('#lot_item_id').val();
            let npl_id = $('#npl_id').val();

            // Dapatkan nilai harga bidding saat ini dan tambahkan kelipatan bidding
            let harga_bidding = parseFloat($('#harga_awal').val()) + kelipatanBidding;

            // Update nilai input harga_awal
            $('#harga_awal').val(harga_bidding);

            // console.log(email);
            // console.log(event_lelang_id);
            // console.log(peserta_npl_id);
            // console.log(lot_item_id);
            // console.log(npl_id);
            // console.log(harga_bidding);

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


    // <------------------------------------------------ FUNGSI STOP BIDDING ------------------------------------------------------->
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
                            const message = res.email ? "Pemenang Dari LOT Ini Adalah " + res.email + " dengan harga " + res.harga_bidding + "!" : "LOT tidak memiliki pemenang.";
                            swal(message, {
                                icon: "success",
                                buttons: {
                                    confirm: {
                                        text: "Next Bidding",
                                    },
                                },
                                closeOnClickOutside: false,
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
                                            console.log(res);
                                            if (res.lot_item.length > 0) {
                                                window.location.href = '/bidding-event-lelang/' + event_lelang_id + '?lot=' + res.lot_item[0].id;
                                            } else {
                                                window.location.href = '/event-lelang';
                                            }
                                        },

                                    });
                                }
                            });

                        }
                    });
                }
            });
    });









    // <------------------------------------------------ FUNGSI BID USER ------------------------------------------------------->

    let email_user = $('#email_user').val();
    let event_lelang_id_user = $('#event_lelang_id_user').val();
    let peserta_npl_id_user = $('#peserta_npl_id_user').val();
    let lot_item_id_user = $('#lot_item_id_user').val();
    let npl_id_user = $('#npl_id_user').val();
    let kelipatanBidding_user = parseFloat($('#harga_bidding_user').val());
    $(document).on('click', '#user_send_bidding', function (e) {
        e.preventDefault();
        // tambah kelipatan bid setiap klik
        let harga_bidding_user = parseFloat($('#harga_awal_user').val()) + kelipatanBidding_user;
        // Update nilai input harga_awal
        $('#harga_awal_user').val(harga_bidding_user);
        $.ajax({
            method: 'post',
            url: '/send-bidding-user',
            data: {
                email: email_user,
                event_lelang_id: event_lelang_id_user,
                peserta_npl_id: peserta_npl_id_user,
                lot_item_id: lot_item_id_user,
                npl_id: npl_id_user,
                harga_bidding: harga_bidding_user
            },
            success: function (res) {
                // Lakukan sesuatu dengan respon dari AJAX jika diperlukan
            }
        });
    });

});


window.Echo.channel('chat')
    .listen('.message', (e) => {
        $('#log-bid').prepend('<div class="mb-3 px-3 py-2" style="background-color: green; color: white; border-radius: 10px"><h5 class="mb-0">' + e.email + ' ' + ': ' + e.harga_bidding + '</h5></div>');
        $('#log-bid-user').prepend('<div class="mb-3 px-3 py-2" style="background-color: green; color: white; border-radius: 10px"><h5 class="mb-0">' + e.email + ' ' + ': ' + e.harga_bidding + '</h5></div>');
    });
window.Echo.channel('button')
    .listen('.respon-button', (e) => {
        $('#user-start-bid').css('display', 'block');
    });
window.Echo.channel('search-pemenang-lot')
    .listen('.pemenang-lot', (e) => {
        console.log(e.bid);
        const message = e.bid.email ? "Pemenang Dari LOT Ini Adalah " + e.bid.email + " dengan harga " + e.bid.harga_bidding + "!" : "LOT tidak memiliki pemenang.";
        
        swal({
            title: "WAKTU HABIS !!!",
            text: message,
            icon: "success",
            buttons: false,
        });

    });
window.Echo.channel('next-lot')
    .listen('.lot', (e) => {
        console.log(e);
        let id_event_crypt = $('#id_event_crypt').val();

        if (e.lot_item.length > 0) {
            window.location.href = '/user-bidding/' + id_event_crypt + '?lot=' + e.lot_item[0].id;
        } else {
            window.location.href = '/';
        }
    });
