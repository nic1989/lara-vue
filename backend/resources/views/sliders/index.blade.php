@extends('layouts.app', ['title' => __('Slider Management')])

@section('content')
    @include('layouts.headers.cards')

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Sliders') }}</h3>
                            </div>
                            @can('slider-create')
                                <div class="col-4 text-right">
                                    <a href="{{ route('slider.create') }}" class="btn btn-sm btn-primary">{{ __('Add Slider') }}</a>
                                </div>
                            @endcan
                        </div>
                    </div>
                    
                    <div class="col-12">
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('status') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                    </div>

                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('Image Name') }}</th>
                                    <th scope="col">{{ __('Creation Date') }}</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($sliders) > 0)
                                    @foreach ($sliders as $slider)
                                        <tr>
                                            <td>{{ $slider->slider_name }}</td>
                                            <td>{{ $slider->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @can('slider-edit')
                                                    <a class="mr-1" title="Edit" href="{{ route('slider.edit', $slider->id) }}"><i class="fa fa-edit"></i></a>
                                                @endcan
                                                @can('slider-delete')
                                                    {!! Form::open(['method' => 'DELETE','route' => ['slider.destroy', $slider],'style'=>'display:inline']) !!}
                                                        <a title="Delete" href="javascript:void(0)" onclick="confirm('{{ __("Are you sure you want to delete this slider?") }}') ? this.parentElement.submit() : ''">
                                                            <i class="fa fa-trash text-red"></i>
                                                        </a>
                                                    {!! Form::close() !!}
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center" colspan="4">{{ __('No Record found') }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer py-4">
                        <nav class="d-flex justify-content-end" aria-label="...">
                            {{ $sliders->appends(request()->except('page'))->links() }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection