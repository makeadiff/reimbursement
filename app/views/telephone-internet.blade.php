@extends('layouts.master')

@section('body')
@section('navbar-header')
    <a class="navbar-brand" href=".">Reimbursement</a>
@stop

@section('navbar-links')
    <li><a href="" class="active">Monthly</a></li>
    {{-- <li><a href="./travel">Travel</a></li> --}}
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

                @if (date("d") > 7)
                    <p>The reimbusment portal is closed for the month of {{ date("F", strtotime("-1 month")) }}.</p>
                @else 
                    <form id="telephone-internet" role="form" method="post" enctype="multipart/form-data"
                          action={{action('Reimbursement@submitTelephoneInternet')}}>

                        <h4 class="sub-title">Month & Year</h4>

                        <div class="row">
                            <div class="form-group col-md-6 col-sm-12">
                                <select id="monthSelect" class="form-control" placeholder="Month" name="monthSelect">
                                    {{--Use full names of months when putting the options since that's how they are checked in Salesforce--}}
                                    <option selected>{{ date("F", strtotime("-1 month")) }}</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6 col-sm-12">
                                <select id="yearSelect" class="form-control" placeholder="Year" name="yearSelect">
                                    <option selected>2021</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4 col-md-offset-4 col-sm-12">
                      <!-- Update amount to 300 if for one month, and to 300 if for two months -->
                        <!-- <p class="text center"><em>*Note: Fellows who had applied for reimbursements for June and July are requested to apply for the said timeline again, i.e June, July and August so that we can process the reimbursements for the three months together.</em></p> -->
                        <p class="text center">Default Monthly Telephonic Reimbursement amount is a fixed INR 400</p>
                        <input type="hidden" class="form-control" name="amount" value="400"/>
                    </div>
                </div>
                <!-- <div class="row">
                    <div class="form-group col-md-4 col-md-offset-4 col-sm-12">
                        <h4 class="sub-title">Comments</h4>
                        <textarea class="form-control" name="comments" placeholder="Comments"
                                  form="telephone-internet"></textarea>
                    </div>
                </div> -->
                <!-- <div class="row">
                    <div class="form-group col-md-4 col-md-offset-4 col-sm-12">
                        <h4 class="sub-title">Attach Bills</h4>
                        <input class="form-control" type="file" name="bills" form="telephone-internet">

                    </div>
                </div> -->


                <div class="row center">
                    <div class="form-group col-md-4 col-md-offset-4 col-sm-12">
                        <button type="submit" class="btn btn-default">Submit</button>
                    </div>
                </div>
                </form>
            @endif
    </div>
</div>

@stop
