<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Invoice-{{$invoice->invoice_code}}</title>

  <style>
    html,
    body {
      margin: 10px;
      padding: 10px;
      font-family: "Poppins", sans-serif;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    p,
    span,
    label {
      font-family: "Poppins", sans-serif;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 0px !important;
    }

    table thead th {
      height: 28px;
      text-align: left;
      font-size: 16px;
      font-family: sans-serif;
    }

    table,
    th,
    td {
      padding: 8px;
      font-size: 14px;
    }

    .heading {
      font-size: 24px;
      margin-top: 12px;
      margin-bottom: 12px;
      font-family: sans-serif;
    }

    .small-heading {
      font-size: 18px;
      font-family: sans-serif;
    }

    .total-heading {
      font-size: 18px;
      font-weight: 700;
      font-family: sans-serif;
    }

    .order-details tbody tr td:nth-child(1) {
      width: 30%;
    }

    .order-details tbody tr td:nth-child(3) {
      width: 20%;
    }

    .text-start {
      text-align: left;
    }

    .text-end {
      text-align: right;
    }

    .text-center {
      text-align: center;
    }

    .company-data span {
      margin-bottom: 4px;
      display: inline-block;
      font-family: sans-serif;
      font-size: 14px;
      font-weight: 400;
    }

    .sub-heading {
      font-size: 14px;
    }

    .no-border {
      border: 1px solid #fff !important;
    }

    .bg-blue {
      background-color: black;
      color: #fff;
    }
  </style>
</head>

<body>
  <table class="order-details">
    <thead>
      <tr>
        <th width="50%" colspan="2">
          <h2 class="text-start">Gentle Baby</h2>
        </th>
        <th width="50%" colspan="2" class="text-end company-data">
          <span>Tanggal : {{date('Y-m-d')}}</span> <br />
          <span>Alamat : Malang</span>
          <br />
        </th>
      </tr>
      <tr class="bg-blue">
        <th width="50%" colspan="2">Order Details</th>
        <th width="50%" colspan="2">Customer Details</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Kode Invoice :</td>
        <td>#{{$invoice->invoice_code}}</td>

        <td>Perusahaan :</td>
        <td>{{$invoice->company->name}}</td>
      </tr>
      <tr>
        <td>Jatuh Tempo DP :</td>
        <td>{{$invoice->dp_value > 0 ? ($invoice->dp_due_date->format('d-m-Y')) : '-'}}</td>

        <td>Nama Pemilik</td>
        <td>{{$invoice->company->owner->name}}</td>
      </tr>
      <tr>
        <td>Jatuh Tempo Pelunasan:</td>
        <td>{{$invoice->payment_due_date->format('d-m-Y')}}</td>

        <td>Email Pemilik :</td>
        <td>{{$invoice->company->owner->name}}</td>
      </tr>
      <tr>
        <td>Metode Pembayaran :</td>
        <td>Transfer</td>

        <td>No Telp. Pemilik :</td>
        <td>{{$invoice->company->owner->phone_number}}</td>
      </tr>
      <tr>
        <td>Status DP :</td>
        <td>{{$invoice->dp_value > 0 ? ($invoice->dp_status ? 'Terbayar' : 'Belum Terbayar') : '-'}}</td>
      </tr>
      <tr>
        <td>Status Pelunasan :</td>
        <td>{{$invoice->payment_status == 1 ? 'Terbayar' : 'Belum Dibayar'}}</td>
      </tr>
    </tbody>
  </table>

  <table>
    <thead>
      <tr>
        <th class="no-border text-start heading" colspan="5">Order Items</th>
      </tr>
      <tr class="bg-blue">
        <th>ID</th>
        <th>Kode Transaksi</th>
        <th>Nama</th>
        <th>Harga</th>
        <th>Quantity</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($invoice->detailTransactions as $detailsTransaction)
      <tr>
        <td width="10%">{{$detailsTransaction->product->id }}</td>
        <td width="25%">#{{$detailsTransaction->transaction->transaction_code }}</td>
        <td>{{$detailsTransaction->product->name }}</td>
        <td width="15%">Rp{{number_format($detailsTransaction->price,0, ".",".")}}</td>
        <td width="10%">{{$detailsTransaction->qty }}</td>
        <td width="30%">Rp{{number_format(($detailsTransaction->price * $detailsTransaction->qty),0,
          ".",".")}}</td>
      </tr>
      @endforeach

      <tr>
        <td colspan="5" class="sub-heading">Total DP (35%) :</td>
        <td colspan="1" class="sub-heading">Rp{{number_format($invoice->dp_value ,0,".",".")}}</td>
      </tr>
      <tr>
        <td colspan="5" class="sub-heading">Total Pelunasan :</td>
        <td colspan="1" class="sub-heading">Rp{{number_format(($invoice->amount - $invoice->dp_value)
          ,0,".",".")}}</td>
      </tr>
      <tr>
        <td colspan="5" class="total-heading">Total Tagihan :</td>
        <td colspan="1" class="total-heading">Rp{{number_format($invoice->amount,0,".",".")}}</td>
      </tr>
    </tbody>
  </table>

  <br />
  <p class="text-center">Terimakasih telah bertransaksi dengan Gentle Baby</p>
</body>

</html>