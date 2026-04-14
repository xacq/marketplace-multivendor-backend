@extends('admin.master_layout')
@section('title')
<title>Contact Leads</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Contact Leads</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard') }}</a></div>
              <div class="breadcrumb-item">Contact Leads</div>
            </div>
          </div>

          <div class="section-body">
            <div class="row mt-4">
                <div class="col">
                  <div class="card">
                    <div class="card-body">
                      <div class="table-responsive table-invoice">
                        <table class="table table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th width="5%">{{__('admin.SN')}}</th>
                                    <th width="20%">Product</th>
                                    <th width="15%">Vendedor</th>
                                    <th width="15%">Interesado</th>
                                    <th width="15%">Email</th>
                                    <th width="10%">Estado</th>
                                    <th width="10%">Fecha</th>
                                    <th width="10%">{{__('admin.Action')}}</th>
                                  </tr>
                            </thead>
                            <tbody>
                                @foreach ($leads as $index => $lead)
                                    <tr>
                                        <td>{{ ++$index }}</td>
                                        <td><a href="{{ env('FRONTEND_URL') }}/product/{{ $lead->product->slug }}">{{ $lead->product->name }}</a></td>
                                        <td>{{ $lead->vendor->shop_name }}</td>
                                        <td>{{ $lead->name }}</td>
                                        <td>{{ $lead->email }}</td>
                                        <td>
                                            @if ($lead->status == 'new')
                                                <span class="badge badge-primary">{{ $statuses['new'] }}</span>
                                            @elseif ($lead->status == 'contacted')
                                                <span class="badge badge-warning">{{ $statuses['contacted'] }}</span>
                                            @elseif ($lead->status == 'in_process')
                                                <span class="badge badge-info">{{ $statuses['in_process'] }}</span>
                                            @elseif ($lead->status == 'sold' || $lead->status == 'won')
                                                <span class="badge badge-success">{{ $statuses[$lead->status] }}</span>
                                            @elseif ($lead->status == 'lost')
                                                <span class="badge badge-danger">{{ $statuses['lost'] }}</span>
                                            @else
                                                <span class="badge badge-secondary">{{ $statuses[$lead->status] }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $lead->created_at->format('d F, Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.leads.show', $lead->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                            <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteDataId('{{ route('admin.leads.destroy', $lead->id) }}')"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                  @endforeach
                            </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
          </div>
        </section>
      </div>

<script>
    function deleteDataId(id){
        $("#deleteForm").attr("action", id);
    }
</script>
@endsection
