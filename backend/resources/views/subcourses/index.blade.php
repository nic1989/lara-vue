@extends('layouts.app', ['title' => __('Sub Course Management')])

@section('content')
    @include('layouts.headers.cards')

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Sub Course') }}</h3>
                            </div>
                            @can('subcourse-create')
                                <div class="col-4 text-right">
                                    <a href="{{ route('subcourse.create') }}" class="btn btn-sm btn-primary">{{ __('Add Sub Course') }}</a>
                                </div>
                            @endcan
                        </div>
                        {!! Form::open(['id' => 'modulesearch', 'method' => 'Get', 'class' => 'form-horizontal mt-3', 'route' => ['subcourse.index']]) !!}
                            <div class="row">
                                <div class="col-md-2 col-sm-3">
                                    <div class="form-group">
                                        <input id="searchinput" placeholder="Search by Title" type="text" class="form-control" name="search" value="{{ $searchValue }}">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-3">
                                    <div class="form-group">
                                        <select name="status" class="form-control">
                                            <option @if($searchStatus == '') selected @endif>All</option>
                                            <option value="active" @if($searchStatus == 'active') selected @endif>Active</option>
                                            <option value="inactive" @if($searchStatus == 'inactive') selected @endif>inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('search') }}
                                        </button>
                                        <a href="{{Request::url()}}" class="btn btn-info">{{ __('clear') }}</a>
                                    </div>
                                </div>
                            </div>
                        {!! Form::close() !!}
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
                                    <th scope="col">{{ __('Title') }}</th>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col">{{ __('Creation Date') }}</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($subcourses) > 0)
                                    @foreach ($subcourses as $subcourse)
                                        <tr>
                                            <td>{{ $subcourse->title }}</td>
                                            <td>
                                                @if($subcourse->status == 1)
                                                    <span>Active</span>
                                                @else
                                                    <span>Inactive</span>
                                                @endif
                                            </td>
                                            <td>{{ $subcourse->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @can('subcourse-edit')
                                                    <a class="mr-1" title="Edit" href="{{ route('subcourse.edit', $subcourse->id) }}"><i class="fa fa-edit"></i></a>
                                                @endcan
                                                @can('subcourse-delete')
                                                    {!! Form::open(['method' => 'DELETE','route' => ['subcourse.destroy', $subcourse->id],'style'=>'display:inline']) !!}
                                                        <a title="Delete" href="javascript:void(0)" onclick="confirm('{{ __("Are you sure you want to delete this Sub Course?") }}') ? this.parentElement.submit() : ''">
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
                            {{ $subcourses->appends(request()->except('page'))->links() }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection