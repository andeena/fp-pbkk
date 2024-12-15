<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Order;
use Milon\Barcode\DNS1D;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // Menampilkan halaman tambah produk
    public function product()
    {
        if (Auth::check() && Auth::user()->usertype == '1') {
            return view('admin.product');
        }

        return redirect('login')->with('error', 'Access denied.');
    }

    // Mengunggah produk baru
    public function uploadproduct(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            // Simpan gambar
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);

            // Simpan produk ke database
            $product = new Product;
            $product->title = $request->title;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->quantity = $request->quantity;
            $product->image = $imageName;
            $product->save();

            // Generate barcode
            $barcodeGenerator = new DNS1D();
            $barcodeValue = (string) $product->id;
            $product->barcode = base64_encode($barcodeGenerator->getBarcodePNG($barcodeValue, 'C128'));
            $product->save();

            return redirect()->back()->with('status', 'Product uploaded successfully.');
        } catch (\Exception $e) {
            Log::error('Error uploading product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to upload product. Please try again.');
        }
    }

    // Regenerate barcode untuk produk tertentu
    public function regenerateBarcode($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        try {
            $barcodeGenerator = new DNS1D();
            $barcodeValue = (string) $product->id;

            // Generate barcode baru
            $product->barcode = base64_encode($barcodeGenerator->getBarcodePNG($barcodeValue, 'C128'));
            $product->save();

            return redirect()->back()->with('status', 'Barcode regenerated successfully.');
        } catch (\Exception $e) {
            Log::error('Error regenerating barcode: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to regenerate barcode.');
        }
    }

    // Menampilkan daftar produk
    public function showproduct()
    {
        $data = Product::paginate(10);
        return view('admin.showproduct', compact('data'));
    }

    public function updateview($id)
    {
        // Ambil data produk berdasarkan ID
        $data = Product::findOrFail($id);

        // Kembalikan view dengan membawa data produk
        return view('admin.updateview', compact('data'));
    }


    // Mengupdate produk
    public function updateproduct(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            // Update data produk
            $product->title = $request->title;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->quantity = $request->quantity;

            // Update gambar jika ada
            if ($request->hasFile('image')) {
                // Hapus gambar lama
                if (Storage::exists('images/' . $product->image)) {
                    Storage::delete('images/' . $product->image);
                }

                // Simpan gambar baru
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('images'), $imageName);
                $product->image = $imageName;
            }

            $product->save();

            return redirect()->back()->with('status', 'Product updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update product.');
        }
    }

    // Menghapus produk
    public function deleteproduct($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        try {
            // Hapus gambar
            if (Storage::exists('images/' . $product->image)) {
                Storage::delete('images/' . $product->image);
            }

            $product->delete();
            return redirect()->route('showproduct')->with('status', 'Product deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage());
            return redirect()->route('showproduct')->with('error', 'Failed to delete product.');
        }
    }

    // Menampilkan daftar pesanan
    public function showorder()
    {
        $orders = Order::paginate(10);
        return view('admin.showorder', compact('orders'));
    }

    // Update status pesanan
    public function updatestatus($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return redirect()->back()->with('error', 'Order not found.');
        }

        try {
            $order->status = 'Delivered';
            $order->save();

            return redirect()->back()->with('status', 'Order status updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating order status: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update order status.');
        }
    }
}
