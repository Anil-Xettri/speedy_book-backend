<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Movie;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CollectionController extends BaseController
{
    public function __construct()
    {
        $this->title = 'Collections';
        $this->resources = 'vendors.collections.';
        parent::__construct();
        $this->route = 'collections.';
    }

    public function getCollections(Request $request)
    {
        $info = $this->crudInfo();
        $info['movies'] = Movie::where('vendor_id', auth('vendor')->user()->id)->get();
        if (isset($request->movie_id) && isset($request->month) && isset($request->year)) {
            if ($request->movie_id != null && $request->month != null) {
                $month = Carbon::now()->format('m');
                $days = Carbon::now()->month($request->month)->daysInMonth;
                $totalDays = [];
                $dayWiseCollections = [];
                $info['selectedMovie'] = $request->movie_id;
                $info['selectedMonth'] = $request->month;
                $info['selectedYear'] = $request->year;
                $bookingIds = Booking::where(['vendor_id' => auth('vendor')->user()->id, 'movie_id' => $request->movie_id])->pluck('id')->toArray();
                for ($i = 1; $i <= $days; $i++) {
                    $paymentData = Payment::where('vendor_id', auth('vendor')->user()->id)->whereIn('booking_id', $bookingIds)->whereDate('created_at', $i)->whereMonth('created_at', $month)
                        ->whereYear('created_at', $request->year)
                        ->get();
                    $totalDays[] = 'day-' . $i;
                    $collectedAmount = 0;
                    foreach ($paymentData ?? [] as $payment) {
                        $collectedAmount += $payment->booking->sum('total');
                    }
                    $dayWiseCollections[] = $collectedAmount;
                }
                $info['totalDays'] = $totalDays;
                $info['dayWiseCollections'] = $dayWiseCollections;
            }
        }
        $info['hideCreate'] = true;
        return view($this->indexResource(), $info);
    }
}
