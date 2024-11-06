{{-- @extends('layouts.app') --}}

{{-- @section('content') --}}
    <div class="container" style="">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">{{$title}} </h3>
                {{-- <ul class="breadcrumbs mb-3">
                    <li class="nav-home">
                        <a href="#">
                            <i class="icon-home"></i>
                        </a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Home</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">User List</a>
                    </li>
                </ul> --}}
                {{-- <button class="btn btn-primary btn-round ms-auto d-flex align-items-center">
                    <i class="fa fa-plus"></i>
                    <a href="{{ route('users.create') }}"> Add Row </a>
                </button> --}}
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ $route }}" method="post" id="data-form">
                                @csrf
                                @if ($method == 'PUT')
                                    @method('PUT')
                                @else
                                    @method('POST')
                                @endif
                                <div class="mb-3 row">
                                    <label for="name"
                                        class="col-md-4 col-form-label text-md-end text-start">Name</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control @error('code') is-invalid @enderror"
                                            id="name" name="name" value="{{ @$data->name }}">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="description"
                                        class="col-md-4 col-form-label text-md-end text-start">Description</label>
                                    <div class="col-md-6">
                                        <input type="text"
                                            class="form-control @error('description') is-invalid @enderror" id="description"
                                            name="description" value="{{ @$data->description }}">
                                        @error('description')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="description" class="col-md-4 col-form-label text-md-end text-start">Due
                                        Date</label>
                                    <div class="col-md-6">
                                        <input type="date"
                                            class="form-control @error('description') is-invalid @enderror" id="due_date"
                                            name="due_date" value="{{ @$data->due_date }}">
                                        @error('due_date')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="description" class="col-md-4 col-form-label text-md-end text-start">Select
                                        Status</label>
                                    <div class="col-md-6">
                                        {{ _select_option(get_status(), 'status_id', @$data->status_id, 'form-control', '') }}
                                        @error('status_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="description"
                                        class="col-md-4 col-form-label text-md-end text-start">Active</label>
                                    <div class="col-md-6">
                                        <input type="checkbox" class=" @error('is_active') is-invalid @enderror"
                                            <?php if (@$data->is_active == 1) {
                                                echo 'checked';
                                            } ?> id="is_active" name="is_active" value="1">
                                        @error('is_active')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="description"
                                        class="col-md-4 col-form-label text-md-end text-start">Partner</label>
                                    <div class="col-md-6">
                                        <?php if(get_partner() != NULL) { foreach (get_partner() as $key => $value) { ?>
                                        <input type="checkbox" class=""
                                            <?php if (@get_partner_campaigns($data->id,$value->id) == true) {
                                                echo 'checked';
                                            } ?> id="" name="partner_id[]" value="<?php echo  $value->id; ?>"> <?php echo  $value->name; ?>&nbsp;&nbsp; 
                                         <?php  } } ?>    
                                        @error('partner_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="image"
                                        class="col-md-4 col-form-label text-md-end text-start">Asset Upload</label>
                                    <div class="col-md-6">
                                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                                            id="" name="image" value="{{ @$data->image }}">
                                        @error('image')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="additional-img">
                                            <label for="">Additional Images</label>
                        
                                            <div class="upload--col">
                                                <div class="drop-zone">
                                                    <div class="drop-zone__prompt">
                        
                                                        <div class="drop-zone_color-txt">
                                                            <span><img src="assets/images/Image.png" alt=""></span> <br />
                                                            <span><img src="assets/images/fi_upload-cloud.svg" alt=""> Upload
                                                                Image</span>
                                                        </div>
                                                    </div>
                                                    <input type="file" name="myFile" class="drop-zone__input">
                                                </div>
                        
                                                <!-- <button type="submit" class="primary-btn">Add</button> -->
                                            </div>
                                        </div>

                                    </div>
                                    
                                    
                                </div>

                                

                               
                                </br>
                                <div class="mb-3 row offset-md-5" style="text-align: center;">
                                    <input type="submit" class="col-md-2 btn btn-primary" value="Save">
                                    <a class="col-md-2 btn btn-danger" href="{{ url('campaigns') }}">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{-- @endsection --}}
@section('script')
    <script>
        $('form#data-form').on('submit', function(e) {
            e.preventDefault();
            var _this = $(this);
            let data = new FormData(_this[0]);
            $('#loading').show();
            if (_this.find('input[name=_method]').val()) {
                var _method = _this.find('input[name=_method]').val();
            } else {
                var _method = 'POST';
            }
            if (_method === "DELETE") {
                var status = confirm("Are you sure you want to delete ?");
                if (status == false) {
                    $('#loading').hide();
                    return false;
                }
            }
            _this.find('span.help-block').remove();
            _this.find('div.form-group').removeClass('has-error');
            // $('#loading').hide();
            $.ajax({
                url: _this.attr('action'),
                type: 'post',
                data,
                contentType: 'multipart/form-data',
                cache: false,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#loading').hide();
                    if (_method === "DELETE") {
                        //   window.location.href = response.redirect_url;
                    } else {
                        window.location.href = response.redirect_url;
                    }
                },
                error: function(response) {
                    $('#loading').hide();
                    var response = $.parseJSON(response.responseText);
                    $.each(response.errors, function(key, value) {
                        if (key == 'permission_check') {
                            _this.find('div.permission_check').after(
                                '<span class="help-block"> ' + value + ' </span>');
                        }
                        if (key == 'user_role_id') {
                            _this.find('div.user_role_id').after('<span class="help-block"> ' +
                                value + ' </span>');
                        }
                        _this.find('input[name=' + key + '], select[name=' + key +
                            '], textarea[name=' + key + ']').parent().addClass('has-error');
                        _this.find('input[name=' + key + '], select[name=' + key +
                            '], textarea[name=' + key + ']').after(
                            '<span class="help-block"> ' + value + ' </span>');
                    });
                }
            })

        });
    </script>
@endsection
