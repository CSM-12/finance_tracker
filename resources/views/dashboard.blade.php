@extends('masterLayout')

{{-- Page Title --}}
@section('page-title')
    Dashboard
@endsection

{{-- Page Style CSS --}}
@section('page-style')
    <style>

    </style>
@endsection


{{-- Page Content --}}
@section('page-content')
    <div class="w-100 d-flex justify-content-between align-items-center">
        <h1>Dashoard{{Auth::check()}}</h1>
        <h1>
            <a href="{{route('logout')}}"><i class="bi bi-box-arrow-right"></i></a>
        </h1>
    </div>

    
    <hr>

    @isset($account_balance)
        <?php
        if ($account_balance > 0) {
            $balance_div_class = 'success';
        } else {
            $balance_div_class = 'danger';
        }
        ?>

        {{-- Account Balance --}}
        <div class="px-2 py-3 bg-{{ $balance_div_class }} rounded col-auto text-white fw-bold">
            <i class="bi bi-wallet"></i> Your Balance : Rs.
            {{ $account_balance }}

            <?php
            if ($account_balance < 0) {
            ?>
            <br>
            <i class="bi bi-exclamation-circle-fill"></i> Looks like your expences are more that your income!
            <?php
            }
            ?>
        </div>
    @endisset

    <br><br>
    <hr>

    <div>
        <h3>Latest Transactions</h3>


        {{-- Transactions Table --}}
        <table class="w-100 table table-bordered rounded">

            <tbody>
                @if ($latest_transactions_data->count() > 0)
                    @foreach ($latest_transactions_data as $transaction)
                        {{-- Show Transaction Color --}}
                        @if ($transaction->type == 'out')
                            @php
                                $tableClass = 'table-danger';
                            @endphp
                        @else
                            @php
                                $tableClass = 'table-success';
                            @endphp
                        @endif
                        <tr class="{{ $tableClass }}">
                            <td>{{ $transaction->id }}</td>
                            <td>{{ $transaction->title }}</td>
                            <td>
                                Rs. {{ $transaction->amount }}
                            </td>
                            <td>{{ $transaction->category->name }}</td>


                            <td>{{ $transaction->updated_at }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6">
                            <center>No Transactions Yet!</center>
                        </td>
                    </tr>
                @endif
            </tbody>

        </table>

    </div>

    <hr>

    <div>
        <h3>Insights</h3>
        <span class="d-block" style="font-size: 24px; color:#888888; font-weight:500;">Monthly Budget Per Category</span>
        <div id="budget_gauge" style="width: 400px; height: 120px;"></div>
        
        {{-- budget alert --}}
        <div id="budget_alert">
            <ul style="color:red;">
                @isset($budgets)
                    @foreach ($budgets as $budget)
                        @if ($budget->percentage >= 100)
                            <li> You have exceeded budget for <b>{{$budget->category}}</b> this month.</li>
                        @endif
                    @endforeach
                @endisset
            </ul>
        </div>

        <hr>
        <div id="calendar_basic" style="width: 1000px; height: 200px;"></div>
        <hr>
        <div id="donutchart" style="width:100%; max-width:400px; height: 400px; padding:0px;"></div>
        <div id="incomeSource" style="width:100%; max-width:400px; height: 400px; padding:0px;"></div>
        <hr>
        <div id="incomeVsExpence" style="width: 800px; height: 500px;"></div>
    </div>
@endsection

@push('page-script')
    {{-- Calendar Script --}}
    <script type="text/javascript">
        google.charts.load("current", {
            packages: ["calendar", "corechart", "bar", "gauge"]
        });
        google.charts.setOnLoadCallback(calendarChart);
        google.charts.setOnLoadCallback(categoryExpencesChart);
        google.charts.setOnLoadCallback(categoryIncomeChart);
        google.charts.setOnLoadCallback(incomeVsExpence);
        google.charts.setOnLoadCallback(budgetGauge);


        // Calendar Chart    
        function calendarChart() {
            var dataTable = new google.visualization.DataTable();
            dataTable.addColumn({
                type: 'date',
                id: 'Date'
            });
            dataTable.addColumn({
                type: 'number',
                id: 'Won/Loss'
            });
            dataTable.addRows([
                
                @foreach ($dailySums as $dailySum)
                    [new Date({{ $dailySum->year }}, {{ $dailySum->month }}, {{ $dailySum->day }}),
                        {{ $dailySum->total_amount }}
                    ],
                @endforeach
                
            ]);

            var chart = new google.visualization.Calendar(document.getElementById('calendar_basic'));

            var options = {
                title: "Yearly expences vs income",
                titleTextStyle: {
                    color: '#888888',
                    fontSize: 24
                },
                colorAxis: {
                    colors: ['red', 'white', 'green'],
                    values: [{{ $minDailySum }}, 0, {{ $maxDailySum }}]
                }
            };

            chart.draw(dataTable, options);
        }
        

        // Categories Expences Pie Chart
        function categoryExpencesChart() {
            var data = google.visualization.arrayToDataTable([
                ['Category', 'Expences'],
                @foreach ($category_expences as $category_expence)
                    ['{{ $category_expence->category }}', {{ $category_expence->total_amount }}],
                @endforeach
            ]);

            var options = {
                title: 'Expences Per Category',
                pieHole: 0.5,
                width: 450,
                height: 300,
                titleTextStyle: {
                    color: '#888888',
                    fontSize: 24
                },
                backgroundColor: {
                    fill: 'transparent'
                }
            };

            var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
            chart.draw(data, options);
        }

        // Income Source
        function categoryIncomeChart() {
            var data = google.visualization.arrayToDataTable([
                ['Category', 'Income'],
                @foreach ($category_incomes as $category_income)
                    ['{{ $category_income->category }}', {{ $category_income->total_amount }}],
                @endforeach
            ]);

            var options = {
                title: 'Income Source',
                pieHole: 0.5,
                width: 450,
                height: 300,
                titleTextStyle: {
                    color: '#888888',
                    fontSize: 24
                },
                backgroundColor: {
                    fill: 'transparent'
                }
            };

            var chart = new google.visualization.PieChart(document.getElementById('incomeSource'));
            chart.draw(data, options);
        }

        // Income VS Expences Bar graph
        function incomeVsExpence() {
            var data = google.visualization.arrayToDataTable([
                ['Month', 'Income', 'Expence'],
                @foreach ($monthlyIEs as $monthlyIE)
                    [getMonthName({{ $monthlyIE->month }}), {{ $monthlyIE->income }}, {{ $monthlyIE->expence }}],
                @endforeach
            ]);

            var options = {
                chart: {
                    title: 'Monthly Income Vs Expences',
                },
                backgroundColor: {
                    fill: 'transparent'
                },
                titleTextStyle: {
                    color: '#888888',
                    fontSize: 24
                }
            };

            var chart = new google.charts.Bar(document.getElementById('incomeVsExpence'));

            chart.draw(data, google.charts.Bar.convertOptions(options));
        }

        // Budget Guage
        function budgetGauge() {

            var data = google.visualization.arrayToDataTable([
                ['Label', 'Value'],
                @foreach ($budgets as $budget)
                    ['{{ $budget->category }}', {{ $budget->percentage }}],
                @endforeach
            ]);

            var options = {
                width: 400,
                height: 120,
                redFrom: 90,
                redTo: 100,
                yellowFrom: 75,
                yellowTo: 90,
                minorTicks: 5
            };

            var chart = new google.visualization.Gauge(document.getElementById('budget_gauge'));

            chart.draw(data, options);
        }
    </script>
@endpush
