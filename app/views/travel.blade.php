@extends('layouts.master')
@section('head')
<link rel="stylesheet" href="css/default.css" id="theme_base">
<link rel="stylesheet" href="css/default.date.css" id="theme_date">
@stop

@section('body')
@section('navbar-header')
<a class="navbar-brand" href=".">MADApp</a>
@stop

@section('navbar-links')
    <li><a href="./telephone-internet">Monthly</a></li>
    <li><a href="" class="active">Travel</a></li>
    <li><a href="./status">Status</a></li>
@stop

<div class="board">
    <div class="pin">
    <h2 class="sub-title">Travel Reimbursement</h2>
    <br>
        <form id="travel" role="form" method="post" enctype="multipart/form-data" action={{{action('Reimbursement@submitTravel')}}}>
        <div class="row"><div class="col-md-4 col-md-offset-4 col-sm-12">
            @if($errors->count()>0)

            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>


                @foreach($errors->all() as $message)

                <strong>Error</strong> : {{{$message}}}

                @endforeach

            </div>
            @endif
        <div class="row"><div class="form-group">
            <h4 class="sub-title">Reason for Travel</h4>
            <textarea class="form-control" name="reason" placeholder="Reason for Travel" ></textarea>
        </div></div>

        <div class="row"><div class="form-group">
            <h4 class="sub-title">Total Daily Bhatta Amount</h4>
            <input type="number" class="form-control" name="daily_bhatta" placeholder="Total Daily Bhatta Amount">
        </div></div>

        <div class="row"><div class="form-group">
            <h4 class="sub-title">Comments</h4>
            <textarea class="form-control" name="comments" placeholder="Comments"></textarea>
        </div></div>




        <br>




        <div id="travelDetails">
            <div class="row">

                    <h4 class="sub-title">Travel Details</h4>


            </div>
            <div class="row">

                <div class="col-md-6 col-sm-12">

                    <div class="form-group">
                        <input type="text" class="form-control" name="travel_from_a" id="travel_from_a" value="" placeholder="From" >
                    </div>
                </div>

                <div class="col-md-6 col-sm-12">
                    <div class="form-group" >
                        <input type="text" class="form-control" name="travel_to_a" id="travel_to_a" value="" placeholder="To">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <select name="modeSelect_a" class="form-control">
                            <option>--Mode--</option>
                            <option>Cab</option>
                            <option>Bus</option>
                            <option>Rail</option>
                            <option>Flight</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <input type="text" id='pickdate_a' name="pickdate_a" class="form-control" placeholder="Date">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <input type="number" name="amount_a" class="form-control" placeholder="Amount">
                    </div>
                </div>

                <div class="col-md-6 col-sm-12">
                    <input type="file" name="bills_a">
                </div>
            </div>

        </div>



        <br><br>
        <input type='button' value="Add Travel" id='addTravel' class="btn btn-default">
        <input type='button' value="Remove Travel" id='removeTravel' class="btn btn-default">
        <br><br>
        <button type="submit" class="btn btn-default">Submit</button>





      </form>
    </div>




</div></div>

</div>
</div>

<script src="js/custom.js"></script>
<script src="js/picker.js"></script>
<script src="js/picker.date.js"></script>

@stop