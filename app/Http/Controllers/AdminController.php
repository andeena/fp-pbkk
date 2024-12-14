<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Order;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;

class AdminController extends Controller
{
    // Menampilkan halaman tambah produk
    public function product()
    {
        if(Auth::id())
        {
            if(Auth::user()->usertype == '1')
            {
                return view('admin.product');
            }

            else{
                return redirect()->back();
            }
        }

        else{
            return redirect('login');
        }

    }

    public function uploadproduct(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Menyimpan gambar dengan nama unik
        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('images'), $imageName);

        // Menyimpan data produk baru ke database
        $product = new Product;
        $product->title = $request->title;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->image = $imageName;

        // Debugging: Tambahkan pengecekan
        try {
            $barcode = DNS1D::getBarcodePNG($request->title, 'C128');
            
            if ($barcode === false) {
                \Log::error('Barcode generation failed');
                return redirect()->back()->with('error', 'Barcode generation failed');
            }

            $product->barcode = base64_encode($barcode);
        } catch (\Exception $e) {
            \Log::error('Barcode generation error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Barcode generation error: ' . $e->getMessage());
        }

        $product->save();

        return redirect()->back()->with('status', 'Product uploaded successfully');
    }

    // Menampilkan daftar produk
    public function showproduct()
    {
        $data = Product::all();
        return view('admin.showproduct', compact('data'));
    }

    // Menampilkan halaman untuk mengedit produk
    public function updateview($id)
    {
        $data = Product::find($id);
        return view('admin.updateview', compact('data'));
    }

    // Mengupdate produk
    public function updateproduct(Request $request, $id)
    {
        $data = Product::find($id);

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Mengupdate data produk
        $data->title = $request->title;
        $data->description = $request->description;
        $data->price = $request->price;
        $data->quantity = $request->quantity;

        // Jika ada gambar baru yang diupload, hapus gambar lama dan simpan gambar baru
        if ($request->hasFile('image')) {
            // Menghapus gambar lama jika ada
            if (file_exists(public_path('images/' . $data->image))) {
                unlink(public_path('images/' . $data->image));
            }

            // Menyimpan gambar baru
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $data->image = $imageName;
        }

        $data->save();

        return redirect()->back()->with('status', 'Product updated successfully');
    }

    // Menghapus produk
    public function deleteproduct($id)
    {
        $data = Product::find($id);

        // Menghapus gambar produk jika ada
        if (file_exists(public_path('images/' . $data->image))) {
            unlink(public_path('images/' . $data->image));
        }

        $data->delete();
        return redirect()->route('showproduct')->with('status', 'Product deleted successfully');
    }

    public function showorder()
    {
        $order = Order::all();
        return view('admin.showorder', compact('order'));
    }

    public function updatestatus($id)
    {
        $order = Order::find($id);
        $order->status = 'Delivered';
        $order->save();
        return redirect()->back()->with('status', 'Order status updated successfully');
    }
}
