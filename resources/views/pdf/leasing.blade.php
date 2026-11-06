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
        table.schedule { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.schedule th, table.schedule td { border: 1px solid #333; padding: 4px 6px; font-size: 11px; text-align: left; }
    </style>
</head>
<body>

@php
    $contractDate = now();
    $monthsGenitive = [1=>'січня',2=>'лютого',3=>'березня',4=>'квітня',5=>'травня',6=>'червня',7=>'липня',8=>'серпня',9=>'вересня',10=>'жовтня',11=>'листопада',12=>'грудня'];
    $contractDateStr = $contractDate->format('d') . ' ' . $monthsGenitive[$contractDate->month] . ' ' . $contractDate->format('Y');
    $monthlyPayment = $deal->leasing_months ? round($deal->total_price / $deal->leasing_months, 2) : null;
@endphp

<h1>ДОГОВІР ФІНАНСОВОГО ЛІЗИНГУ</h1>
<p class="subtitle">транспортного засобу</p>

<p class="place-date">м. Хмельницький «{{ $contractDateStr }}»р.</p>

<p><strong>Лізингодавець: ФОП Шиманський Владислав Борисович,</strong> який діє на підставі Виписки №2010350000000677902 від 31.10.2024 року з однієї сторони, та</p>
<p><strong>Лізингоодержувач: {{ $deal->user->name }}</strong> з другої сторони, уклали цей Договір про наступне.</p>

<p class="clause-title">1. Предмет договору</p>
<p class="indent">1.1. Лізингодавець передає Лізингоодержувачу в користування на умовах фінансового лізингу транспортний засіб марки <strong>{{ strtoupper($car->brand) }} {{ strtoupper($car->model) }}</strong>, <strong>{{ $car->year }}</strong> року випуску, <strong>{{ $car->color ?? '____________' }}</strong> кольору.</p>
<p class="indent">1.2. Державний номерний знак: <strong>{{ $car->plate_number ?? '____________' }}</strong>, номер кузова: <strong>{{ $car->vin ?? '____________' }}</strong>.</p>

<p class="clause-title">2. Строк та вартість лізингу</p>
<p class="indent">2.1. Строк лізингу становить <strong>{{ $deal->leasing_months }}</strong> місяців.</p>
<p class="indent">2.2. Загальна вартість договору лізингу становить <strong>{{ number_format($deal->total_price, 0, '', ' ') }}</strong> гривень.</p>
<p class="indent">2.3. Щомісячний лізинговий платіж становить <strong>{{ number_format($monthlyPayment, 0, '', ' ') }}</strong> гривень та сплачується не пізніше п'ятого числа кожного місяця згідно з графіком, наведеним нижче.</p>

@if ($deal->leasingSchedules->count())
    <table class="schedule">
        <tr><th>Дата платежу</th><th>Сума, грн</th></tr>
        @foreach ($deal->leasingSchedules as $schedule)
            <tr>
                <td>{{ \Carbon\Carbon::parse($schedule->payment_date)->format('d.m.Y') }}</td>
                <td>{{ number_format($schedule->amount, 0, '', ' ') }}</td>
            </tr>
        @endforeach
    </table>
@endif

<p class="clause-title">3. Права та обов'язки сторін</p>
<p class="indent">3.1. Лізингоодержувач зобов'язується своєчасно сплачувати лізингові платежі, підтримувати транспортний засіб у справному стані та не передавати його третім особам без письмової згоди Лізингодавця.</p>
<p class="indent">3.2. Лізингодавець зобов'язується забезпечити технічну справність транспортного засобу на момент передачі.</p>

<p class="clause-title">4. Відповідальність сторін</p>
<p class="indent">4.1. За прострочення сплати лізингового платежу нараховується пеня у розмірі 0.5% від простроченої суми за кожен день прострочення.</p>

<p class="clause-title">5. Прикінцеві положення</p>
<p class="indent">5.1. Договір набуває чинності з моменту підписання і діє до повного виконання сторонами своїх зобов'язань.</p>

<table class="signatures">
    <tr>
        <td>
            <strong>Лізингодавець</strong><br>
            ФОП ШИМАНСЬКИЙ ВЛАДИСЛАВ БОРИСОВИЧ<br>
            РНОКПП: 3393813338<br>
            Адреса: м. Хмельницький провулок Дубівський, 9<br>
            Тел: 067-595-691-5<br><br>
            ____________________ /В.Б. Шиманський/
        </td>
        <td>
            <strong>Лізингоодержувач</strong><br>
            ПІБ: {{ $deal->user->name }}<br>
            Тел: {{ $deal->user->phone ?? '____________________' }}<br><br>
            ____________________ /{{ $deal->user->name }}/
        </td>
    </tr>
</table>

</body>
</html>
