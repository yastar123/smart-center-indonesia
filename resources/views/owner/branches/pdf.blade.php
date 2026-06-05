<html>
<head>
    <meta charset="utf-8">
    <style>
        table { width:100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f4f4f4; }
    </style>
    <title>Branches</title>
</head>
<body>
    <h3>Branches</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Address</th>
                <th>City</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($branches as $b)
            <tr>
                <td>{{ $b->id }}</td>
                <td>{{ $b->name }}</td>
                <td>{{ $b->address }}</td>
                <td>{{ $b->city }}</td>
                <td>{{ $b->phone }}</td>
                <td>{{ $b->email }}</td>
                <td>{{ $b->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
