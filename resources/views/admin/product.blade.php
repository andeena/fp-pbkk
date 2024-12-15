<!DOCTYPE html>
<html lang="en">
  <head>
    @include('admin.css')
    <style type="text/css">
      .title{
        color:white; padding-top: 25px; font-size: 25px
      }

      label{
        display:inline-block; width: 200px; margin-bottom: 10px; color:white
      }
      </style>
      
</head>
  
<body>
    @include('admin.sidebar')
    @include('admin.navbar')

    <div class="container-fluid page-body-wrapper">
      <div class="container" align="center">
        <h1 class="title">Add Product</h1>

        @if(session()->has('status'))
          <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">x</button>
            {{session()->get('status')}}
          </div>

        @endif

        <form action="{{url('uploadproduct')}}" method="post" enctype="multipart/form-data">
          @csrf
        <div style="padding: 15px">
          <label>Product Name</label>
          <input style="color:black" type="text" name="title" placeholder="Give a Product Name" required="">
        </div>

        <div style="padding: 15px">
          <label>Product Description</label>
          <textarea style="color:black" name="description" placeholder="Give a Product Description" required=""></textarea>
        </div>

        <div style="padding: 15px">
          <label>Product Price</label>
          <input style="color:black" type="text" name="price" placeholder="Give a Product Price" required="">
        </div>

        <div style="padding: 15px">
          <label>Product Quantity</label>
          <input style="color:black" type="text" name="quantity" placeholder="Give a Product Quantity" required="">
        </div>

        <div style="padding: 15px">
          <label>Product Image</label>
          <input style="color:black" type="file" name="image" required="">
        </div>

        <div style="padding: 15px">
          <label>Product Barcode</label>
          @if(!empty($product->barcode))
              <img src="data:image/png;base64,{{$product->barcode}}" alt="Barcode">
          @else
              <p>No Barcode Available</p>
          @endif
        </div>


        <div style="padding: 15px">
          <button type="submit" class="btn btn-primary">Add Product</button>
        </div>
      </form>




      </div>  
    </div>
    
    @include('admin.script')
</body>

</html>
