<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function save_orders(Request $request)
    {
        $request->validate([
            'exam_type_id' => 'required|exists:exam_types,id',
            'student_id' => 'required|exists:students,id',
            'email' => 'required|email',
            'mobile' => 'required',
            'validity_period' => 'required|in:6_months,1_year',
            'receipt' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp,pdf',
            'status' => 'required|boolean',
        ]);

        $validityDate = Carbon::now(); // Start date is current date
        $expiryDate = $this->calculateExpiryDate($validityDate, $request->validity_period);

        $order = new Order;

        $order->date = $validityDate;
        $order->exam_type_id = $request->exam_type_id;
        $order->student_id = $request->student_id;
        $order->email = $request->email;
        $order->mobile = $request->mobile;
        $order->start_validity_date = $validityDate;
        $order->expire_validity_date = $expiryDate;
        $order->status = $request->status;

        if ($request->hasFile('receipt')) {
            $file = $request->file('receipt');
            $fileName = time().'_'.$file->getClientOriginalName();
            $filePath = public_path('storage/receipt');
            $file->move($filePath, $fileName);
            $order->receipt = 'storage/receipt/'.$fileName;
        }

        $order->save();

        return response()->json([
            'message' => 'Order Added Successfully.',
            'data' => $order
        ], 200);
    }

    public function update_order_by_id(Request $request, $id)
    {
        $request->validate([
            'exam_type_id' => 'required|exists:exam_types,id',
            'student_id' => 'required|exists:students,id',
            'email' => 'required|email',
            'mobile' => 'required',
            'validity_period' => 'required|in:6_months,1_year',
            'receipt' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp,pdf',
            'status' => 'required|boolean',
        ]);

        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        $validityDate = Carbon::now(); // Start date is updated to current date
        $expiryDate = $this->calculateExpiryDate($validityDate, $request->validity_period);

        $order->date = $validityDate;
        $order->exam_type_id = $request->exam_type_id;
        $order->student_id = $request->student_id;
        $order->email = $request->email;
        $order->mobile = $request->mobile;
        $order->start_validity_date = $validityDate;
        $order->expire_validity_date = $expiryDate;
        $order->status = $request->status;

        if ($request->hasFile('receipt')) {
            $oldReceiptPath = public_path($order->receipt);

            if ($order->receipt && file_exists($oldReceiptPath)) {
                unlink($oldReceiptPath);
            }

            $file = $request->file('receipt');
            $fileName = time().'_'.$file->getClientOriginalName();
            $filePath = public_path('storage/receipt'); 

            if (!file_exists($filePath)) {
                mkdir($filePath, 0755, true);
            }

            $file->move($filePath, $fileName);

            $order->receipt = 'storage/receipt/'.$fileName;
        }

        $order->save();

        return response()->json([
            'message' => 'Order Updated Successfully.',
            'data' => $order
        ], 200);
    }

    public function delete_order_by_id($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        if ($order->receipt) {
            $receiptPath = public_path($order->receipt);

            if (file_exists($receiptPath)) {
                unlink($receiptPath);
            }
        }

        $result = $order->delete();

        if ($result) {
            return response()->json(['message' => 'Order Deleted'], 200);
        } else {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    private function calculateExpiryDate($startDate, $validityPeriod)
    {
        if ($validityPeriod === '6_months') {
            return $startDate->copy()->addMonths(6);
        } elseif ($validityPeriod === '1_year') {
            return $startDate->copy()->addYear();
        }

        throw new \Exception('Invalid validity period.');
    }

    public function get_all_order()
{
    $orders = Order::with(['examType', 'student'])->get();

    if ($orders->isEmpty()) {
        return response()->json(['error' => 'No Orders data found'], 404);
    }

    $orders = $orders->map(function ($order) {
        $order->receipt = $order->receipt ? asset($order->receipt) : null;

        if ($order->examType && $order->examType->photo) {
            $order->examType->photo = asset($order->examType->photo);
        }

        if ($order->student && $order->student->profile_image) {
            $order->student->profile_image = asset($order->student->profile_image);
        }

        return $order;
    });

    return response()->json([
        'message' => 'Orders retrieved successfully.',
        'data' => $orders
    ], 200);
}


public function get_order_by_id($id)
{
    $order = Order::with(['examType', 'student'])->find($id);

    if (!$order) {
        return response()->json(['error' => 'Order not found'], 404);
    }

    $order->receipt = $order->receipt ? asset($order->receipt) : null;

    if ($order->examType && $order->examType->photo) {
        $order->examType->photo = asset($order->examType->photo);
    }

    if ($order->student && $order->student->profile_image) {
        $order->student->profile_image = asset($order->student->profile_image);
    }

    return response()->json([
        'message' => 'Order retrieved successfully.',
        'data' => $order
    ], 200);
}

}