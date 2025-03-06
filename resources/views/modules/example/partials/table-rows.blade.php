@extends('components.module-table-rows')

@section('content')
<x-module-table-rows 
    :items="$items"
    :columns="$columns"
    routePrefix="admin.examples"
/>
@endsection 