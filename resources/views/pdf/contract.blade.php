<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { text-align: center; font-size: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        td { padding: 4px 0; }
        .label { font-weight: bold; width: 200px; }
    </style>
</head>
<body>
<h1>ДОГОВІР № {{ $deal->id }}</h1>
<p>Дата укладання: {{ now()->format('d.m.Y') }}</p>

<table>
    <tr><td class="label">Клієнт:</td><td>{{ $deal->user->name }}</td></tr>
    <tr><td class="label">Email:</td><td>{{ $deal->user->email }}</td></tr>
    <tr><td class="label">Автомобіль:</td><td>{{ $deal->car->brand }} {{ $deal->car->model }} ({{ $deal->car->year }})</td></tr>
    <tr><td class="label">Тип угоди:</td><td>{{ $deal->type }}</td></tr>
    @if ($deal->start_date)
        <tr><td class="label">Період:</td><td>{{ $deal->start_date }} — {{ $deal->end_date }}</td></tr>
    @endif
    @if ($deal->leasing_months)
        <tr><td class="label">Строк лізингу:</td><td>{{ $deal->leasing_months }} міс.</td></tr>
    @endif
    <tr><td class="label">Вартість:</td><td>{{ $deal->total_price }} грн</td></tr>
</table>

<p style="margin-top: 40px;">Підписи сторін:</p>
<table>
    <tr>
        <td>Клієнт: ______________________</td>
        <td>Компанія: ______________________</td>
    </tr>
</table>
</body>
</html>
