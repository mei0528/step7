<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Sale;
use App\Models\Product;

class PurchaseController extends Controller
{
    public function purchase(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $productId = $validated['product_id'];
        $quantity = $validated['quantity'];

        // トランザクション開始
        DB::beginTransaction();
        try {
            $product = Product::findOrFail($productId);

            // 在庫チェック
            if ($product->stock < $quantity) {
                return response()->json(['error' => '在庫が不足しています'], 400);
            }

            // salesテーブルにレコードを追加
            Sale::create([
                'product_id' => $productId,
                'quantity' => $quantity,
                'total_price' => $product->price * $quantity,
            ]);

            // productsテーブルの在庫数を減算
            $product->stock -= $quantity;
            $product->save();

            // トランザクションコミット
            DB::commit();

            return response()->json(['success' => '購入が完了しました'], 200);

        } catch (\Exception $e) {
            // トランザクションロールバック
            DB::rollBack();
            return response()->json(['error' => '購入処理中にエラーが発生しました'], 500);
        }
    }
}
