
@extends('layouts.app')
@section('title', '詳細場面')
@section('content')
    <div class="container">
        <h1>商品詳細</h1>
        <p>ID: {{ $product->id }}</p>
        <p>商品画像: <img src="{{ asset($product->img_path) }}" alt="{{ $product->product_name }}"></p>
        <p>商品名: {{ $product->product_name }}</p>
        <p>メーカー名: {{ $product->company->company_name}}</p>
        <p>価格: {{ $product->price }}</p>
        <p>在庫数: {{ $product->stock }}</p>
        <p>コメント: {{ $product->comment }}</p>
        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">編集</a>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">戻る</a>
    </div>  
@endsection
