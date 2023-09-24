@extends('admin.layout.default')
@section('content')
    <div class="content">
        <div class="card mb-3">
            <div class="card-body d-flex justify-content-between align-items-center py-3">
                <h5 class="text-primary">Sub Admins</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Sub Admins</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">

                <form class="row g-3 needs-validation" novalidate="">
                    <div class="col-md-4">
                        <label class="form-label" for="validationCustom01">First name</label>
                        <input class="form-control" id="validationCustom01" type="text" value="Mark" required="">
                        <div class="valid-feedback">Looks good!</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="validationCustom02">Last name</label>
                        <input class="form-control" id="validationCustom02" type="text" value="Otto" required="">
                        <div class="valid-feedback">Looks good!</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="validationCustomUsername">Username</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text rounded-end-0" id="inputGroupPrepend">@</span>
                            <input class="form-control" id="validationCustomUsername" type="text"
                                aria-describedby="inputGroupPrepend" required="">
                            <div class="invalid-feedback">Please choose a username.</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">City</label>
                        <input class="form-control" id="validationCustom03" type="text" required="">
                        <div class="invalid-feedback">Please provide a valid city.</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="validationCustom04">State</label>
                        <select class="form-select" id="validationCustom04" required="">
                            <option selected="" disabled="" value="">Choose...</option>
                            <option>...</option>
                        </select>
                        <div class="invalid-feedback">Please select a valid state.</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="validationCustom05">Zip</label>
                        <input class="form-control" id="validationCustom05" type="text" required="">
                        <div class="invalid-feedback">Please provide a valid zip.</div>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" id="invalidCheck" type="checkbox" value="" required="">
                            <label class="form-check-label mb-0" for="invalidCheck">Agree to terms and conditions</label>
                            <div class="invalid-feedback mt-0">You must agree before submitting.</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">Submit form</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
