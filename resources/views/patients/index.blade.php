@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Список пациентов</h1>
        @if(count($patients) > 0)
            <ul class="list-group">
                @foreach($patients as $patient)
                    <li class="list-group-item">
                        Имя: {{ $patient['name'] }}<br>
                        Дата рождения: {{ $patient['birthdate'] }}<br>
                        Возраст: {{ $patient['age'] }}
                    </li>
                @endforeach
            </ul>
        @else
            <p>Пациенты не найдены.</p>
        @endif
    </div>
@endsection
