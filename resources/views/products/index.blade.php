<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Product Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  </head>
  <body>
    <div class="bg-dark py-3">
        <h3 class="text-white text-center">Product Management System</h3>
    </div>
    <div class="container">
        <div class="row justify-content-center mt-4">
            <div class="col-md-10 d-flex justify-content-end">
                <a href="{{ route('products.create') }}" class="btn btn-dark">Create</a>
            </div>
        </div>
        <div class="row d-flex justify-content-center">
            @if (Session::has('success'))
            <div class="col-md-10 mt-4">
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
            </div>
            @endif
            <div class="col-md-10">
                <div class="card borde-0 shadow-lg my-4">
                    <div class="card-header bg-dark">
                        <h3 class="text-white">Products</h3>
                    </div>
                    <div class="card-body">
                        <!-- Filter Form -->
                        <form action="{{ route('products.index') }}" method="GET">
                            <div class="row">
{{--                                <div class="col-md-3">--}}
{{--                                    <input type="text" name="product_id" class="form-control" placeholder="Product ID" value="{{ request('product_id') }}">--}}
{{--                                </div>--}}
                                <div class="col-md-3">
                                    <input type="text" name="name" class="form-control" placeholder="Product Name" value="{{ request('name') }}">
                                </div>
                                <div class="col-md-3">
                                    <select name="sort_price" class="form-control">
                                        <option value="">Sort by Price</option>
                                        <option value="low_to_high" {{ request('sort_price') == 'low_to_high' ? 'selected' : '' }}>Low to High</option>
                                        <option value="high_to_low" {{ request('sort_price') == 'high_to_low' ? 'selected' : '' }}>High to Low</option>
                                    </select>
                                </div>
{{--                                <div class="col-md-2">--}}
{{--                                    <input type="number" name="min_price" class="form-control" placeholder="Price" value="{{ request('price') }}">--}}
{{--                                </div>--}}
{{--                                <div class="col-md-2">--}}
{{--                                    <input type="number" name="max_price" class="form-control" placeholder="Max Price" value="{{ request('max_price') }}">--}}
{{--                                </div>--}}
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Reset</a>
                                </div>
                            </div>
                        </form>
                        <table class="table">
                            <tr>
                                <th>ID</th>
                                <th>Product Id</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Image</th>
                                <th>Created at</th>
                                <th>Update at</th>
                                <th>Action</th>
                            </tr>
                            @if ($products->isNotEmpty())
                            @foreach ($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>{{$product->product_id}}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{$product->description}}</td>
                                <td>${{ $product->price }}</td>
                                <td>{{ $product->stock }}</td>
                                <td>
{{--                                    @if ($product->image != "")--}}
{{--                                        <img width="50" src="{{ asset('uploads/products/'.$product->image) }}" alt="">--}}
{{--                                    @endif--}}
                                        @if($product->image)
                                            <img width="50" src="{{ asset('uploads/products/'.$product->image) }}" alt="{{ $product->name }}">
{{--                                            <p><strong>Image:</strong> <img src="{{ $product->image }}" alt="{{ $product->name }}" style="max-width: 300px;"></p>--}}
                                        @else
                                            <p>No image available</p>
                                        @endif
                                </td>
                                <td>{{ $product->created_at->format('Y-m-d') }}</td>
                                <td>{{ $product->updated_at->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary" role="button">View</a>
                                    <a href="{{ route('products.edit',$product->id) }}" class="btn btn-dark">Edit</a>
                                    <a href="#" onclick="deleteProduct({{ $product->id  }});" class="btn btn-danger">Delete</a>
                                    <form id="delete-product-from-{{ $product->id  }}" action="{{ route('products.destroy',$product->id) }}" method="post">
                                        @csrf
                                        @method('delete')
                                    </form>
                                </td>
                            </tr>
                            @endforeach

                            @endif

                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $products->links('pagination::simple-bootstrap-5') }}
                    </div>

                </div>
            </div>
        </div>

    </div>

  </body>
</html>

<script>
    function deleteProduct(id) {
        if (confirm("Are you sure you want to delete product?")) {
            document.getElementById("delete-product-from-"+id).submit();
        }
    }
</script>
