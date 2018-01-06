@extends('layouts.master')

@section('body')
@section('navbar-header')
<a class="navbar-brand" href=".">Reimbursement</a>
@stop

@section('navbar-links')
<li><a href="./telephone-internet">Telephone/Internet</a></li>
<li><a href="./travel">Travel</a></li>
<li><a href="./status">Status</a></li>
@stop


<div class="container-fluid">
    <div class="centered board">
        <br>
        <br>
        <h1 class="title">Success!</h1>
        <br>
        <div class="row">
           <p class="success">Successfully added reimbursement request with ID {{Session::get('id')}}. You will receive an email (on {{Session::get('email')}}) on request approval/rejection.</p>
        </div>
        <br>
        <div class="row">
            <a href='.' class='btn btn-primary btn-lg transparent'>Back to Home</a>
        </div>
    </div>
</div>
@stop
