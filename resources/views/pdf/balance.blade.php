<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <style>
    body {
      font-family: 'sans-serif';
    }
    table {
      width: 100%;
      border-collapse: collapse;
      padding: 0;
    }
    table td,
    table th {
      border: 1px solid #000;
      border-collapse: collapse;
      text-align: center;
      height: 1rem;
      padding: 0;
    }
    .pago {
      color: red;
    }

    table.balances th:last-child,
    table.balances td:last-child {
      text-align: right;
    }
    table.balances tr td:first-child,
    table.balances tr th:first-child {
      white-space: nowrap;
      width: fit-content;
    }
    table.balances td:nth-child(4){
      text-transform: capitalize;
    }

    table.info table th,
    table.info table td {
      padding: 4px;
      font-size: .9rem;
    }

    table.balances th,
    table.balances td {
      padding: 4px;
      font-size: .9rem;
    }

    table.info th {
      text-align: left;
      background: lightblue;
    }

    table.unbordered th,
    table.unbordered td {
      border: none;
    }
  </style>
</head>
<body>
  <table class="unbordered" style="margin-bottom: 1rem;">
    <tr>
      <td>
        <table class="unbordered">
          <tr>
            <td>
              <img src="{{ public_path('/img/logo.png') }}" width="150px" alt="">
            </td>
            <td>
              <ul style="list-style-type: none; padding: 0; margin: 0;">
                <li>Nit. 901394830-5</li>
                <li>
                  <a href="www.phenlinea.com">
                  www.phenlinea.com
                  </a>
                </li>
                <li>Cel. 305 283 08 98</li>
                <li>
                  <a href="mailto:info@phenlinea.com">
                    info@phenlinea.com
                  </a>
                </li>
                <li>Calle 49b # 76 - 40</li>
              </ul>
            </td>
          </tr>
        </table>
      </td>
      <td style="padding-left: 2rem;">
        <div style="text-align: center";>
          Estado de cuenta
        </div>
        <table>
          <tr>
            <th>Fecha de emision</th>
            <th>Fecha de suspension</th>
          </tr>
          <tr>
            <td>{{ now()->format('Y-m-d') }}</td>
            <td></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <table class="info" style="margin-bottom: 1rem; border: none;">
    <tr>
      <td style="border: none">
        <table>
          <tr>
            <th>Cliente</th>
            <td>{{ $extension->owner_name }}</td>
          </tr>
          <tr>
            <th>NIT</th>
            <td>{{ $extension->id }}</td>
          </tr>
          <tr>
            <th>Dirección</th>
            <td>{{ $extension->owner_name }}</td>
          </tr>
        </table>
      </td>
      <td style="border: none">
        <table>
          <tr>
            <th>Correo</th>
            <td>{{ $extension->owner_name }}</td>
          </tr>
          <tr>
            <th>Teléfono</th>
            <td>{{ $extension->owner_phone }}</td>
          </tr>
          <tr>
            <th>Encargado</th>
            <td>SIS. ADMIN</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <table class="balances" style="width: 100%;">
    <thead>
        <tr>
          <th colspan="2"></th>
          <th colspan="3" style="text-align: center;">FACTURACIÓN</th>
        </tr>
        <tr>
          <th>No. Factura</th>
          <th>Cliente</th>
          <th>Fecha</th>
          <th>Mes</th>
          <th>Valor</th>
        </tr>
    </thead>
    <tbody>
      @php setlocale(LC_TIME, 'es_ES'); @endphp
      @foreach( $rows as $item )
      <tr class="@if(!array_key_exists('extension_id', $item)) pago @endif">
        <td>
          {{ array_key_exists('extension_id', $item, ) ? $item['id'] : 'PAGO' }}
        </td>
        <td>
          {{ $extension['owner_name'] }}
        </td>
        <td>
          {{ $item['created_at'] }}
        </td>
        <td>
          {{ \Carbon\Carbon::parse($item['created_at'])->isoFormat('MMMM') }}
        </td>
        <td>
          {{ !array_key_exists('extension_id', $item) ? '-' : '' }}
          $ {{ $item['total'] }}
        </td>
      </tr>
      @endforeach
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td colspan="3"></td>
        <td style="text-align: left;">
          <b>
            TOTAL
          </b>
        </td>
        <td style="text-align: right;">
          <b>
            $ 999.999
          </b>
        </td>
      </tr>
    </tbody>
  </table>
</body>
</html>