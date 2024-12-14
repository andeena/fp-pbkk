<!DOCTYPE html>
<html lang="en">
  <head>

  <base href="/public"> 
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
  
      <!-- partial -->
      <!--  -->
      <body>
        @include('admin.sidebar')
      @include('admin.navbar')
      
        <!-- partial -->

        <div class="container-fluid page-body-wrapper">
      <div class="container" align="center">
        <h1 class="title">Update Product</h1>

        @if(session()->has('status'))
          <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">x</button>
            {{session()->get('status')}}
          </div>

        @endif

        <form action="{{url('updateproduct',$data->id)}}" method="post" enctype="multipart/form-data">
          @csrf
        <div style="padding: 15px">
          <label>Product Name</label>
          <input style="color:black" type="text" name="title" value="{{$data->title}}" required="">
        </div>

        <div style="padding: 15px">
          <label>Product Description</label>
          <input style="color:black" type="text" name="description" value="{{$data->description}}" required=""></textarea>
        </div>

        <div style="padding: 15px">
          <label>Product Price</label>
          <input style="color:black" type="text" name="price" value="{{$data->price}}" required="">
        </div>

        <div style="padding: 15px">
          <label>Product Quantity</label>
          <input style="color:black" type="text" name="quantity" value="{{$data->quantity}}" required="">
        </div>

        <div style="padding: 15px">
          <label>Old Image</label>
          <img src="/images/{{$data->image}}" width="100px" height="100px">
        </div>

        <div style="padding: 15px">
          <label>Change the Image</label>
          <input style="color:black" type="file" name="image">
        </div>

        <div style="padding: 15px">
          <button type="submit" class="btn btn-primary">Add Product</button>
        </div>
      </form>




      </div>  
    </div>
        
          <!-- partial -->
        @include('admin.script')
  </body>
</html>
