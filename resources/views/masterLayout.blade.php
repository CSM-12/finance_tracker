<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{-- Bootstrap CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" />

    <title>@yield('page-title')</title>

    {{-- Master layout CSS Style --}}
    <style>
        body {
            background-color: #E0FBE2;
        }

        nav {
            height: 60px;
            background-color: #06D001;
            position: fixed;
            top: 0px;
            z-index: 1000;
        }

        #navbar_placeholder {
            height: 60px;
        }

        #brand {
            color: white;
        }

        /* Container */
        .max-700 {
            width: 100%;
            max-width: 800px;
        }

        #sidebar_menu {
            width: 25%;
            max-width: 300px;
        }

        .overflow-container {
            overflow-x: scroll;
        }

        #menu_bar_placeholder {
            width: 25%;
            max-width: 300px;
        }
    </style>

    @yield('page-style')

</head>

<body>

    {{-- Navbar --}}
    <nav class="w-100 d-flex px-2 justify-content-between align-items-center">
        <h1 id="brand">Personel Finance Tracker</h1>
    </nav>

    <div id="navbar_placeholder" class="w-100"></div>

    <div class="w-100 d-flex justify-content-start">

        {{-- Menu Side-Bar --}}
        <div class="d-none d-md-flex bg-light m-2 me-1 p-2 shadow rounded flex-column" id="sidebar_menu">

            {{-- Dashboard --}}
            <a class="text-decoration-none w-100" href="{{ route('home') }}">
                <div class="bg-info p-2 py-3 rounded d-flex align-items-center w-100 mb-2 text-white">
                    <b style="font-size: 1.5rem;"><i class="bi bi-bar-chart-line-fill"></i> Dashboard</b>
                </div>
            </a>

            {{-- Transactions --}}
            <a class="text-decoration-none w-100" href="{{ route('transaction.index') }}">
                <div class="bg-secondary p-2 py-3 rounded d-flex align-items-center w-100 mb-2 text-white">
                    <b style="font-size: 1.5rem;"><i class="bi bi-arrow-down-up"></i> Transactions</b>
                </div>
            </a>

            {{-- Categories --}}
            <a class="text-decoration-none w-100" href="{{ route('category.index') }}">
                <div class="bg-warning p-2 py-3 rounded d-flex align-items-center w-100 mb-2 text-white">
                    <b style="font-size: 1.5rem;"><i class="bi bi-bookmark-fill"></i> Categories</b>
                </div>
            </a>

            {{-- Savings --}}
            <a class="text-decoration-none w-100" href="{{ route('savings.index') }}">
                <div class="bg-success p-2 py-3 rounded d-flex align-items-center w-100 mb-2 text-white">
                    <b style="font-size: 1.5rem;"><i class="bi bi-piggy-bank-fill"></i> Savings</b>
                </div>
            </a>
        </div>

        {{-- Content --}}
        <div class="flex-fill bg-light m-2 ms-1 p-2 shadow rounded">
            @yield('page-content')
        </div>
    </div>

</body>

{{-- JQuery --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

{{-- Bootstrap Script CDN --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>

{{-- Google Charts Script CDN --}}
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>


{{-- Javascript --}}
<script>
    function getMonthName(monthNumber) {
        const monthNames = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];

        if (monthNumber < 1 || monthNumber > 12) {
            throw new Error("Month number must be between 1 and 12");
        }

        return monthNames[monthNumber - 1];
    }
</Script>

{{-- DataTables CDN Script --}}
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>

<script>

    let table = new DataTable('.dataTable', {
        order: [] // Disable initial sorting
    });

</Script>

{{-- Page Scripts --}}
@stack('page-script')


</html>
