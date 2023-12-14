@extends('bisualmail::layout.app')

@section('title', 'New Template')

@section('editor', true)

@section('css')
<link rel="stylesheet" type="text/css" href="{{ url('app-assets/vendors/css/pickers/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@stop

@section('content-mails')

<div class="col-lg-10 col-md-12">
    <div class="card">
        <div class="card-body pb-4">
            <form action="{{ route('backetfy.mails.createNewsletter') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-12 pb-2 pt-2">
                        <h3>Nueva newsletter</h3>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Título</label>
                            <input type="text" class="form-control" name="title">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Fecha</label>
                            <input type="text" autocomplete="off" class="form-control pickadate" name="send_date[date]">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Hora</label>
                            <input type="text" autocomplete="off" class="form-control timepicker" name="send_date[time]">
                        </div>
                    </div>
                    <div class="col-12 pt-2 pb-2">
                        <h5>Filtros</h5>
                    </div>
                    <div class="col-md-6">
                        <label>Rol/es</label>
                        <select class="select2" multiple name="filters[role_id][]" style="display: block;">
                            @foreach (App\Role::all() as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>País/es</label>
                        <select class="select2" multiple name="filters[country_id][]" style="display: block;">
                            @foreach (App\Country::all() as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 pt-4">
                        <button class="btn btn-primary">Crear</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
    <script src="{{ url('app-assets/vendors/js/pickers/pickadate/legacy.js')}}"></script>
    <script src="{{ url('app-assets/vendors/js/pickers/pickadate/picker.js')}}"></script>
    <script src="{{ url('app-assets/vendors/js/pickers/pickadate/picker.date.js')}}"></script>
    <script src="{{ url('app-assets/vendors/js/pickers/pickadate/picker.time.js')}}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $('.select2').select2({
            languague: 'es',
            width: '100%',
        });

        $('.pickadate').pickadate({
			selectMonths: true,
			selectYears: true,
			format: 'dd/mm/yyyy',
			monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
			weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
			today: 'Hoy',
			clear: 'Limpiar',
			close: 'Cerrar',
			firstDay: 'Lun',
		});
        $('.timepicker').pickatime({
            format: 'H:i'
        })
    </script>
@endsection
   
@endsection