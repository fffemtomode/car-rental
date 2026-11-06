<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; line-height: 1.5; }
        h1 { text-align: center; font-size: 15px; margin-bottom: 2px; }
        .subtitle { text-align: center; font-size: 13px; margin-bottom: 15px; }
        .place-date { margin-bottom: 15px; }
        .clause-title { font-weight: bold; margin-top: 12px; margin-bottom: 4px; }
        p { margin: 4px 0; }
        .indent { margin-left: 15px; }
        table.signatures { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table.signatures td { width: 50%; vertical-align: top; padding: 8px; border: 1px solid #333; font-size: 11px; }
    </style>
</head>
<body>

@php
    $contractDate = now();
    $monthsGenitive = [1=>'січня',2=>'лютого',3=>'березня',4=>'квітня',5=>'травня',6=>'червня',7=>'липня',8=>'серпня',9=>'вересня',10=>'жовтня',11=>'листопада',12=>'грудня'];
    $contractDateStr = $contractDate->format('d') . ' ' . $monthsGenitive[$contractDate->month] . ' ' . $contractDate->format('Y');
@endphp

<h1>ДОГОВІР КУПІВЛІ-ПРОДАЖУ</h1>
<p class="subtitle">транспортного засобу</p>

<p class="place-date">м. Хмельницький «{{ $contractDateStr }}»р.</p>

<p><strong>Продавець: ФОП Шиманський Владислав Борисович,</strong> який діє на підставі Виписки №2010350000000677902 від 31.10.2024 року з однієї сторони, та</p>
<p><strong>Покупець: {{ $deal->user->name }}</strong> з другої сторони, уклали цей Договір про наступне.</p>

<p class="clause-title">1. Предмет договору</p>
<p class="indent">1.1. Продавець передає у власність, а Покупець приймає та оплачує транспортний засіб марки <strong>{{ strtoupper($car->brand) }} {{ strtoupper($car->model) }}</strong>, <strong>{{ $car->year }}</strong> року випуску, <strong>{{ $car->color ?? '____________' }}</strong> кольору.</p>
<p class="indent">1.2. Державний номерний знак: <strong>{{ $car->plate_number ?? '____________' }}</strong>, номер кузова: <strong>{{ $car->vin ?? '____________' }}</strong>.</p>
<p class="indent">1.3. Загальний пробіг автомобіля на момент продажу становить {{ $car->mileage ? number_format($car->mileage, 0, '', ' ') . ' км' : '____________' }}.</p>

<p class="clause-title">2. Вартість та порядок розрахунків</p>
<p class="indent">2.1. Вартість транспортного засобу становить <strong>{{ number_format($deal->total_price, 0, '', ' ') }}</strong> гривень.</p>
<p class="indent">2.2. Оплата здійснюється одноразово в повному обсязі протягом трьох банківських днів з моменту підписання цього договору.</p>

<p class="clause-title">3. Права та обов'язки сторін</p>
<p class="indent">3.1. Продавець зобов'язується передати транспортний засіб у технічно справному стані разом із комплектом документів, необхідних для перереєстрації на нового власника.</p>
<p class="indent">3.2. Покупець зобов'язується самостійно здійснити перереєстрацію транспортного засобу у відповідних державних органах протягом строку, встановленого чинним законодавством України.</p>

<p class="clause-title">4. Відповідальність сторін</p>
<p class="indent">4.1. За невиконання або неналежне виконання умов цього договору сторони несуть відповідальність згідно з чинним законодавством України.</p>

<p class="clause-title">5. Прикінцеві положення</p>
<p class="indent">5.1. Договір набуває чинності з моменту його підписання обома сторонами та діє до повного виконання сторонами своїх зобов'язань.</p>
<p class="indent">5.2. Договір укладено у двох примірниках, що мають однакову юридичну силу, по одному для кожної зі сторін.</p>

<table class="signatures">
    <tr>
        <td>
            <strong>Продавець</strong><br>
            ФОП ШИМАНСЬКИЙ ВЛАДИСЛАВ БОРИСОВИЧ<br>
            РНОКПП: 3393813338<br>
            Адреса: м. Хмельницький провулок Дубівський, 9<br>
            Тел: 067-595-691-5<br><br>
            ____________________ /В.Б. Шиманський/
        </td>
        <td>
            <strong>Покупець</strong><br>
            ПІБ: {{ $deal->user->name }}<br>
            Тел: {{ $deal->user->phone ?? '____________________' }}<br><br>
            ____________________ /{{ $deal->user->name }}/
        </td>
    </tr>
</table>

</body>
</html>
