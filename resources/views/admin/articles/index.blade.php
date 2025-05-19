@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold">Gestion des articles</h1>
    <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Créer un article
    </a>
</div>

<div class="content-card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Nom</th>
                    <th>Poids moyen</th>
                    <th>Type</th>
                    <th>Classe</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $article)
                <tr>
                    <td>{{ $article->id }}</td>
                    <td>
                        <img src="{{ asset($article->image_path) }}" alt="{{ $article->name }}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                    </td>
                    <td>{{ $article->name }}</td>
                    <td>{{ $article->average_weight }} kg</td>
                    <td>{{ is_array($article->type) ? implode(', ', $article->type) : $article->type }}</td>
                    <td>
                        @switch($article->weight_class)
                            @case('leger')
                                <span class="badge bg-success">Léger</span>
                                @break
                            @case('moyen')
                                <span class="badge bg-warning">Moyen</span>
                                @break
                            @case('lourd')
                                <span class="badge bg-danger">Lourd</span>
                                @break
                            @case('variable')
                                <span class="badge bg-info">Variable</span>
                                @break
                            @default
                                <span class="badge bg-secondary">{{ $article->weight_class }}</span>
                        @endswitch
                    </td>
                    <td>
                        <div class="d-flex">
                            <a href="{{ route('admin.articles.edit', $article->id) }}" class="btn btn-sm btn-primary me-2">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <i class="bi bi-box-seam fs-2 text-primary-purple mb-3 d-block"></i>
                        <p>Aucun article dans le catalogue</p>
                        <a href="{{ route('admin.articles.create') }}" class="btn btn-primary-purple">
                            <i class="bi bi-plus-circle me-2"></i> Créer votre premier article
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection 