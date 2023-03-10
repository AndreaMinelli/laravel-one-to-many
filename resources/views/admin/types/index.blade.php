@extends('layouts.app')

@section('title', 'Types')
@section('cdns')
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css'
        integrity='sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=='
        crossorigin='anonymous' />
@endsection

@section('content')
    <h1 class="text-center my-4">Tipologia progetti</h1>
    <div class="d-flex align-items-center justify-content-end">
        <button type="button" id="show-add-form" class="btn btn-success" style='display:{{ old('name') ? 'none' : 'block' }}'>
            <i class="fas fa-plus me-2"></i>Aggiungi tipologia
        </button>
        <form action="{{ route('admin.types.store') }}" method="POST" style='display:{{ old('name') ? 'block' : 'none' }}'
            id="add-form" class="align-items-end">
            @csrf
            <div>
                <label for="name" class="form-label">Nome tipologia:</label>
                <input type="text" class="form-control me-2 @error('name') is-invalid @enderror" id="name"
                    name="name" placeholder="Inserisci nome tipologia" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <button class="btn btn-success ms-3">Salva</button>
        </form>
    </div>
    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Nome</th>
                <th scope="col">Ultimo Agg.</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($types as $type)
                <tr>
                    <th scope="row">{{ $type->id }}</th>
                    <td>{{ $type->name }}</td>
                    <td>{{ $type->updated_at }}</td>
                    <td class="d-flex justify-content-end">
                        <button type="button" class="btn btn-warning btn-sm text-white me-3"><i
                                class="fa-solid fa-pencil"></i></button>
                        <form action="{{ route('admin.types.destroy', $type->id) }}" class="delete-form" method="POST">
                            @method('DELETE')
                            @csrf
                            <button class="btn btn-danger btn-sm"><i class="fa-solid fa-trash-can"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" scope="row" class="text-center">
                        Non ci sono tipologie da visualizzare
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class='d-flex justify-content-center my-5 '>
        {{ $types->links() }}
    </div>
@endsection

@section('scripts')
    <script>
        const showAddForm = document.getElementById('show-add-form');
        const addForm = document.getElementById('add-form');
        showAddForm.addEventListener('click', () => {
            showAddForm.style.display = 'none';
            addForm.style.display = 'flex';
        });
    </script>
@endsection
