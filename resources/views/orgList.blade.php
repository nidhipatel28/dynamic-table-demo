<!DOCTYPE html>
<html>
<head>
    <title>Organizations</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>
<body>
    
<div class="container mt-5">
    <a href="{{ route('addGroup')}}">Add organization</a>
    <h2 class="mb-4">Organization List</h2>
    <div class="alert alert-success" style="display:none"></div>
    <table class="table table-bordered yajra-datatable">
        <thead>
            <tr>
                <th>No</th>
                <th>Organization Name</th>
                <th>Organization Code</th>
                <th>Status</th>
                <th colspan="2">Action</th>
            </tr>
        </thead>
        <tbody>
        @if($orgList->count() == 0)
            <tr>
                <td colspan="5"> No records found.</td>
            </tr>
        @else
        @foreach($orgList as $key => $org)
            <tr class="table-list">
                <td>{{ $key+1 }}</td>
                <td class="org-name-{{$org->id}}">
                    {{ $org->org_name }}
                </td>
                <td>{{ $org->org_code }}</td>
                <td><input type="checkbox" class="status" org-id="{{ $org->id }}" name="status" value="{{$org->status}}" {{ ($org->status == 1) ? 'checked' : ''}}></td>
                <td><input type="submit" value="Edit" id="update_{{ $org->id }}" org-id="{{ $org->id }}" class="btn btn-primary editbtn">
                <input type="submit" value="Delete" data-id="{{ $org->id }}" class="btn btn-danger delete"></td>
            </tr>
        @endforeach
        </tbody>
        @endif
        
    </table>
</div>
   
</body>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<script>
$(document).ready(function(){
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    // on edit button append text box
    $(document).on("click", ".editbtn", function(){
        let orgId = $(this).attr('org-id');
        var $this = $('.org-name-'+orgId);
        var $input = $('<input>', {
            value: $.trim($this.text()),
            type: 'text',
            name: 'org_name',
            id: 'org_name_'+orgId,
        }).appendTo( $this.empty() ).focus();
        $(this).val('Save');
        $(this).removeClass('editbtn');
        $(this).addClass('savebtn');
    });

    // save data on click and replace text box with name
    $(document).on("click", ".savebtn", function(){
        let orgId = $(this).attr('org-id');
        var name = $('#org_name_'+orgId).val();

        if(name != ''){
            $.ajax({
            url: 'update-org',
            type: 'post',
            data: {_token: CSRF_TOKEN,orgId: orgId,name: name},
            success: function(response){
                $('#update_'+orgId).val('Edit');
                $('#update_'+orgId).addClass('editbtn');
                $('#update_'+orgId).removeClass('savebtn');
                $('.org-name-'+orgId).text(name);
                $('.alert-success').css('display','block');
                $('.alert-success').text(response.message);
            }
            });
        }else{
            alert('Organization name can not be empty.');
        }
    });

    // update organization status
    $('.status').on('change', function (){
        var $this = $(this);
        $.ajax({
            data: {_token: CSRF_TOKEN,orgId: $this.attr('org-id'),status: $(this).is(':checked')},
            url: 'update-org',
            type: 'POST',
            success : function(response){
                $('.alert-success').css('display','block');
                $('.alert-success').text(response.message);
            }
        });
    });

    // Delete record
    $(document).on("click", ".delete" , function() {
        var delete_id = $(this).data('id');
        var el = this;
        var confirmation = confirm("Do you really want to delete record?");
        if(confirmation){
            $.ajax({
                url: 'delete-org/'+delete_id,
                type: 'get',
                success: function(response){
                    $(el).closest( "tr" ).remove();
                    $('.alert-success').css('display','block');
                    $('.alert-success').text(response.message);
                }
            });
        }
    });

});
</script>
</html>