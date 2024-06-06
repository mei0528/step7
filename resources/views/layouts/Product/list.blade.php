@extends('layouts.app')
@section('title', '商品一覧画面')
@section('content')

<div class="container">
    <h1 class="mb-4">商品情報一覧</h1>
    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">新規登録</a>

    <div class="products mt-5">
<form action="{{ route('products.index') }}" method="GET">
    <input type="text" name="keyword" placeholder="商品名を入力">
    <select name="company_id">
        <option value="">メーカー名</option>
        @foreach ($companies as $company)
        <option value="{{ $company->id }}" @if($companyId == $company->id) selected @endif>{{ $company->company_name }}</option>
    @endforeach
    </select>

    <br>
    <label>価格範囲:</label>
    <input type="number" name="price_min" placeholder="最低価格"> 〜
    <input type="number" name="price_max" placeholder="最高価格">
    <br>
    <label>在庫数範囲:</label>
    <input type="number" name="stock_min" placeholder="最低在庫数"> 〜
    <input type="number" name="stock_max" placeholder="最高在庫数">
    <br>
    <button type="submit">検索</button>
</form>

    <div class="products mt-5">
        <table id="product-table" class="table table-striped tablesorter">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>商品画像</th>
                    <th>商品名</th>
                    <th>価格</th>
                    <th>在庫数</th>
                    <th>メーカー名</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($products as $product)
            <tr>
                    <td>{{ $product->id }}</td>
                    <td><img src="{{ asset($product->img_path) }}" alt="商品画像" width="100"></td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->price }}</td> 
                    <td>{{ $product->stock }}</td>
                    <td>
                        @if ($product->company)
                            {{ $product->company->company_name }}
                        @else
                            No Company
                        @endif
                    <td>{{ $product->comment }}</td>
                    </td>
                    <td>
                        <a href="{{ route('product.show', $product->id) }}" class="btn btn-primary btn-sm mx-1">詳細</a>
                        <form action="{{ route('products.delete', $product->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger btn-sm mx-1 delete-btn" data-product-id="{{ $product->id }}">削除</button>                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    
</div>

<script>
$(document).ready(function() {
    $("#product-table").tablesorter({
        sortList: [[0, 1]] 
    });
    
    $(document).on('click', '.delete-btn', function() {
        console.log('削除ボタンがクリックされました'); 

    var deleteConfirm = confirm('削除してよろしいでしょうか？');

    if(deleteConfirm == true) {
      var clickEle = $(this);
     var productID = clickEle.data('product-id');
     console.log('削除する商品ID:', productID); 

      $.ajax({
        url: '/product/' + productID,
        type: 'POST',
        data: {
          'id': productID,
          '_method': 'DELETE',
          '_token': $('meta[name="csrf-token"]').attr('content')
        }
      })
      .done(function() {
        console.log('削除リクエストが成功しました'); 
        clickEle.parents('tr').remove();
        $("#product-table").trigger("update");
      })
      .fail(function() {
        console.log('削除リクエストが失敗しました'); 
        alert('エラー');
      });
    } else {
      (function(e) {
        e.preventDefault();
      });
    }
 });
});

</script>
@endsection
