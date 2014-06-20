@extends('layouts.master')

@section('body')
@section('navbar-header')
<a class="navbar-brand" href=".">Reimbursement</a>
@stop

@section('navbar-links')
<li><a href="./telephone-internet">Telephone/Internet</a></li>
<li><a href="./travel">Travel</a></li>
@stop


<div class="container-fluid">
    <div class="centered">
        <br>
        <br>
        <h1 class="title">Oops!</h1>
        <br>
        <div class="row">
            <p class="success">Something went wrong. Please try again after sometime.</p>
        </div>
        <br>
        <div class="row">
            <a href='.' class='btn btn-primary btn-lg transparent'>Back to Home</a>
        </div>
    </div>
</div>
@stop
