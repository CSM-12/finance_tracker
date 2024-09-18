@extends('masterLayout')

{{-- Page Title --}}
@section('page-title')
    Savings
@endsection

{{-- Page Style --}}
@section('page-style')
    <style>

    </style>
@endsection

{{-- Page Content --}}
@section('page-content')

    {{-- Alert Section --}}
    @if (session('alert'))
        <div class="alert alert-{{ session('alertType') }} alert-dismissible fade show" role="alert">
            {{ session('alert') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


    <div class="w-100 d-flex justify-content-center align-items-center flex-column">

        <div class="p-2 my-1 w-100 bg-light rounded shadow">
            <h1>Savings</h1>
            <hr>

            {{-- Transaction Form --}}
            <h3>Add Saving Goal</h3>

            <form action="{{ route('savings.store') }}" method="POST">
                @csrf

                <input type="text" name="saving_title" class="form-control mb-2" placeholder="Saving Title">

                <textarea name="saving_description" class="form-control mb-2" placeholder="Saving Description..."></textarea>

                <input type="number" name="saving_amount" class="form-control mb-2" placeholder="Target Saving Amount">

                <input type="date" name="saving_due_date" class="form-control mb-2">

                <select class="form-select mb-2" name="saving_category">
                    @foreach ($categories_data as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>

                <input type="submit" class="btn btn-primary">
            </form>
        </div>


        {{-- All Savings --}}
        <div class="p-2 my-1 w-100 bg-light rounded shadow">

            {{-- DataTables class --}}
            @if ($savings_data->count() > 0)
                <?php $dataTable_class = 'dataTable' ?>
            @else
                <?php $dataTable_class = '' ?>
            @endif
            
            {{-- Savings Table --}}
            <table class="w-100 table table-bordered rounded {{$dataTable_class}}">
                <thead>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Target Amount</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Due Date</th>
                    <th>Actions</th>
                </thead>

                <tbody>
                    @if ($savings_data->count() > 0)
                        @foreach ($savings_data as $saving)
                            <tr>
                                <td rowspan="2">{{ $saving->id }}</td>
                                <td>{{ $saving->title }}</td>
                                <td>
                                    Rs. {{ $saving->amount }}
                                </td>
                                <td>{{ $saving->description }}</td>
                                <td>{{ $saving->category->name }}</td>
                                <td>{{ $saving->due_date }}</td>

                                {{-- Delete & Upadte --}}
                                <td>

                                    {{-- Delete Button --}}
                                    <form action="{{ route('savings.destroy', $saving->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')

                                        <input type="submit" class="btn btn-danger" value="Delete">
                                    </form>

                                    {{-- Update Button --}}
                                    <button class="btn btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#updateSavingModal{{ $saving->id }}">Update</button>

                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">
                                <center>No Saving Goals Yet!</center>
                            </td>
                        </tr>
                    @endif
                </tbody>

            </table>
        </div>
    </div>



    {{-- Update Transaction Modal --}}
    <!-- Modal -->

    @isset($savings_data)
        @foreach ($savings_data as $saving)
            <div class="modal fade" id="updateSavingModal{{ $saving->id }}" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Update Saving Goal</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            {{-- Update Transaction Form --}}
                            <form action="{{ route('savings.update', $saving->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <input type="text" name="saving_title" class="form-control mb-2" placeholder="Saving Title"
                                    value="{{ $saving->title }}">

                                <textarea name="saving_description" class="form-control mb-2" placeholder="Saving Description...">{{ $saving->description }}</textarea>

                                <input type="number" name="saving_amount" class="form-control mb-2"
                                    placeholder="Target Saving Amount" value="{{ $saving->amount }}">

                                <input type="date" name="saving_due_date" class="form-control mb-2"
                                    value="{{ $saving->due_date }}">

                                <select class="form-select mb-2" name="saving_category" value="{{ $saving->category->name }}">
                                    @foreach ($categories_data as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>

                                <br>

                                <input type="submit" class="btn btn-success">
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endisset


@endsection
