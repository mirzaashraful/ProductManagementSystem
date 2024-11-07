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
            <a href="{{ route('products.index') }}" class="btn btn-dark">Back</a>
        </div>
    </div>
    <div class="row d-flex justify-content-center">
        <div class="col-md-10">
            <div class="card borde-0 shadow-lg my-4">
                <div class="card-header bg-dark">
                    <h3 class="text-white">Products</h3>
                </div>
                <div class="card-body">
                    <h1>Product Details</h1>
                    <p><strong>Product ID:</strong> {{ $product->product_id }}</p>
                    <p><strong>Name:</strong> {{ $product->name }}</p>
                    <p><strong>Description:</strong> {{ $product->description }}</p>
                    <p><strong>Price:</strong> {{ $product->price }}</p>
                    <p><strong>Stock:</strong> {{ $product->stock }}</p>

                    @if($product->image)
                        <img width="50" src="{{ asset('uploads/products/'.$product->image) }}" alt="{{ $product->name }}">
                        {{--                                            <p><strong>Image:</strong> <img src="{{ $product->image }}" alt="{{ $product->name }}" style="max-width: 300px;"></p>--}}
                    @else
                        <p>No image available</p>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

</body>
</html>
