import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
$(document).ready(function () {

    

    let event_lelang_id = $('#event_lelang_id').val();
    let lot_item_id = $('#lot_item_id').val();
    let event_lelang_id_crypt = $('#event_lelang_id_crypt').val();

    // <------------------------------------------------ FUNGSI LIAT STORY BIDDING --------------------------------------------------->

    $.ajax({
        method: 'post',
        url: '/log-bidding',
        data: {
            event_lelang_id: event_lelang_id,
            lot_item_id: lot_item_id,
        },
        success: function (res) {
            // console.log(res);
            $.each(res, function (key, value) {
                $('#log-bid').prepend('<div class="mb-3 px-3 py-2" style="background-color: green; color: white; border-radius: 10px"><h5 class="mb-0">' + value.email + ' ' + ': ' + value.harga_bidding + '</h5></div>');
            });
        }
    });

    // <------------------------------------------------ FUNGSI BUTTON START BID ------------------------------------------------------->

    // <--------FUNGSI hitung mundur timer ----------->
    let countdown; // variabel untuk menyimpan interval timer
    let isTimerRunning = false; // false karena timer belom berjalan

    // // Fungsi untuk mengubah format waktu_bid menjadi menit:detik
    function formatTime(seconds) {
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = seconds % 60;
        return `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
    }

    let timer_bid = localStorage.getItem('timer_bid');

    if (timer_bid && timer_bid > 0) {
        toggleTimer(timer_bid);
    }


    function toggleTimer(data) { // <-- Fungsi untuk memulai timer
        if (!isTimerRunning) { // <-- apakah timer false
            const timerElement = document.getElementById('timer');
            countdown = setInterval(() => {
                data--; // kurangi second 1
                if (data >= 0) {
                    timerElement.textContent = formatTime(data);
                    localStorage.setItem('timer_bid', data);
                } else { // <-- ketika timer sudah habis
                    localStorage.removeItem('timer_bid');
                    clearInterval(countdown); // Hentikan timer saat mencapai 0
                    timer_habis(); 
                    isTimerRunning = false; 
                    document.getElementById('start-bid').disabled = false; // Aktifkan kembali tombol "Start Bid"
                }
            }, 1000);

            isTimerRunning = true; // Setel timer ke berjalan
            document.getElementById('start-bid').disabled = true; // Nonaktifkan tombol "Start Bid" saat timer berjalan
        }
    }

    function getCookie(cname) {
        let name = cname + "=";
        let decodedCookie = decodeURIComponent(document.cookie);
        let ca = decodedCookie.split(';');
        for(let i = 0; i <ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    let button = getCookie("button-bid");
    if (button != "") {
        $('#con-bid').css('display', 'block');
        $('#start-bid').css('display', 'none');
        $('#user-send-bidding').css('display', 'block');
        toggleTimer();
    } 

        

    // kode tampilkan button bidding di user, button stop, bid dan jalankan timer
    $(document).on('click', '#start-bid', function (e) {
        $('#con-bid').css('display', 'block');
        $('#start-bid').css('display', 'none');
        const timerElement = document.getElementById('timer');
        let seconds = parseInt(timerElement.getAttribute('data-seconds'));
        $.ajax({
            method: 'post',
            url: '/superadmin/open-button',
            data: {
                button: 'open',
                event_lelang_id: event_lelang_id,
            },
            success: function (res) {
                console.log('start-bid',res);
                toggleTimer(seconds);
                
            }
        });
    });

    // <--------FUNGSI ketika timer habis ----------->
    function timer_habis() {
        $.ajax({
            method: 'post',
            url: '/superadmin/search-pemenang-event',
            data: {
                event_lelang_id: event_lelang_id,
                lot_item_id: lot_item_id,
            },
            success: function (res) {
                console.log('timer-habis',res);
                $('#con-bid').css('display', 'none');
                    $('#loading').css('display', 'block');
                const message = res.email ? "Pemenang Dari LOT Ini Adalah " + res.email + " dengan harga " + res.harga_bidding + "!" : "LOT tidak memiliki pemenang.";
                swal(message, {
                    icon: "success",
                    buttons: {
                        confirm: {
                            text: "Next Bidding",
                            closeOnClickOutside: false,
                        },
                    },
                }).then((nextlot) => {
                    if (nextlot) {
                        $.ajax({
                            method: 'post',
                            url: '/superadmin/next-lot',
                            data: {
                                event_lelang_id: event_lelang_id,
                                lot_item_id: lot_item_id,
                            },
                            success: function (res) {
                                console.log('next-bid',res);
                                if (res.lot_item.length > 0) {
                                    window.location.href = '/superadmin/bidding-event-lelang/' + event_lelang_id_crypt + '?lot=' + res.lot_item[0].id;
                                    document.cookie = 'button-bid=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
                                    localStorage.removeItem('timer_bid');
                                    clearInterval(countdown);
                                } else {
                                    $.ajax({
                                        method: 'post',
                                        url: '/superadmin/delete-event-lelang/' + event_lelang_id,
                                        data: {
                                            _method: 'PUT',
                                        },
                                        success: function (res) {
                                            console.log('delete-event',res);
                                                document.cookie = 'button-bid=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
                                        }
                                    });
                                    window.location.href = '/superadmin/event-lelang';
                                }
                            },

                        });
                    }
                });

            }
        });
    }


    // <------------------------------------------------ FUNGSI KLIK BIDDING ------------------------------------------------------->
    $(document).on('click', '#send_bidding', function (e) {
        e.preventDefault();
        $('#con-bid').css('display', 'none');
        $('#loading').css('display', 'block');

        let kelipatan_bid = parseFloat($('#kelipatan_bid').val());
        let harga_awal = parseFloat($('#harga_awal').val());

        let email = $('#email').val();
        let event_lelang_id = $('#event_lelang_id').val();
        let user_id = $('#user_id').val();
        let lot_item_id = $('#lot_item_id').val();
        let npl_id = $('#npl_id').val();


        $.ajax({
            method: 'post',
            url: '/superadmin/send-bidding',
            data: {
                email: email,
                event_lelang_id: event_lelang_id,
                user_id: user_id,
                lot_item_id: lot_item_id,
                npl_id: npl_id,
                harga_awal: harga_awal,
                kelipatan_bid: kelipatan_bid,
            },
            success: function (res) {
                console.log('send-bidding',res);
                $('#con-bid').css('display', 'block');
                $('#loading').css('display', 'none');
                
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
                closeOnClickOutside: false,
            })
            .then((win) => {
                if (win) {
                    $('#con-bid').css('display', 'none');
                    $('#loading').css('display', 'block');
                    timer_habis();
                }
            });
    });



    // <------------------------------------------------ FUNGSI BID USER ------------------------------------------------------->

    
    $(document).on('click', '#user-send-bidding', function (e) {
        let email_user = $('#email_user').val();
        let event_lelang_id_user = $('#event_lelang_id_user').val();
        let user_id_web = $('#user_id_web').val();
        let lot_item_id_user = $('#lot_item_id_user').val();
        let npl_id_user = $('#npl_id_user').val();
        let kelipatan_bid_user = $('#kelipatan_bid_user').val();
        let harga_awal_user = $('#harga_awal_user').val();

        e.preventDefault();

        $('#user-send-bidding').css('display', 'none');
        $('#loading').css('display', 'block');
        $.ajax({
            method: 'post',
            url: '/send-bidding-user',
            data: {
                email: email_user,
                event_lelang_id: event_lelang_id_user,
                user_id: user_id_web,
                lot_item_id: lot_item_id_user,
                npl_id: npl_id_user,
                kelipatan_bid_user: kelipatan_bid_user,
                harga_awal_user: harga_awal_user
            },
            success: function (res) {
                console.log('send-bid-user',res);

                $('#user-send-bidding').css('display', 'block');
                $('#loading').css('display', 'none');
            }
        });
    });

});

let event_lelang_id = $('#event_lelang_id_web_user').val();
window.Echo.channel('lelang')
     .listen('.bidding-event-'+event_lelang_id, (e) => {
        console.log(e);
        $('#log-bid').prepend('<div class="mb-3 px-3 py-2" style="background-color: green; color: white; border-radius: 10px"><h5 class="mb-0">' + e.email + ' ' + ': ' + e.harga_bidding + '</h5></div>');
        $('#log-bid-user').prepend('<div class="mb-3 px-3 py-2" style="background-color: green; color: white; border-radius: 10px"><h5 class="mb-0">' + e.email + ' ' + ': ' + e.harga_bidding + '</h5></div>');

        $('#harga_awal').val(e.harga_bidding);
        $('#harga_awal_user').val(e.harga_bidding);


    });
window.Echo.channel('lelang')
    .listen('.button-bid-event-'+event_lelang_id, (e) => {
        function setCookie(cname, cvalue, exdays) {
            const d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            let expires = "expires=" + d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        function getCookie(cname) {
            let name = cname + "=";
            let decodedCookie = decodeURIComponent(document.cookie);
            let ca = decodedCookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }

            let button = getCookie("button-bid");
            if (button != "") {
                // alert("Welcome again " + button);
            } else {
                button = "true";
                if (button != "" && button != null) {
                    setCookie("button-bid", button, 1);
                }
            }
        $('#user-send-bidding').css('display', 'block');
    });
window.Echo.channel('lelang')
     .listen('.pemenang-lot-event-'+event_lelang_id, (e) => {
        console.log(e);
        var meta = document.getElementsByTagName('meta');
        for (let idx = 0; idx < meta.length; idx++) {
            const el = meta[idx];
            // console.log("meta page",el.getAttribute('data-page'));
            if (el.getAttribute('data-page') == "user") {
                if (e.bid !== null) {
                    var message = "Pemenang Dari LOT Ini Adalah " + e.bid.email + " dengan harga " + e.bid.harga_bidding + "!";
                } else {
                    var message = "LOT tidak memiliki pemenang.";
                }
                swal({
                    title: "WAKTU HABIS !!!",
                    text: message,
                    icon: "success",
                    buttons: false,
                    closeOnClickOutside: false,
                });
                break;
            }
        }
    });
window.Echo.channel('lelang')
     .listen('.next-lot-event-'+event_lelang_id, (e) => {
        console.log(e);
        let id_event_crypt = $('#id_event_crypt').val();

        if (e.lot_item.length > 0) {
            window.location.href = '/user-bidding/' + id_event_crypt + '?lot=' + e.lot_item[0].id;
            document.cookie = 'button-bid=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
        } else {
            swal({
                title: "Event Selesai !!!",
                icon: "success",
                text: "Terima Kasih Sudah mengikuti event ini :)",
                buttons: false,
                closeOnClickOutside: false,
            });
            window.location.href = '/';
            document.cookie = 'button-bid=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
        }
    });

// window.Echo.channel('bid-event.'+event_lelang_id)
//      .listen('.message', (e) => {
//         console.log(e);
//         $('#log-bid').prepend('<div class="mb-3 px-3 py-2" style="background-color: green; color: white; border-radius: 10px"><h5 class="mb-0">' + e.email + ' ' + ': ' + e.harga_bidding + '</h5></div>');
//         $('#log-bid-user').prepend('<div class="mb-3 px-3 py-2" style="background-color: green; color: white; border-radius: 10px"><h5 class="mb-0">' + e.email + ' ' + ': ' + e.harga_bidding + '</h5></div>');

//         $('#harga_awal').val(e.harga_bidding);
//         $('#harga_awal_user').val(e.harga_bidding);


//     });
// window.Echo.channel('button')
//     .listen('.button-bid-event-'+event_lelang_id, (e) => {
//         function setCookie(cname, cvalue, exdays) {
//             const d = new Date();
//             d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
//             let expires = "expires=" + d.toUTCString();
//             document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
//         }

//         function getCookie(cname) {
//             let name = cname + "=";
//             let decodedCookie = decodeURIComponent(document.cookie);
//             let ca = decodedCookie.split(';');
//             for (let i = 0; i < ca.length; i++) {
//                 let c = ca[i];
//                 while (c.charAt(0) == ' ') {
//                     c = c.substring(1);
//                 }
//                 if (c.indexOf(name) == 0) {
//                     return c.substring(name.length, c.length);
//                 }
//             }
//             return "";
//         }

//             let button = getCookie("button-bid");
//             if (button != "") {
//                 // alert("Welcome again " + button);
//             } else {
//                 button = "true";
//                 if (button != "" && button != null) {
//                     setCookie("button-bid", button, 1);
//                 }
//             }
//         $('#user-send-bidding').css('display', 'block');
//     });
// window.Echo.channel('search-pemenang-lot')
//     .listen('.pemenang-lot', (e) => {
//         console.log(e);

//         var meta = document.getElementsByTagName('meta');
//         for (let idx = 0; idx < meta.length; idx++) {
//             const el = meta[idx];
//             // console.log("meta page",el.getAttribute('data-page'));
//             if (el.getAttribute('data-page') == "user") {
//                 if (e.bid !== null) {
//                     var message = "Pemenang Dari LOT Ini Adalah " + e.bid.email + " dengan harga " + e.bid.harga_bidding + "!";
//                 } else {
//                     var message = "LOT tidak memiliki pemenang.";
//                 }
//                 swal({
//                     title: "WAKTU HABIS !!!",
//                     text: message,
//                     icon: "success",
//                     buttons: false,
//                     closeOnClickOutside: false,
//                 });
//                 break;
//             }
//         }

//     });
// window.Echo.channel('next-lot')
//     .listen('.lot', (e) => {
//         let id_event_crypt = $('#id_event_crypt').val();

//         if (e.lot_item.length > 0) {
//             window.location.href = '/user-bidding/' + id_event_crypt + '?lot=' + e.lot_item[0].id;
//             document.cookie = 'button-bid=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
//         } else {
//             swal({
//                 title: "Event Selesai !!!",
//                 icon: "success",
//                 text: "Terima Kasih Sudah mengikuti event ini :)",
//                 buttons: false,
//                 closeOnClickOutside: false,
//             });
//             window.location.href = '/';
//             document.cookie = 'button-bid=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
//         }
//     });
