@extends('main.main_layout')

@section('head')
    <title>Sewanee Eats | Support </title>
@stop

@section('body')
    <style>
        label {;
        }
    </style>
    <link rel="stylesheet" href="{{asset('css/support.css',env('APP_ENV') != 'local')}}">
    <div class="container support">
        <br>
        <p class="row support-h"> We are always available to listen to your concern or/and suggestion</p>
        <hr>
        <form action="{{ url()->to(parse_url(route('createIssue',[]),PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
              method="post">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="formGroupExampleInput">Full Name</label>
                <br>
                <input type="text" class="form-control" id="fullname"
                       name="name" maxlength="150" required
                       value="{{ old('name')  }}">
            </div>
            <div class="form-group">
                <label for="formGroupExampleInput2"> Your Email</label>
                <br>
                <input type="text" class="form-control" id="email"
                       name="email" maxlength="150" required value="{{ old('email')  }}">
            </div>
            <div class="form-group">
                <label for="formGroupExampleInput2"> Subject </label>
                <br>
                <input type="text" class="form-control" id="subject"
                       name="subject" maxlength="150" required value="{{ old('subject')  }}">
            </div>
            <div class="form-group">
                <label for="formGroupExampleInput2">If your concern/suggestion is about your order, please provide your
                    order confirmation number below</label>
                <br>
                <input type="text" class="form-control" id="order-id"
                       name="confirmation_number" maxlength="100"
                       value="{{ old('confirmation_number')  }}">
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="formGroupExampleInput2"> Can you select what applies best to your concern? </label>

                </div>
                <div class="row">
                    <div class="select-wrap">
                    <select class="row custom-select" name="issue_type" id="inlineFormCustomSelect" required>
                        <option class="issue-type" value="1" selected>Concern</option>
                        <option class="issue-type" value="2">Suggestion</option>
                    </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="formGroupExampleInput2">Please expand your concern</label>
                <br>
                <textarea maxlength="500" required class="form-control" name="body"
                          id="concern">{{ old('body') }}</textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>

    <script>
      var OLD_ISSUE_TYPE_INPUT = "{{ old('issue_type') }}";
      /* find and select the old option from previous request (if there was a previous request) */
      if (OLD_ISSUE_TYPE_INPUT.localeCompare("") != 0) {
        $('.issue-type').each(function () {
          if ($(this).val() == OLD_ISSUE_TYPE_INPUT) {
            $(this).attr('selected', true);
          }
        })
      }
    </script>

@stop