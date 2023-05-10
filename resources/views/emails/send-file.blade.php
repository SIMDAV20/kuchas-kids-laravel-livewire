<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nueva Venta</title>

    <style>
        * {
            color: #252528;
        }

        .text-center {
            text-align: center
        }

        .flex {
            display: flex;
        }

        .flex-col {
            flex-direction: column;
        }

        .items-center {
            align-items: center;
        }

        .text-xl {
            font-size: 20px;
        }

        .text-lg {
            font-size: 18px;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .text-sm {
            font-size: 12px;
        }

        .font-semi-bold {
            font-weight: 500;
        }

        .font-bold {
            font-weight: 600;
        }

        p {
            margin-top: 0 !important;
        }

        .uppercase {
            text-transform: uppercase;
        }

    </style>
</head>

<body>

    @php
        $file = $msg['file'];
    @endphp

    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <h1 style="color: pink">¡Gracias por comprar en Kuchas Kids!</h1>
        @php
            $name = explode('_', (new SplFileInfo($file))->getFilename())[0];
        @endphp

        <a href="{{ $file }}" target="_blank" class="uppercase text-xl">{{ $name }}</a>
        <p>Click en el link para descargar</p>
    </div>

</body>

</html>
