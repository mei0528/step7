@extends('layouts.app')
@section('title', '商品編集')

@section('content')
    <div class="container">
        <h1>商品編集</h1>
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- 商品ID -->
            <div class="form-group">
                <label for="id">ID</label>
                <input type="text" class="form-control" id="id" name="id" value="{{ $product->id }}" readonly>
            </div>

            <!-- 商品名 -->
            <div class="form-group">
                <label for="product_name">商品名</label>
                <input type="text" class="form-control" id="product_name" name="product_name" value="{{ $product->product_name }}">
            </div>

            <!-- メーカー名 -->
            <div class="form-group">
                <label for="company_id">メーカー名：</label>
                <select id="company_id" name="company_id" class="form-control" required>
                    <option value="">メーカーを選択してください</option>
                    @foreach($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                @endforeach
                </select>
            </div>

            <!-- 価格 -->
            <div class="form-group">
                <label for="price">価格</label>
                <input type="number" class="form-control" id="price" name="price" value="{{ $product->price }}">
            </div>

            <!-- 在庫数 -->
            <div class="form-group">
                <label for="stock">在庫数</label>
                <input type="number" class="form-control" id="stock" name="stock" value="{{ $product->stock }}">
            </div>

            <!-- コメント -->
            <div class="form-group">
                <label for="comment">コメント</label>
                <textarea class="form-control" id="comment" name="comment">{{ $product->comment }}</textarea>
            </div>

            <!-- 商品画像 -->
            <div class="form-group">
                <label for="image">商品画像</label>
                <input type="file" class="form-control-file" id="image" name="image">
                <img src="{{ asset($product->img_path) }}" alt="{{ $product->product_name }}" class="mt-2" style="max-width: 200px;">
            </div>

            <!-- 更新ボタン -->
            <button type="submit" class="btn btn-primary">更新</button>
            <a href="{{ route('product.show', $product->id) }}" class="btn btn-secondary">戻る</a>
        </form>
    </div>
@endsection