<link href="{{ asset('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker-standalone.css') }}" rel="stylesheet">
<!doctype html>
<head>
  <link rel="stylesheet" type="text/css" media="screen" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" />
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
  <link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
  <script type="text/javascript" src="//code.jquery.com/jquery-2.1.1.min.js"></script>
  <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
  <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
</head>
<form method="post" action="{{ route('add-payment-post') }}">
   {{csrf_field()}}
<body>
  <h1>Плащане</h1>
  @if ($errors->any())
    <div class="alert alert-danger alert-danger--customer">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if(Session::has('iban-error'))
    <div class="alert alert-danger">
        <ul>
            <li>{{Session::get('iban-error')}}</li>
        </ul>
    </div>
@endif
@if(Session::has('success'))
    <div class="alert alert-success">
        <ul>
            <li>{{Session::get('success')}}</li>
        </ul>
    </div>
@endif
<div class="form-container">
  <div class="form-group row">
    <label for="IBAN_orig" class="col-sm-5 col-form-label">IBAN на наредителя</label>
    <div class="col-sm-7">
      <input type="text" class="form-control" value="{{old('IBAN_orig')? old('IBAN_orig') : ''}}" id="IBAN_orig" placeholder="IBAN на наредителя" name="IBAN_orig">
    </div>
  </div>
  <div class="form-group row">
    <label for="IBAN_benef" class="col-sm-5 col-form-label">IBAN на бенефициента</label>
    <div class="col-sm-7">
      <input type="text" class="form-control" value="{{old('IBAN_benef')? old('IBAN_benef') : ''}}" id="IBAN_benef" placeholder="IBAN на бенефициента" name="IBAN_benef">
    </div>
  </div>
  <div class="form-group row">
    <label for="amount" class="col-sm-5 col-form-label">Сума</label>
    <div class="col-sm-7">
      <input type="text" class="form-control" value="{{old('amount')? old('amount') : ''}}" id="amount" placeholder="Сума" name="amount">
    </div>
  </div>
  <div class="form-group row">
    <label for="amount" class="col-sm-5 col-form-label">Дата</label>
    <div class='col-sm-7'>
      <div class="form-group">
        <div class='input-group date' id='datetimepicker1'>
          <input type='text' class="form-control" value="{{old('date')? old('date') : ''}}" name="date" />
          <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
          </span>
        </div>
      </div>
    </div>
    <script type="text/javascript">
      $(function () {
        $('#datetimepicker1').datetimepicker();
      });
    </script>
  </div>
  <div class="form-group row">
    <label for="reason" class="col-sm-5 col-form-label">Основание за плащане</label>
    <div class="col-sm-7">
      <input type="text" class="form-control" id="reason" value="{{old('reason')? old('reason') : ''}}" placeholder="Основание за плащане" name="reason">
    </div>
  </div>
  <div class="form-group row">
    <div class="col-sm-12">
      <button type="submit" class="btn btn-primary btn btn-primary--submit-button">Плати</button>
    </div>
  </div>
</div>
</body>
</form>
<html>
<style>

body{
  width: 70%;
  margin: 0 auto;
}

.btn-primary--submit-button{
  float:right;
}

.col-sm-12__validation-textbox{
  text-align: right;
}

.form-container, .alert-danger--customer{
    width: 50%;
    margin: 0 auto;
}

.alert-danger--customer{
    margin-bottom: 20px;
}

h1{
  text-align: center;
  margin-bottom: 35px;
}
</style>