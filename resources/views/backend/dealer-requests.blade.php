@extends('layouts.backend.master')

@section('content')
    <table class="table">
        <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Ph.Num.</th>
            <th scope="col">Email</th>
            <th scope="col">Age</th>
            <th scope="col">Qualification/Experience</th>
            <th scope="col">Address</th>
            <th scope="col">Photo</th>
        </tr>
        </thead>
        <tbody>
        @foreach($dealerRequests as $dealerRequest)
            <tr>
                <th scope="row">1</th>
                <td>Mark</td>
                <td>Otto</td>
                <td>@mdo</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection