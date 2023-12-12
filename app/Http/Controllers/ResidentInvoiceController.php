<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Extension;
use App\Imports\ResidentInvoiceImport;
use App\ResidentInvoice;
use App\ResidentInvoicePayment;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class ResidentInvoiceController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request, ?Extension $extension)
  {
    if( $extension ){
      $resident_invoices = $extension->resident_invoices;
      return view('admin.extensions.invoices', compact('extension', 'resident_invoices'));
    }
    $resident_invoices = auth()->user()->resident_invoices;
    return view('admin.resident-invoices.index', compact('resident_invoices'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function upload()
  {
    return view('admin.resident-invoices.import');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show(ResidentInvoice $resident_invoice)
  {
    $resident_invoice->load('resident_invoice_batch.admin');
    return view('public.resident-invoices.show', compact('resident_invoice'));
  }

  public function import(Request $request)
  {
    $request->validate(['file' => 'file|max:5000|mimes:xls,xlsx']);
    $file = $request->file('file');
    $path = $file->store('resident-invoices');
    Excel::import(new ResidentInvoiceImport($request->all()), $path);
    return response()->json(['data' => 'Success']);
  }

  public function download(ResidentInvoice $resident_invoice)
  {
    $pdf = Pdf::loadView('pdf.factura', ['resident_invoice' => $resident_invoice]);
    return $pdf->download('invoice.pdf');
  }

  public function apartmentInvoices(Request $request){
    $request->validate(['nit'=>'required', 'apto'=>'required']);
    $admin     = Admin::whereNit($request->nit)->firstOrFail();
    $apto      = $admin->extensions()->whereName( $request->apto )->firstOrFail();
    $invoices  = $apto->resident_invoices;
    return view('public.resident-invoices.index', compact('invoices'));
  }

  public function status(Extension $extension, Request $request){
    $from        = $request->from ?: Carbon::now()->startOfYear()->format('Y-m-d');
    $to          = $request->to   ?: Carbon::now()->addDay()->format('Y-m-d');

    $invoices    = $extension->resident_invoices()->whereBetween('created_at', [$from, $to])->get();
    $invoice_ids = $invoices->pluck('id');

    $payments    = ResidentInvoicePayment::whereIn('resident_invoice_id', $invoice_ids)
                  ->whereBetween('created_at', [$from, $to])
                  ->get(['id', 'resident_invoice_id', 'amount as total', 'created_at']);

    $deuda = $invoices->reduce(fn($carry, $invoice)=> $carry + $invoice->total, 0);
    $abono = $payments->reduce(fn($carry, $payment)=> $carry + $payment->total, 0);
    $total = $deuda - $abono;

    $rows = array_merge($invoices->toArray(), $payments->toArray());
    
    usort($rows, function ($a, $b) {
      return strtotime($a['created_at']) - strtotime($b['created_at']);
    });

    return Inertia::render('ResidentInvoices/AccountStatus', compact('from', 'to', 'extension', 'rows', 'total'));
  }

  function downloadBalancePDF(Request $request, Extension $extension){
    $from        = $request->from ?: Carbon::now()->startOfYear()->format('Y-m-d');
    $to          = $request->to   ?: Carbon::now()->addDay()->format('Y-m-d');
    $invoices    = $extension->resident_invoices()->whereBetween('created_at', [$from, $to])->get();
    $invoice_ids = $invoices->pluck('id');
    $payments    = ResidentInvoicePayment::whereIn('resident_invoice_id', $invoice_ids)
                  ->whereBetween('created_at', [$from, $to])
                  ->get(['id', 'resident_invoice_id', 'amount as total', 'created_at']);

    $deuda = $invoices->reduce(fn($carry, $invoice)=> $carry + $invoice->total, 0);
    $abono = $payments->reduce(fn($carry, $payment)=> $carry + $payment->total, 0);
    $total = $deuda - $abono;

    $rows = array_merge($invoices->toArray(), $payments->toArray());
    
    usort($rows, function ($a, $b) {
      return strtotime($a['created_at']) - strtotime($b['created_at']);
    });

    $pdf = Pdf::loadView('pdf.balance', compact('extension', 'rows', 'from', 'to', 'total'));
    return $pdf->stream();
    return $pdf->download('edo_cta.pdf');
  }
}
