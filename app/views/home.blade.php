@extends('layouts.master')

@section('body')

    <div class="container-fluid">
        <div class="centered">
            <br>
            <br>
            <h1 class="title">Reimbursement</h1>
            <br>

            @if(!empty($message))
            <div class="row">
                <div class="col-md-4 col-md-offset-4 col-sm-12">
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <strong>Error :</strong> {{$message}}
                    </div>
                    <br>
                </div>
                </div>
            @endif

            <div class="row">
                <a href='telephone-internet' class='btn btn-primary btn-lg transparent'>Monthly</a>
            </div>
            <br>
            <div class="row">
                <a href='travel' class='btn btn-primary btn-lg transparent'>Travel</a>
            </div>
            <br>
            <br>
            <div class="row">
                <a href='status' class='btn btn-primary btn-lg transparent'>Status</a>
            </div>
        </div>
    </div>
@stop
