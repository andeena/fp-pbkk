
        
<div class="latest-products">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="section-heading">
              <h2>Our Products</h2>
              <a href="products.html">view all products <i class="fa fa-angle-right"></i></a>

              <form class="form-inline" style="float: right" action="{{url('search')}}" method="get">
                <div class="form-group d-flex">
                @csrf

                  <input type="text" class="form-control" name="search" placeholder="Search">
                  <input type="submit" value="Search" class="btn btn-success"></button>
                  </div>
              </form>
            </div>
          </div>

          @foreach($data as $product)
              <div class="col-md-4">
                  <div class="product-item">
                      <a href="#"><img height="300" width="150" src="/images/{{$product->image}}" alt=""></a>
                      <div class="down-content">
                          <a href="#"><h4>{{$product->title}}</h4></a>
                          <h6>Rp{{$product->price}}</h6>
                          <p>{{$product->description}}</p>
                          
                          <div>
                            <img src="data:image/png;base64,{{$product->barcode}}" alt="Barcode">
                          </div>


                          <form action="{{url('addcart',$product->id)}}" method="POST">
                            @csrf
                            <input type="number" value="1" min="1" class="form-control" style="width: 100px" name="quantity">
                            <br>

                            
                            <button type="submit" class="btn btn-primary">Add to Cart</button>

                          </form>
                        </div>
                  </div>
              </div>
          @endforeach

          @if(method_exists($data, 'links'))

          <div class="d-flex justify-content-center">
            {!! $data->links() !!}
          </div>

          @endif

        </div>
      </div>
    </div>