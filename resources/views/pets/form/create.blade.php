@extends('layouts.app')

@section('content')
    <style>
        .bg-wall{
            width: 100%;
            height: 700px;
            background: #43C6AC;  /* fallback for old browsers */
            background: -webkitlinear-gradient(to right, rgba(25, 22, 84, 0.61), rgba(67, 198, 172, 0.6)), url("img/background.jpg"); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
            background: linear-gradient(to right, rgba(25, 22, 84, 0.61), rgba(67, 198, 172, 0.6)), url("img/background.jpg"); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
            background-size: cover;
        }
    </style>
    <div class="bg-wall">

    </div>
@endsection
