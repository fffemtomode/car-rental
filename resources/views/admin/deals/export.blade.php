<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        table { border-collapse: collapse; font-family: Arial, sans-serif; font-size: 12px; }
        th { background: #2563eb; color: #ffffff; font-weight: bold; padding: 8px; border: 1px solid #1e3a8a; text-align: left; }
        td { padding: 6px 8px; border: 1px solid #d1d5db; }
        tr:nth-child(even) td { background: #f3f4f6; }
        .title { font-size: 16px; font-weight: bold; padding: 8px 0; }
        .total td { font-weight: bold; background: #dcfce7; }
    </style>
</head>
<body>
<div class="title">Звіт по заявках CarRental — {{ now()->format('d.m.Y H:i') }}</div>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Дата</th>
        <th>Клієнт</th>
        <th>Телефон</th>
        <th>Авто</th>
        <th>Тип</th>
        <th>Статус</th>
        <th>Вартість (грн)</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($deals as $deal)
        <tr>
            <td>{{ $deal->id }}</td>
            <td>{{ $deal->created_at->format('d.m.Y H:i') }}</td>
            <td>{{ $deal->user->name }}</td>
            <td>{{ $deal->user->phone }}</td>
            <td>{{ $deal->car->brand }} {{ $deal->car->model }}</td>
            <td>{{ $deal->type_label }}</td>
            <td>{{ $deal->status_label }}</td>
            <td>{{ $deal->total_price }}</td>
        </tr>
    @endforeach
    <tr class="total">
        <td colspan="7">Разом (підтверджені/завершені)</td>
        <td>{{ number_format($totalValue, 0, '', ' ') }}</td>
    </tr>
    </tbody>
</table>
</body>
</html>
