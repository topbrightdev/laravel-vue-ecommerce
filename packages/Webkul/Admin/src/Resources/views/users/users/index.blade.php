@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.users.users.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.users.users.title') }}</h1>
            </div>

            <div class="page-action">
                @if (bouncer()->hasPermission('settings.users.users.create')) 
                    <a href="{{ route('admin.users.create') }}" class="btn btn-lg btn-primary">
                        {{ __('admin::app.users.users.add-user-title') }}
                    </a>
                @endif
            </div>
        </div>

        <div class="page-content">
            <datagrid-plus src="{{ route('admin.users.index') }}"></datagrid-plus>
        </div>
    </div>
@stop
