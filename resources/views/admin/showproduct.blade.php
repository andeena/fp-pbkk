<!DOCTYPE html>
<html lang="en">
  <head>
    @include('admin.css')
  </head>
  <body>
    @include('admin.sidebar')
    @include('admin.navbar')

    <div class="container" align="center" style="margin: 30px auto; padding: 10px;">
  @if(session()->has('status'))
    <div class="alert alert-success" style="margin: 20px; padding: 15px;">
      <button type="button" class="close" data-dismiss="alert">x</button>
      {{ session()->get('status') }}
    </div>
  @endif

  <div class="table-responsive">
    <table class="table table-bordered table-striped" style="margin-top:20px; width:90%;">
      <tr class="bg-primary text-white text-center">
        <td>Product Name</td>
        <td>Description</td>
        <td>Price</td>
        <td>Quantity</td>
        <td>Image</td>
        <td>Barcode</td>
        <td>Update</td>
        <td>Delete</td>
      </tr>

      @foreach ($data as $product)
      <tr>
          <td>{{ $product->title }}</td>
          <td>{{ $product->description }}</td>
          <td>{{ $product->price }}</td>
          <td>{{ $product->quantity }}</td>
          <td>
              @if ($product->image)
                  <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->title }}" width="50">
              @endif
          </td>
          <td>
              @if ($product->barcode)
                  <img src="data:image/png;base64,{{ $product->barcode }}" alt="Barcode">
              @else
                  <span>No Barcode Generated</span>
                  <a href="{{ route('regenerate.barcode', $product->id) }}" class="btn btn-primary btn-sm">Generate Barcode</a>
              @endif
          </td>
          <td><a href="{{ route('updateview', $product->id) }}" class="btn btn-warning btn-sm">Update</a></td>
          <td><a href="{{ route('deleteproduct', $product->id) }}" class="btn btn-danger btn-sm">Delete</a></td>
      </tr>
      @endforeach

    </table>
  </div>

  <!-- Pagination -->
  <div class="d-flex justify-content-center" style="margin-top: 20px;">
    {!! $data->links() !!}
  </div>
</div>


    @include('admin.script')
  </body>
</html>
