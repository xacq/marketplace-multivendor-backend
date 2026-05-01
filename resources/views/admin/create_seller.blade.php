@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Create Admin')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Seller List')}}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item active"><a href="{{ route('admin.seller-list') }}">{{__('admin.Seller List')}}</a></div>
              <div class="breadcrumb-item">{{__('admin.Add New')}}</div>
            </div>
          </div>

          <div class="section-body">
            <a href="{{ route('admin.seller-list') }}" class="btn btn-primary"><i class="fas fa-list"></i> {{__('admin.Seller List')}}</a>
            <div class="row mt-4">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.seller-store') }}" method="POST">
                            @csrf
                            <h5 class="mb-3 text-primary">{{__('admin.User Name')}}</h5>
                            <div class="row">
                                <div class="form-group col-md-6 col-12">
                                    <label>{{__('admin.Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                                    @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                                <div class="form-group col-md-6 col-12">
                                    <label>{{__('admin.Email')}} <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                                    @error('email')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                                <div class="form-group col-md-6 col-12">
                                    <label>{{__('admin.Password')}} <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="password">
                                    @error('password')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                                <div class="form-group col-md-6 col-12">
                                    <label>{{__('admin.Phone')}} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="phone" value="{{ old('phone') }}">
                                    @error('phone')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                            </div>

                            <hr>
                            <h5 class="mb-3 text-primary">{{__('admin.Shop Details')}}</h5>
                            <div class="row">
                                <div class="form-group col-md-6 col-12">
                                    <label>{{__('admin.Shop Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="shop_name" value="{{ old('shop_name') }}">
                                    @error('shop_name')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                                <div class="form-group col-md-6 col-12">
                                    <label>{{__('admin.Address')}}</label>
                                    <input type="text" class="form-control" name="address" value="{{ old('address') }}">
                                </div>
                                <div class="form-group col-md-6 col-12">
                                    <label>{{__('admin.Opens at')}}</label>
                                    <input type="time" class="form-control" name="open_at" value="{{ old('open_at', '09:00') }}">
                                </div>
                                <div class="form-group col-md-6 col-12">
                                    <label>{{__('admin.Closed at')}}</label>
                                    <input type="time" class="form-control" name="closed_at" value="{{ old('closed_at', '18:00') }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Current Logo')}}</label>
                                    <div class="mb-2">
                                        @if($defaultAvatar && $defaultAvatar->become_seller_avatar)
                                            <img src="{{ asset($defaultAvatar->become_seller_avatar) }}" width="100px" alt="Logo por defecto" class="rounded">
                                        @else
                                            <span class="text-muted">Sin logo por defecto configurado</span>
                                        @endif
                                    </div>
                                    <small class="text-muted">Se asignará automáticamente. El vendedor podrá cambiarlo desde su panel.</small>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">{{__('admin.Save')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                  </div>
                </div>
            </div>
          </div>
        </section>
      </div>
@endsection
