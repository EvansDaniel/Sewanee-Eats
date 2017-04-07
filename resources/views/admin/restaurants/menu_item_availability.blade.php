@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>
        Viewing {{ $menu_item->name }} availability
    </title>
@stop

@section('body')
    <a href="{{ route('adminShowMenu',['id' => $menu_item->restaurant->id]) }}">
        <button class="btn btn-dark" type="button">Back to Menu</button>
    </a>
    <h3>Viewing availability for {{ $menu_item->name }}</h3>
    @foreach($day_of_week_names as $day_of_week)
        @if(count($menu_item->getResourceTimeRangesByDay($day_of_week)) != 0)
            <h4>
                Available times starting {{ $day_of_week }}</h4>
            <ul>
                @foreach($menu_item->getResourceTimeRangesByDay($day_of_week) as $time_range)
                    <li>
                        {{ $time_range->getDayDateTimeString() }} |
                        <a href="{{ route('showMenuItemUpdateAvailability',
                            ['time_range_id' => $time_range->id,
                            'menu_item_id' => $menu_item->id]) }}">
                            <button class="btn btn-dark" type="submit">Update time</button>
                        </a>
                        <form action="{{ url()->to(parse_url(route('menuItemDeleteAvailability',[]),PHP_URL_PATH),[],env('APP_ENV') !== 'local')  }}"
                              method="post" style="display: inline;">
                            {{ csrf_field() }}
                            <input name="time_range_id" type="hidden" value="{{ $time_range->id }}">
                            <input name="menu_item_id" type="hidden" value="{{ $menu_item->id }}">
                            <button class="btn btn-danger" type="submit">Delete Availability Time Range</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif
    @endforeach
@stop