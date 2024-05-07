@extends('layouts.app')
@section('title', '新規登録画面')
@section('content')


<div class="container">
    <h1 class="mb-4">商品新規登録</h1>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="product_name">商品名：</label>
            <input type="text" id="product_name" name="product_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="company_id">メーカー名：</label>
            <select id="company_id" name="company_id" class="form-control" required>
                <option value="">メーカーを選択してください</option>
                @foreach($companies as $company)
                <option value="{{ $company->id }}">{{ $company->company_name }}</option>
            @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="price">価格：</label>
            <input type="number" id="price" name="price" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="stock">在庫数：</label>
            <input type="number" id="stock" name="stock" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="comment">コメント：</label>
            <textarea id="comment" name="comment" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="image">商品画像：</label>
            <input type="file" id="image" name="image" class="form-control-file" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary">登録</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">戻る</a>
    </form>
</div>

@endsection