@extends('layouts.main')

@section('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border bg-info">
                    <h3 class="box-title">Search</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <div class="box-body">
                    <div class="row">
                        <form role="form" action="{{ url('invoice') }}" method="post">
                            {{ csrf_field() }}
                            <div class="col-md-2">
                                <select name="agentId" id="agentId" class="form-control">
                                    <option value=""> -- Select Agents --</option>
                                    <option value="b2c" @if($agentId=='b2c'){{ 'selected' }}@endif>B2C</option>
                                    @foreach($agent->data as $row)
                                        <option value="{{ $row->agentId }}" @if($row->agentId==$agentId){{ 'selected' }}@endif>{{ $row->agentName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="dateType" id="dateType" class="form-control">
                                    <option value="1" @if($dateType==1){{ 'selected' }}@endif>Booking Date</option>
                                    <option value="2" @if($dateType==2){{ 'selected' }}@endif>Travel Date</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group date" name="startDate" id="datepicker1">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" name="startDate"
                                           onchange="createInvoice()" value="{{ $startDate }}"
                                           class="form-control pull-right" placeholder="mm/dd/yyyy">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group date" name="endDate" id="datepicker2">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" name="endDate" id="endDate"
                                           onchange="createInvoice()" value="{{ $endDate }}"
                                           class="form-control pull-right" placeholder="mm/dd/yyyy">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search-plus"></i>
                                    Search
                                </button>
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                        data-target="#modal-default" onclick="copy();"><i class="fa fa-save"></i> Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ $page_title }}</h3>
                    <div class="message_error"></div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <form role="form" id="getInvoice" action="{{ url('api/createInvoice') }}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="invRun" id="invRun" value="{{ $invRun }}">
                        <input type="hidden" name="invNo" id="invNo" value="">
                        <input type="hidden" name="end" id="end" value="">
                        <input type="hidden" name="datatype" id="datatype" value="">
                        <input type="hidden" name="custom" id="custom" value="">
                        <div class="table-responsive">
                            <table id="dataTable1" class="table table-bordered table-hover" role="grid">
                                <thead>
                                <tr class="bg-primary">
                                    <th rowspan="2"><input type="checkbox" id="checkAll"></th>
                                    <th rowspan="2">No.</th>
                                    <th rowspan="2">Booking Date</th>
                                    <th rowspan="2">Travel Date</th>
                                    <th rowspan="2">Voucher No</th>
                                    <th rowspan="2">Route</th>
                                    <th rowspan="2">B2B & B2C</th>
                                    <th rowspan="2">Agency Name</th>
                                    <th rowspan="2">Customer Name</th>
                                    <th colspan="2" class="text-center">Amount</th>
                                    <th colspan="2" class="text-center">Price/Pax</th>
                                    <th colspan="2" class="text-center">Total</th>
                                    <th rowspan="2">Grand Total</th>
                                </tr>
                                <tr class="bg-primary">
                                    <th>Adult</th>
                                    <th>Child</th>
                                    <th>Adult</th>
                                    <th>Child</th>
                                    <th>Adult</th>
                                    <th>Child</th>
                                </tr>
                                </thead>

                                <tbody>
                                @if(!empty($invoice->data))
                                    @foreach($invoice->data as $k => $row)
                                        @php $grandTotal += $row->grandTotal @endphp
                                        <tr>
                                            <td>
                                                <input type="checkbox" id="id{{ $row->voucherNo }}" name="items[]"
                                                       value="{{ $row->no - 1 }}"
                                                       onchange="getGrandTotal({{ "'".$row->voucherNo."',".$row->grandTotal }})">
                                                <input type="hidden" name="vouchers[]"
                                                       value="{{ $row->voucherNo }}">
                                                <input type="hidden" name="agents[]"
                                                       value="{{ $row->agentId }}">
                                                <input type="hidden" name="amounts[]"
                                                       value="{{ $row->grandTotal }}">
                                            </td>
                                            <td>{{ $row->no }}</td>
                                            <td>{{ $row->bookingDate }}</td>
                                            <input type="hidden" name="bookingDate[]" value="{{ $row->bookingDate }}">
                                            <td>{{ $row->travelDate }}</td>
                                            <input type="hidden" name="travelDate[]" value="{{ $row->travelDate }}">
                                            <td>{{ $row->voucherNo }}</td>
                                            <td>{{ $row->route }}</td>
                                            <td>{{ $row->type }}</td>
                                            <td>{{ $row->agentName }}</td>
                                            <td>{{ $row->customer }}</td>
                                            <td>{{ $row->adult }}</td>
                                            <td>{{ $row->child }}</td>
                                            <td>{{ number_format($row->priceAdult, 0) }}</td>
                                            <td>{{ number_format($row->priceChild, 0) }}</td>
                                            <td>{{ number_format($row->totalAdult, 0) }}</td>
                                            <td>{{ number_format($row->totalChild, 0) }}</td>
                                            <td>{{ number_format($row->grandTotal, 0) }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
                <div class="box-footer">
                    <div class="row">
                        <div class="col-lg-2 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <input type="hidden" id="grandTotal" name="grandTotal" value="{{ $grandTotal }}">
                                    <h3 id="showGrandTotal">{{ number_format($grandTotal, 0) }}</h3>
                                    <p>Grand Total</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-footer -->
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-default" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Invoice Running</h4>
                </div>
                <div class="modal-body">
                    <p><input type="text" class="form-control" id="invoice_no" value="{{ $invNo }}"></p>
                    <div class="row">
                        <div class=" col-md-4">
                            <input class="form-control" id="agentId2" name="agentId2" value="" readonly>
                        </div>
                        <div class=" col-md-4">
                            <select name="dateType2" id="dateType2" class="form-control" onchange="createInvoice()">
                                <option value="1" @if($dateType==1){{ 'selected' }}@endif>Booking Date</option>
                                <option value="2" @if($dateType==2){{ 'selected' }}@endif>Travel Date</option>
                                <option value="3" @if($dateType==3){{ 'selected' }}@endif>Custom Date</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <div class="input-group date" id="datepicker3">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" name="customDate"  id="customDate"
                                       onchange="createInvoice()" value="{{$newDate = date("m/d/Y")}}"
                                       class="form-control pull-right" placeholder="mm/dd/yyyy">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="getInvoice()">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection

@section('script')
    <!-- bootstrap datepicker -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="{{ asset('bower_components/admin-lte/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('js/jquery.number.min.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            $("#dataTable1").DataTable({
                'paging': false,
                aLengthMenu: [
                    [100, 200, 500, 1000, 1500, 2000, 3000, -1],
                    [100, 200, 500, 1000, 1500, 2000, 3000, "All"]
                ],
                iDisplayLength: 100,
                'lengthChange': false,
                'searching': false,
                'sorting': false,
                'ordering': false,
                'info': true,
                'autoWidth': false
            });
        });

        //Date picker

        $('#datepicker1, #datepicker2,#datepicker3').datepicker({
            autoclose: true
        })

        $("#checkAll").change(function () {
            $("input:checkbox").prop('checked', $(this).prop("checked"));
        });

        function getInvoice() {
            var invNo = $('#invoice_no').val();
            var checkbox = $("input:checkbox").is(":checked");
            $('#invNo').val(invNo);

            if (checkbox) {
                $('#getInvoice').submit();
                alert('Save successfully!')
            } else {
                alert('Please check record!');
            }
        }

        function createInvoice() {
            var selectDate = $('#customDate').val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var selectType = $('#dateType2').val();
            var dout = Date.parse(selectDate);
            $('#custom').val(selectDate);
            $('#end').val(endDate);
            $('#datatype').val(selectType);
        }

        function getGrandTotal(no, amount) {
            var grandTotal = $('#grandTotal').val();
            var sumTotal = 0;
            if ($('#id' + no).prop('checked')) {
                sumTotal = parseInt(grandTotal) + parseInt(amount);
                $('#showGrandTotal').html($.number(sumTotal));
            } else {
                sumTotal = parseInt(grandTotal) - parseInt(amount);
                $('#showGrandTotal').html($.number(sumTotal));
            }
        }

        function copy() {
            var a2 = document.getElementById("dateType")
            var b2 = document.getElementById("dateType2")
            b2.value = a2.value;
            $(document).ready(function () {
                var dateType = $('#dateType2').val();
                if (dateType == '3') {
                    $('#datatype').val(dateType);
                    $("#customDate").show();
                    $("#datepicker3").show();
                }
                else if (dateType == '2'){
                    $('#datatype').val(dateType);
                    $("#datepicker3").hide();
                    $("#customDate").hide();
                }
                else {
                    $('#datatype').val(dateType);
                    $("#datepicker3").hide();
                    $("#customDate").hide();
                }
                $('#dateType2').on('change', function () {
                    if (this.value == '3') {
                        $("#customDate").show();
                        $("#datepicker3").show();
                    }
                    else {
                        $("#datepicker3").hide();
                        $("#customDate").hide();
                    }
                });
            });
            var agent = $('#agentId').val();
            if (agent == "")
            {
                agent = "-- Select Agents --"
                $('#agentId2').val(agent)
            }
            else if(agent == "b2c"){
                agent = "B2C"
                $('#agentId2').val(agent)
            }
            else{
                $.ajax({
                    url: 'agent/query/'+agent,
                    method: "get",
                    success: function (data) {
                        $('#agentId2').val(data);
                        // alert(data);
                    }
                })
            }
        }
    </script>
@endsection
