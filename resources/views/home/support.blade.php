@extends('main.main_layout')

@section('head')
    <title>Sewanee Eats | Support </title>
@stop

@section('body')
    <link rel="stylesheet" href="{{ assetUrl('css/support.css') }}">
    <div class="container support">
        <br>
        <p class="row support-h"> We are always available to listen to your concern or/and suggestion</p>
        <hr>
        <form action="{{ formUrl('createIssue') }}"
              method="post">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="fullname">Full Name</label>
                <input type="text" class="form-control" id="fullname"
                       name="name" maxlength="150" required
                       value="{{ old('name')  }}" placeholder="Name ">
            </div>
            <div class="form-group">
                <label for="email"> Your Email</label>
                <input type="text" class="form-control" id="email"
                       name="email" maxlength="150" required value="{{ old('email')  }}" placeholder="Email">
            </div>
            <div class="form-group">
                <label for="subject"> Subject </label>
                <input type="text" class="form-control" id="subject"
                       name="subject" maxlength="150" required value="{{ old('subject')  }}" placeholder="Subject">
            </div>
            <div class="form-group">
                <label for="order-id">If your concern/suggestion is about an order you purchased, please provide your
                    order confirmation number below</label>
                <input type="text" class="form-control" id="order-id"
                       name="confirmation_number" maxlength="100"
                       value="{{ old('confirmation_number')  }}" placeholder="Order #">
            </div>
            <div class="form-group">
                <label for="type-of-inquiry">Please select the type the best fits your inquiry</label>
                <select name="issue_type" class="row custom-select" id="type-of-inquiry" required>
                    <option class="issue-type" value="1" selected>Concern</option>
                    <option class="issue-type" value="2">Suggestion</option>
                </select>
            </div>
            <div class="form-group">
                <label for="inquiry-body"> Please expand your concern</label>
                <textarea name="body" maxlength="500" required class="form-control" id="inquiry-body"
                          placeholder="">{{ old('body') }}</textarea>
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