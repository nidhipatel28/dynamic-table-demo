<!DOCTYPE html>
<html>
<head>
    <title>Organizations</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        .error-msg,.required {
            color: red;
        }
    </style>
</head>
<body>
    <form method="POST" enctype="multipart/form-data"  action="{{route('addGroup')}}">
        @csrf
        <div class="container">
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            <h1> Add organization </h2>
            <div class="form-outline mb-4">
                <label class="form-label" for="org_name">Organization name</label>
                <input type="text" name ="org_name" id="org_name" class="form-control" />
                <span class="error-msg">
                    {{ $errors->first('org_name') }}
                </span>
            </div>

            <div class="form-outline mb-4">
                <label class="form-label" for="org_code">Organization codes</label>
                <input type="text" name ="org_code" id="org_code" class="form-control" />
                <span class="error-msg">
                    {{ $errors->first('org_code') }}
                </span>
            </div>
            <!-- Submit button -->
            <button type="submit" class="btn btn-primary btn-block">Create organization</button>
        </div>
    </form>
</body>
</html>