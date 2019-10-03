@extends('layouts.app')


@section('content')
<client
        :id="'{{Auth::user()->id}}'"
        :room_id="'{{Auth::user()->getRoomID()}}'"

        :avatar="'https://hsto.org/getpro/habr/company/125/20e/0e6/12520e0e64ae0c7dbd415a2c9ab3005d.jpg'"
        :name="'{{Auth::user()->name}}'"
></client>
@endsection
