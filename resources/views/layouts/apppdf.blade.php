<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plantilla</title>
    <link rel="icon" href="{{ config('SCITS.favicon') }}">

    <style>
        /* Aquí puedes incluir los estilos comunes para tu PDF */
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            padding: 0;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #2c3e50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>
