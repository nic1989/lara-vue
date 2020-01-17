@extends('layouts.app', ['title' => __('User Management')])

@section('content')
    @include('layouts.navbars.commanheader', ['title' => __('Edit User')])   

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('User Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('user.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('user.update', $user) }}" autocomplete="off">
                            @csrf
                            @method('put')

                            <h6 class="heading-small text-muted mb-4">{{ __('User information') }}</h6>
                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                                    <input type="text" name="name" id="input-name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name', $user->name) }}" required autofocus>

                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-email">{{ __('Email') }}</label>
                                    <input type="email" name="email" id="input-email" class="form-control form-control-alternative{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email') }}" value="{{ old('email', $user->email) }}" required>

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-password">{{ __('Password') }}</label>
                                    <input type="password" name="password" id="input-password" class="form-control form-control-alternative{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('Password') }}" value="">
                                    
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="input-password-confirmation">{{ __('Confirm Password') }}</label>
                                    <input type="password" name="password_confirmation" id="input-password-confirmation" class="form-control form-control-alternative" placeholder="{{ __('Confirm Password') }}" value="">
                                </div>
                                @if(Auth::user()->user_type == 2)
                                    <input type="hidden" value="{{Auth::user()->id}}" name="parent_id">
                                @else
                                    <div class="form-group{{ $errors->has('parent_id') ? ' has-danger' : '' }}">
                                        @if($errors->has('parent_id'))
                                            @php $class = 'form-control is-invalid' @endphp
                                        @else
                                            @php $class = 'form-control' @endphp
                                        @endif
                                        
                                        <label class="form-control-label">{{ __('Client') }}</label>
                                        <select name="parent_id" class="{{$class}}">
                                            <option value=''>Please Select Client</option>
                                            @foreach($clients as $key => $client)
                                                <option value="{{$key}}" @if($key == $user->parent_id) selected @endif>{{$client}}</option>
                                            @endforeach
                                        </select>
                                        
                                        @if ($errors->has('parent_id'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('parent_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label class="form-control-label" for="input-password-confirmation">{{ __('Status') }}</label>
                                    <div class="custom-control custom-radio mb-3">
                                        <input name="status" value="1" class="custom-control-input" @if($user->status == 1) checked="" @endif id="customRadio5" type="radio">
                                        <label class="custom-control-label" for="customRadio5">Active</label>
                                        </div>
                                        <div class="custom-control custom-radio mb-3">
                                        <input name="status" value="0" class="custom-control-input" id="customRadio6" @if($user->status == 0) checked="" @endif type="radio">
                                        <label class="custom-control-label" for="customRadio6">Inactive</label>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection