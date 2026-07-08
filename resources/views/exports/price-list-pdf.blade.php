<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Price List</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #1877F2; color: white; padding: 10px; text-align: left; }
        td { padding: 8px 10px; border-bottom: 1px solid #ddd; }
        .product-image { width: 60px; height: 60px; object-fit: cover; border-radius: 4px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { color: #1877F2; margin: 0; }
        .header p { color: #555; margin: 5px 0 0; }
        .footer { text-align: center; margin-top: 30px; font-size: 10px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Market Place Uganda</h1>
        <p>Vendor Price List – {{ now()->format('d M Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <!-- <th>Image</th> -->
                <th>Code</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price (UGX)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
<!--                     <td>
                        @if($product->images && count($product->images) > 0)
                            <img src="{{ asset($product->images[0]) }}" class="product-image" alt="{{ $product->name }}" />
                        @else
                            <span style="color:#ccc;">No image</span>
                        @endif
                    </td> -->
                    <td>{{ $product->code ?? '-' }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name ?? 'Uncategorized' }}</td>
                    <td>{{ number_format($product->price, 0) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ now()->format('Y-m-d H:i:s') }} &bull; Market Place Uganda
    </div>
</body>
</html>