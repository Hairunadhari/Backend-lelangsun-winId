@extends('app.layouts')
@section('content')
<div class="section-header">
    <h1>Lacak Pengiriman</h1>
  </div>
<div class="section-body">
    <div class="row">
        <div class="col-12">
          <div class="activities">
          @foreach ($data['history'] as $item)
                <div class="activity">
                  <div class="activity-icon bg-primary text-white shadow-primary">
                    <i class="fas fa-map-marker-alt"></i>
                  </div>
                  <div class="activity-detail">
                    <div class="mb-2">
                      <span class="text-job">{{ \Carbon\Carbon::parse($item['updated_at'])->format('Y-m-d H:i:s') }}</span>
                      <span class="bullet"></span>
                      <a class="text-job">Status : {{$item['status']}}</a>
                      <div class="float-right dropdown">
                        <div class="dropdown-menu">
                          <div class="dropdown-title">Options</div>
                          <a href="#" class="dropdown-item has-icon"><i class="fas fa-eye"></i> View</a>
                          <a href="#" class="dropdown-item has-icon"><i class="fas fa-list"></i> Detail</a>
                          <div class="dropdown-divider"></div>
                          <a href="#" class="dropdown-item has-icon text-danger" data-confirm="Wait, wait, wait...|This action can't be undone. Want to take risks?" data-confirm-text-yes="Yes, IDC"><i class="fas fa-trash-alt"></i> Archive</a>
                        </div>
                      </div>
                    </div>
                    <p>{{$item['note']}}</p>
                  </div>
                </div>
                @endforeach
              </div>
        </div>
    </div>
</div>

@endsection
