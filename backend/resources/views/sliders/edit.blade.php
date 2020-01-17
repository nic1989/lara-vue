@extends('layouts.app', ['title' => __('Slider Management')])

@section('content')
    @include('layouts.navbars.commanheader', ['title' => __('Edit Slider')])   

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Slider Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('slider.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('slider.update', $slider) }}" autocomplete="off"  enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            
                            <h6 class="heading-small text-muted mb-4">{{ __('Slider information') }}</h6>
                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Title') }}</label>
                                    <input type="text" name="title" id="input-name" class="form-control form-control-alternative{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Title') }}" value="{{ old('title', $slider->title) }}" required autofocus>

                                    @if ($errors->has('title'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('title') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="form-group{{ $errors->has('slider_name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label">{{ __('Slider Image') }}</label>
                                    <input type="file" name="slider_name" class="form-control{{ $errors->has('slider_name') ? ' is-invalid' : '' }}" />
                                    
                                    <a href="{{url('uploads/slider/'.$slider->slider_name)}}" target="_blank">
                                        <img src="{{url('uploads/slider/'.$slider->slider_name)}}" width="100" height="100">
                                    </a>

                                    @if ($errors->has('slider_name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('slider_name') }}</strong>
                                        </span>
                                    @endif
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