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
    @foreach ($collection as $item)

    <div id="headerlogo" style="border-bottom: 1px solid black;">
        @switch($item)
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
        <div style="border: 1px solid black; height: 100px"></div>
        <div>

            Nomor Resi: {{$item->waybill_id}}
        </div>
    </section>
    <section style="text-align: center; border-bottom: 1px solid black; margin-bottom: 1rem; padding: 1rem">
        JENIS LAYANA - {{$item->order->type}}
    </section>

    <section style="border-bottom: 1px solid black; padding: 1rem; text-align: center">
        <div style="float: left; width: 45%">
            {{$item->order->postal_code_user}}
        </div>
        <div style="float: left; width: 45%">
            <div>Quantity : {{count($item->order->orderitem)}}</div>
            <br>
            <div>Weight</div>
        </div>
        <div style="clear: both;"></div>
    </section>

    <section style="border-bottom: 1px solid black; padding: 1rem; text-align: center">
        <div style="height: 150px; float: left; width: 45%">Alamat Peneriman :</div>
        <div style="height: 150px; float: left; width: 45%">Alamat Pengirim :</div>
        <div style="clear: both;"></div>

    </section>

    <section style="border-bottom: 1px solid black; padding: 1rem;">
        Jenis Barang :
    </section>
    <Section style="border-bottom: 1px solid black; padding: 1rem;">
        Catatan :
    </Section>
    @endforeach

</body>

</html>
