<!DOCTYPE html>
<html lang="en">
  <head>
    @include('admin.css')
  </head>
  <body>
    @include('admin.sidebar')
    @include('admin.navbar')

    <div class="container-fluid page-body-wrapper">
      <div class="container" align="center">

        @if(session()->has('status'))
          <div class="alert alert-success" style="margin: 20px; padding: 15px;">
            <button type="button" class="close" data-dismiss="alert">x</button>
            {{session()->get('status')}}
          </div>
        @endif

        <div class="table-responsive">
          <table class="table table-bordered table-striped" style="margin-top:20px; width:90%;">
            <tr style="background-color: grey; text-align:center;">
              <td style="padding:20px">Product Name</td>
              <td style="padding:20px">Description</td>
              <td style="padding:20px">Price</td>
              <td style="padding:20px">Quantity</td>
              <td style="padding:20px">Image</td>
              <td style="padding:20px">Barcode</td>
              <td style="padding:20px">Update</td>
              <td style="padding:20px">Delete</td>
            </tr>

            @foreach($data as $product)
              <tr align="center" style="background-color: black;">
                <td style="padding:20px">{{$product->title}}</td>
                <td style="padding:20px">{{$product->description}}</td>
                <td style="padding:20px">{{$product->price}}</td>
                <td style="padding:20px">{{$product->quantity}}</td>
                <td style="padding:20px">
                  @if($product->image)
                    <img src="/images/{{$product->image}}" width="100px" height="100px">
                  @else
                    <span>No Image</span>
                  @endif
                </td>
                <td style="padding:20px">
                  <img src="data:image/png;base64,{{$product->barcode}}" width="150px" height="50px">
                </td>
                <td style="padding:20px">
                  <a class="btn btn-primary" href="{{url('updateview')}}/{{$product->id}}">Update</a>
                </td>
                <td style="padding:20px">
                  <a class="btn btn-danger" onclick="return confirm('Are you sure want to remove this item?')" href="{{url('deleteproduct')}}/{{$product->id}}">Delete</a>
                </td>
              </tr>
            @endforeach

          </table>
        </div>
      </div>
    </div>

    @include('admin.script')
  </body>
</html>
