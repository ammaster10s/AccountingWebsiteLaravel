@extends('layouts.main')

@section('content')
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
                        <form role="form" action="{{ url('history') }}" method="post">
                            {{ csrf_field() }}
                            <div class="col-md-3">
                                <input type="text" name="invNo" id="invNo" value="{{ $invNo }}" class="form-control"
                                       placeholder="Invoice No">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search-plus"></i>
                                    Search
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
                    <div class="table-responsive">
                        <table id="dataTable1" class="table table-bordered table-hover" role="grid">
                            <thead>
                            <tr class="bg-primary">
                                <th width="5%">No.</th>
                                <th>Inv No</th>
                                <th width="20%">Inv Status</th>
                                <th width="10%">PDF</th>
                                <th width="10%">Cancel</th>
                            </tr>
                            </thead>

                            <tbody>
                            @if(!empty($invoice->data))
                                @php $no = 1; @endphp
                                @foreach($invoice->data as $k => $row)
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>
                                                <span style="cursor: pointer"
                                                      @if($row->status=='Y')onclick="getVoucherNo({{"'".$row->invNo."'"}})"@endif>
                                                    {{ $row->invNo }}
                                                </span>
                                        </td>
                                        <td>
                                            @if($row->status=='Y')
                                                <span class="badge bg-green">Complete</span>
                                            @else
                                                <span class="badge bg-red">Cancel</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger"
                                                    onclick="genInvoice('{{$row->invNo}}')">
                                                <i class="fa fa-file-pdf-o"></i> PDF
                                            </button>
                                        </td>
                                        <td>
                                            @if($row->status=='Y')
                                                <button type="button" class="btn btn-warning"
                                                        onclick="updateInvoice({{"'".$row->invNo."'"}})">
                                                    Cancel
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @php $no++ @endphp
                                @endforeach
                            @endif
                            </tbody>
                        </table>
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
                    <h4 class="modal-title">Voucher No</h4>
                </div>
                <div class="modal-body">
                    <p id="showVoucherNo"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <form role="form" id="genInvoice" action="{{ url('genInvoice') }}" method="post" target="_blank">
        {{ csrf_field() }}
        <input type="hidden" name="invNo" id="invoiceNo" value="">
    </form>

    <form role="form" id="updateInvoice" action="{{ url('history/update') }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="invNo" id="invoiceNo2" value="">
    </form>
@endsection

@section('script')
    <!-- bootstrap datepicker -->
    <script src="{{ asset('bower_components/admin-lte/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            $("#dataTable1").DataTable({
                'paging': false,
                aLengthMenu: [
                    [100, 200, 500, 1000, 1500, 2000, 3000, -1],
                    [100, 200, 500, 1000, 1500, 2000, 3000, "All"]
                ],
                iDisplayLength: 25,
                'lengthChange': false,
                'searching': false,
                'sorting': false,
                'ordering': false,
                'info': true,
                'autoWidth': false
            });
        });

        //Date picker
        $('#datepicker1, #datepicker2').datepicker({
            autoclose: true
        });

        function genInvoice(invNo) {
            $('#invoiceNo').val(invNo);
            $('#genInvoice').submit();
        }

        function updateInvoice(invNo) {
            $('#invoiceNo2').val(invNo);
            var result = confirm("Want to change?");
            if (result) {
                $('#updateInvoice').submit();
            }
        }

        function getVoucherNo(id) {
            $('#modal-default').modal('show');
            $('#showVoucherNo').html('');
            $.ajax({
                type: 'GET',
                url: '/api/getVoucherNo?invNo=' + id,
                dataType: 'json',
                success: function (data) {
                    $.each(data.data, function (index, element) {
                        $('#showVoucherNo').append('<span class="badge bg-blue">' + element.voucherNo + '</span>  ');
                    });
                }
            });
        }
    </script>
@endsection
