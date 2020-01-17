@extends('layouts.app', ['title' => __('Permissions Management')])

@section('content')
    @include('layouts.headers.cards')
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Permissions') }}</h3>
                            </div>
                        </div>

                        {!! Form::open(['id' => 'modulesearch', 'method' => 'GET', 'class' => 'form-horizontal mt-3', 'route' => ['permission.index']]) !!}
                            <div class="row">
                                <div class="col-md-2 col-sm-3">
                                    <div class="form-group">
                                        <input id="searchinput" placeholder="Search by Name" type="text" class="form-control" name="search" value="{{ $searchValue }}">
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
                                    <th scope="col">{{ __('Name') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissions as $key => $permission)
                                    <tr>
                                        <td>{{ $permission->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer py-4">
                        <nav class="d-flex justify-content-end" aria-label="...">
                            {{ $permissions->appends(request()->except('page'))->links() }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection