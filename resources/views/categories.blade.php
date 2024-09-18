@extends('masterLayout')

{{-- Page Title --}}
@section('page-title')
    Categories
@endsection

{{-- Page Style CSS --}}
@section('page-style')
    <style>
        
    </style>
@endsection

{{-- Page Content --}}
@section('page-content')

    {{-- Alert Section --}}
    @if (session('alert'))

            <div class="alert alert-{{session('alertType')}} alert-dismissible fade show" role="alert">
                {{session('alert')}}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        
    @endif



    {{-- Main Content --}}
    <div class="w-100 d-flex justify-content-center align-items-center flex-column">
        <div class="p-2 my-1 max-700 bg-light rounded shadow">
            <h1>Categories</h1>
            <hr><br>

            {{-- Add Category Form --}}
            <h3>Add Category</h3>
            <form action="{{ route('category.store') }}" method="POST" class="">
                @csrf
                <input type="text" name="category_name" class="form-control rounded w-100 mb-1" placeholder="Category Name...">
                <input type="number" name="category_budget" class="form-control rounded w-100" placeholder="Ctegory Budget...">
                <br>

                <button type="submit" class="btn btn-success text-light">Add</button>
            </form>

        </div>

        <div class="p-2 max-700 bg-light my-1 rounded shadow">

            {{-- DataTables class --}}
            @if ($categories_data->count() > 0)
                <?php $dataTable_class = 'dataTable' ?>
            @else
                <?php $dataTable_class = '' ?>
            @endif

            {{-- Categories Table --}}
            <table class="w-100 table table-bordered table-striped {{$dataTable_class}}">
                <thead>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Budget</th>
                    <th>Action</th>
                    <th>Published</th>
                </thead>

                <tbody>
                    @if($categories_data->count() > 0)
                        @foreach ($categories_data as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                <td>
                                    @if ($category->budget != null)
                                        {{ $category->budget }}
                                    @else
                                        No Budget 
                                    @endif
                                </td>
                                <td>
                                    {{-- Delete Button --}}
                                    <form action="{{ route('category.destroy', $category->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')

                                        <input type="submit" class="btn btn-danger" value="Delete">
                                    </form>

                                    {{-- Update Button --}}
                                    <button class="btn btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#updateCategoryModal{{ $category->id }}">Update</button>

                                </td>
                                <td>{{ $category->created_at }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5">
                                <center>No Ctegories!</center>
                            </td>
                        </tr>
                    @endif

                </tbody>
            </table>
        </div>
    </div>


    {{-- Update Category Modal --}}
    <!-- Modal -->

    @isset($categories_data)
        @foreach ($categories_data as $category)
            <div class="modal fade" id="updateCategoryModal{{ $category->id }}" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Update Category</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            {{-- Update Category Form --}}
                            <form action="{{ route('category.update', $category->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <input type="text" name="category_name" class="form-control mb-1 rounded" value="{{ $category->name }}">
                                <input type="number" name="category_budget" class="form-control mb-1 rounded" value="{{ $category->budget }}">
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