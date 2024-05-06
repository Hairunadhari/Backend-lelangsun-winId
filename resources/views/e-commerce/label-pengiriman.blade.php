<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        /* #headerlogo{
        display: flex !important; 
        gap: 30rem;
    } */

    </style>
</head>

<body>

    <div id="headerlogo" style="border-bottom: 1px solid black;">
        @switch($key->courier_company)
            @case('lalamove')
                <div style="float: left;">
                    <img style="width: 120px" src="{{public_path("/asset-kurir/lalamove.jpg")}}" alt="">
                </div>
            @break
            @case('jne')
                <div style="float: left;">
                    <img style="width: 120px" src="{{public_path("/asset-kurir/jne.jpg")}}" alt="">
                </div>
            @break
            @case('tiki')
                <div style="float: left;">
                    <img style="width: 120px" src="{{public_path("/asset-kurir/tiki.png")}}" alt="">
                </div>
            @break
            @case('jnt')
                <div style="float: left;">
                    <img style="width: 120px" src="{{public_path("/asset-kurir/jnt.jpg")}}" alt="">
                </div>
            @break
            @case('sicepat')
                <div style="float: left;">
                    <img style="width: 120px" src="{{public_path("/asset-kurir/sicepat.svg")}}" alt="">
                </div>
            @break
        @default

        @endswitch
        <div style="float: left; margin-left: 10rem;">
            <img style="width: 120px" src="{{public_path("/assets/img/LogoWin-Shop2.png")}}" alt="">
        </div>
        <div style="clear: both;"></div>
    </div>

    <section style="text-align: center;border-bottom: 1px solid black; padding: 1rem">
        <div style="border: 1px solid black; ">
            {!! $key->barcode !!}
        </div>
        <div>

            Nomor Resi: {{$key->waybill_id}}
        </div>
    </section>
    <section style="text-align: center; border-bottom: 1px solid black; margin-bottom: 1rem; padding: 1rem">
        JENIS LAYANAN - {{$key->type}}
    </section>

    <section style="border-bottom: 1px solid black; padding: 1rem; text-align: center">
        <div style="float: left; width: 45%">
            {{$key->postal_code_user}}
        </div>
        <div style="float: left; width: 45%">
            <div>Quantity : {{$key->qty}} Pcs</div>
            <br>
            <div>Weight : {{$key->berat_item}} Gram</div>
        </div>
        <div style="clear: both;"></div>
    </section>

    <section style="border-bottom: 1px solid black; padding: 1rem; text-align: center">
        <div style="height: 150px; float: left; width: 45%">
            <label for="">Alamat Penerima :</label>
            <div>Nama : {{$key->nama_user}}</div>
            <div>No Telephone : {{$key->no_telephone_user}}</div>
            <div>Kode Pos : {{$key->postal_code_user}}</div>
            <div>Detail : {{$key->detail_alamat_user}}</div>
        </div>
        <div style="height: 150px; float: left; width: 45%">
            <label for="">Alamat Pengirim :</label>
            <div>Nama : {{$key->nama_toko}}</div>
            <div>No Telephone : {{$key->no_telephone_toko}}</div>
            <div>Kode Pos : {{$key->postal_code_toko}}</div>
            <div>Detail : {{$key->detail_alamat_toko}}</div>
        </div>
        <div style="clear: both;"></div>

    </section>

    <section style="border-bottom: 1px solid black; padding: 1rem;">
        Jenis Barang : {{$key->nama_produk}}
    </section>
    <Section style="border-bottom: 1px solid black; padding: 1rem;">
        Catatan : Tidak Ada
    </Section>

</body>

</html>
