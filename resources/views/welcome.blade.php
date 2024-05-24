<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <form method="post" action="{{ route('orders.store') }}">
            @csrf
            <div class="form-group">
                <label for="symbol">Symbol:</label>
                <input type="text" class="form-control" value="BTCUSDT" name="symbol" id="symbol" required>
            </div>

            <div class="form-group">
                <label for="side">Side (buy/sell):</label>
                <input type="text" class="form-control" name="side" id="side" value="buy" required>
            </div>

            <div class="form-group">
                <label for="type">Type (market/limit):</label>
                <input type="text" class="form-control" name="type" value="limit" id="type" required>
            </div>

            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" class="form-control" name="quantity" value="2" id="quantity" required>
            </div>

            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" class="form-control" name="price" id="price" >
            </div>

            <button type="submit" class="btn btn-primary">Create Order</button>
        </form>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
