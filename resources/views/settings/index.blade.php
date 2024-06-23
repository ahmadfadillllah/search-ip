@include('layout.head')
@include('layout.header')
@include('layout.right-sidebar')
@include('layout.sidebar')

<div class="content-wrapper">
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Update Session Terlebih Dahulu</h4>
                    <p>Cara ini masih manual, nanti akan di update kembali.</p>
                    <form class="forms-sample" action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="exampleInputName1">Session</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="{{ $session->session }}" name="session" >
                        </div>
                        <button type="submit" class="btn btn-primary me-2">Update</button>
                        <button class="btn btn-light">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layout.footer')
