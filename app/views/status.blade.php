@extends('layouts.master')

@section('body')
@section('navbar-header')
<a class="navbar-brand" href=".">Reimbursement</a>
@stop

@section('navbar-links')
<li><a href="./telephone-internet" class="active">Monthly</a></li>
<li><a href="./travel">Travel</a></li>
<li><a href="" class="active">Status</a></li>
@stop

<div class="board">
<div class="row">
    <div class="form-group col-md-4 col-md-offset-4 col-sm-12">
        <h2 class="sub-title">Status</h2>
        <br>
        <div class="table-responsive">
            <table class="table table-bordered status">
                <tr>
                    <th>Id</th><th>Type</th><th>Amount</th><th>Date</th><th>Status</th>
                </tr>
                @foreach($rows as $row)
                    <tr>
                        <td>
                            {{$row->Id}}
                        </td>
                        <td>
                            @if($row->Type == "Telephone/Internet")
                                Monthly
                            @else
                                Travel
                            @endif
                        </td>
                        <td>
                            @if($row->Type == "Telephone/Internet")
                                {{$row->Telephone_Internet_Amount}}
                            @else
                                {{$row->Travel_Amount}}
                            @endif
                        </td>
                        <td>
                            {{$row->Telephone_Internet_Month}}, {{$row->Telephone_Internet_Year}}
                        </td>
                        <td>
                            @if($row->Status == 'Created')
                                Pending Approval
                            @else
                                {{$row->Status}}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>

    </div>
</div>
</div>
@stop