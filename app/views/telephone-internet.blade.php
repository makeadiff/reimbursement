@extends('layouts.master')

@section('body')
@section('navbar-header')
    <a class="navbar-brand" href=".">Reimbursement</a>
@stop

@section('navbar-links')
    <li><a href="" class="active">Monthly</a></li>
    <li><a href="./travel">Travel</a></li>
    <li><a href="./status">Status</a></li>
@stop

<div class="board">
    <div class="pin">
        <h2 class="sub-title">Monthly Reimbursement</h2>
        <br>

        <div class="row">
            <div class="col-md-4 col-md-offset-4 col-sm-12">
                @if($errors->count()>0)

                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>


                        @foreach($errors->all() as $message)

                            <strong>Error</strong> : {{$message}}

                        @endforeach

                    </div>
                @endif

                <form id="telephone-internet" role="form" method="post" enctype="multipart/form-data"
                      action={{action('Reimbursement@submitTelephoneInternet')}}>

                    <h4 class="sub-title">Month & Year</h4>

                    <div class="row">
                        <div class="form-group col-md-6 col-sm-12">
                            <select id="monthSelect" class="form-control" placeholder="Month" name="monthSelect">
                                <option selected>December</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6 col-sm-12">
                            <select id="yearSelect" class="form-control" placeholder="Year" name="yearSelect">
                                <option selected>2015</option>
                            </select>
                        </div>
                    </div>



            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-4 col-md-offset-4 col-sm-12">
                <h4 class="sub-title">Amount</h4>
                <input type="number" class="form-control" name="amount" placeholder="Amount">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-4 col-md-offset-4 col-sm-12">
                <h4 class="sub-title">Comments</h4>
                <textarea class="form-control" name="comments" placeholder="Comments"
                          form="telephone-internet"></textarea>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-4 col-md-offset-4 col-sm-12">
                <h4 class="sub-title">Attach Bills</h4>
                <input class="form-control" type="file" name="bills" form="telephone-internet">

            </div>
        </div>


        <div class="row">
            <div class="form-group col-md-4 col-md-offset-4 col-sm-12">
                <button type="submit" class="btn btn-default">Submit</button>
            </div>
        </div>
        </form>
    </div>
</div>

@stop