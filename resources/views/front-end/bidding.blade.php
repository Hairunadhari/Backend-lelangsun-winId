@extends('front-end.layout')
@section('content')
<style>
    #satu {
        background-image: url('detail_event.jpg');
        height: 100vh;
        background-position: left;
        background-size: cover;
        background-repeat: no-repeat;
        width: 100%;
        padding: 50px;
        color: white;
    }
   
    #satu .bungkus-lot {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(18rem, 1fr));
        grid-gap: 20px; /* Spasi antara card */
        padding: 0 20px; /* Spasi kiri dan kanan dari container */
        justify-content: center;
    }
    #satu .items {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(15rem, 1fr));
        grid-gap: 0px; /* Spasi antara card */
        padding: 0px; /* Spasi kiri dan kanan dari container */
        justify-content: center;
    }
    #satu .bungkus-lot img{
        height: 200px;
    }
    .item-barang img{
        width: 200px;
        height: 200px;
    }
    .items img{
        box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; margin:5px; padding:0.25rem; border:1px solid #dee2e6; 
    }
    .scroll .card{
        height:300px;
        overflow: scroll;
        width: auto;
    }
    @media (max-width: 600px) {
        #satu {
            background-image: url('detail_event.jpg');
            height: auto;
            background-position: left;
            background-size: cover;
            background-repeat: no-repeat;
            width: 100%;
            padding: 20px;
        }
        #satu h1,#satu h4{
            display: none;
        }
        #dua{
            background-image: url('asset-lelang/lelang2.jpg');
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            width: 100%;
            padding: 20px;
            color: white;
            text-align: center
        }
        
    }
</style>
    <section id="satu">
        <div class="judul">
            <h1> DETAIL EVENTS</h1>
        </div>
        <div class="row">
            <div class="card col-6">
                <h1>Event : {{$event->judul}}</h1>
                <img src="{{asset('storage/image/'.$event->lot_item[0]->barang_lelang->gambarlelang[0]->gambar)}}"  alt="...">
            </div>
            <div class="scroll col-6">
                <div class="card" >
                    <div class="card-body">
                        
                    </div>
                  </div>
                  <form>
                    <div class="mb-3">
                      <label for="exampleInputEmail1" class="form-label">Email</label>
                      <input type="text" class="form-control" name="email">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </form>
            </div>
        </div>
    </section>

@endsection