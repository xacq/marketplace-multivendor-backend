@extends('seller.master_layout')
@section('title')
<title>Contact Lead Details</title>
@endsection
@section('seller-content')
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Lead Details: {{ $lead->name }}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('seller.dashboard') }}">{{__('admin.Dashboard') }}</a></div>
              <div class="breadcrumb-item active"><a href="{{ route('seller.leads.index') }}">Leads</a></div>
              <div class="breadcrumb-item">Detalles</div>
            </div>
          </div>

          <div class="section-body">
            <div class="row">
                <div class="col-md-6">
                  <div class="card">
                    <div class="card-header">
                        <h4>Lead Information</h4>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>Nombre</th>
                                <td>{{ $lead->name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $lead->email }}</td>
                            </tr>
                            <tr>
                                <th>Teléfono</th>
                                <td>{{ $lead->phone }}</td>
                            </tr>
                            <tr>
                                <th>Fecha</th>
                                <td>{{ $lead->created_at->format('d F, Y h:i A') }}</td>
                            </tr>
                            <tr>
                                <th>Mensaje</th>
                                <td>{{ $lead->message }}</td>
                            </tr>
                        </table>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="card">
                    <div class="card-header">
                        <h4>Update Status</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('seller.leads.updateStatus', $lead->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="">Estado</label>
                                <select name="status" class="form-control">
                                    @foreach($statuses as $key => $statusName)
                                        <option value="{{ $key }}" {{ $lead->status == $key ? 'selected' : '' }}>{{ $statusName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button class="btn btn-primary" type="submit">Guardar Cambios</button>
                        </form>
                    </div>
                  </div>

                  <div class="card mt-4">
                    <div class="card-header">
                        <h4>Producto Interesado</h4>
                    </div>
                    <div class="card-body">
                        <p><strong><a href="{{ env('FRONTEND_URL') }}/product/{{ $lead->product->slug }}" target="_blank">{{ $lead->product->name }}</a></strong></p>
                    </div>
                  </div>
                </div>

            </div>
          </div>
        </section>
      </div>
@endsection
