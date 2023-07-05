@extends('base')

@section('form')
    <div>
        <button id="setLeft">-></button>
        <input type="number" id="leftValue" name="leftValue">
    </div>
    <br>
    <div>
        <button id="setOperation">-></button>
        <select id="operation" name="operation">
            <option value="+"> + </option>
            <option value="-"> - </option>
            <option value="*"> * </option>
            <option value="/"> / </option>
        </select>
    </div>
    <br>
    <div>
        <button id="setRight">-></button>
        <input type="number" id="rightValue" name="rightValue">
    </div>
    <br>
    <div>
        <button id="getResult"> = </button>
        <input type="number" name="result" id="result" readonly>
    </div>
    <br>
    <form method="get" action="calculation/history" target="_blank">
        <button>История</button>
        <input type="number" name="page" min="1" step="1" value="1">
    </form>
    <div id="clearHist">
        <button>Очистить историю</button>
    </div>

    <script>
        document.getElementById('setLeft').onclick = async function () {
            let input = document.getElementById('leftValue')
            let response = await fetch('/api/calculation/leftValue', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8'
                },
                body: JSON.stringify({'leftValue': input.value ?? null})
            });
            let json = await response.json()
            if (response.ok) {
                input.value = json['leftValue']
            } else if (response.status === 400) {
                alert(json.status);
            } else {
                alert("Ошибка HTTP: " + response.status);
            }
        }

        document.getElementById('setRight').onclick = async function () {
            let input = document.getElementById('rightValue')
            let response = await fetch('/api/calculation/rightValue', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8'
                },
                body: JSON.stringify({'rightValue': input.value})
            });
            let json = await response.json()
            if (response.ok) {
                input.value = json['rightValue']
            } else if (response.status === 400) {
                alert(json.status);
            } else {
                alert("Ошибка HTTP: " + response.status);
            }
        }

        document.getElementById('setOperation').onclick = async function () {
            let input = document.getElementById('operation')
            let response = await fetch('/api/calculation/operation', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8'
                },
                body: JSON.stringify({'operation': input.value})
            });
            let json = await response.json()
            if (response.ok) {
                input.value = json['operation']
                document.getElementById('result').value = json['result']
            } else if (response.status === 400) {
                alert(json.status);
            } else {
                alert("Ошибка HTTP: " + response.status);
            }
        }

        document.getElementById('getResult').onclick = async function () {
            let response = await fetch('/api/calculation/getResult', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8'
                },
            });
            let json = await response.json()
            if (response.ok) {
                document.getElementById('result').value = json['result']
            } else if (response.status === 400) {
                alert(json.status);
            } else {
                alert("Ошибка HTTP: " + response.status);
            }
        }

        document.getElementById('clearHist').onclick = async function () {
            let response = await fetch('/api/calculation/history', {
                method: 'DELETE'
            });
            if (response.ok) {
            } else if (response.status === 400) {
                alert(json.status);
            } else {
                alert("Ошибка HTTP: " + response.status);
            }
        }
    </script>
@stop

@section('content')
    @yield('form')
@stop
