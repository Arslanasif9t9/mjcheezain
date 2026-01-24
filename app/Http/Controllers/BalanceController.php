<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BalanceController extends Controller
{
    // Show withdrawal page
    public function showWithdrawalPage()
    {
        $vendorId = Auth::id();
        
        // Get vendor balance
        $balance = DB::table('vendor_balances')
                    ->where('vendor_id', $vendorId)
                    ->first();
        
        // If no balance record exists, create one
        if (!$balance) {
            $balance = $this->createBalanceRecord($vendorId);
        }
        
        $user = (object) ['user_id' => $vendorId];
        // dd($user);
        return view('vendor.withdraw', compact('balance', 'user'));
    }
    
    // Create balance record if not exists
    private function createBalanceRecord($vendorId)
    {
        DB::table('vendor_balances')->insert([
            'vendor_id' => $vendorId,
            'available_balance' => 0,
            'pending_balance' => 0,
            'total_balance' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        return DB::table('vendor_balances')
                ->where('vendor_id', $vendorId)
                ->first();
    }
    
    // Process withdrawal request
    public function processWithdrawal(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'account_number' => 'required|string|max:50',
            'account_holder_name' => 'required|string|max:100',
            'bank_name' => 'required|string|max:100',
            'ifsc_code' => 'required|string|max:20'
        ]);
        
        $vendorId = Auth::id();
        
        // Get current balance
        $balance = DB::table('vendor_balances')
                    ->where('vendor_id', $vendorId)
                    ->first();
        
        // Check if sufficient balance is available
        if ($balance->available_balance < $request->amount) {
            return redirect()->back()
                ->with('error', 'Insufficient available balance!')
                ->withInput();
        }
        
        DB::beginTransaction();
        
        try {
            // Create withdrawal request
            $withdrawalId = DB::table('withdrawal_requests')->insertGetId([
                'vendor_id' => $vendorId,
                'amount' => $request->amount,
                'account_number' => $request->account_number,
                'account_holder_name' => $request->account_holder_name,
                'bank_name' => $request->bank_name,
                'ifsc_code' => $request->ifsc_code,
                'notes' => $request->notes,
                'status' => 'pending',
                'requested_at' => now()
            ]);
            
            // Update vendor balance (move from available to pending)
            DB::table('vendor_balances')
                ->where('vendor_id', $vendorId)
                ->update([
                    'available_balance' => $balance->available_balance - $request->amount,
                    'pending_balance' => $balance->pending_balance + $request->amount,
                    'updated_at' => now()
                ]);
            
            // Record transaction
            $this->recordTransaction($vendorId, 'withdrawal', $request->amount, 
                $balance->available_balance, 
                $balance->available_balance - $request->amount,
                'Withdrawal request initiated',
                $withdrawalId,
                'withdrawal_requests'
            );
            
            DB::commit();
            
            return redirect()->route('vendor.withdraw')
                ->with('success', 'Withdrawal request submitted successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error processing withdrawal: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    // Show balance details
    public function showBalanceDetails()
    {
        $vendorId = Auth::id();
        
        // Get current balance
        $balance = DB::table('vendor_balances')
                    ->where('vendor_id', $vendorId)
                    ->first();
        
        // Get recent transactions
        $transactions = DB::table('balance_transactions')
                        ->where('vendor_id', $vendorId)
                        ->orderBy('transaction_date', 'desc')
                        ->paginate(10);
        
        // Get withdrawal history
        $withdrawals = DB::table('withdrawal_requests')
                        ->where('vendor_id', $vendorId)
                        ->orderBy('requested_at', 'desc')
                        ->paginate(10);
        
        return view('vendor.balance-details', compact('balance', 'transactions', 'withdrawals'));
    }
    
    // Record transaction helper method
    private function recordTransaction($vendorId, $type, $amount, $previousBalance, $newBalance, $description, $referenceId = null, $referenceType = null)
    {
        DB::table('balance_transactions')->insert([
            'vendor_id' => $vendorId,
            'transaction_type' => $type,
            'amount' => $amount,
            'previous_balance' => $previousBalance,
            'new_balance' => $newBalance,
            'description' => $description,
            'reference_id' => $referenceId,
            'reference_type' => $referenceType,
            'transaction_date' => now()
        ]);
    }
}