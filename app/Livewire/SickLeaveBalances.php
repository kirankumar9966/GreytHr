<?php

namespace App\Livewire;

use App\Models\EmployeeDetails;
use App\Models\EmployeeLeaveBalances;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class SickLeaveBalances extends Component
{
    public $leaveData, $year;
    public $currentYear;
    public $employeeLeaveBalances;
    public $employeeleaveavlid;
    public $totalSickDays = 0;
    public $Availablebalance, $employeeDetails, $leaveGrantedData,$availedLeavesCount;


    ///calculate number of days
    public function calculateNumberOfDays($fromDate, $fromSession, $toDate, $toSession)
    {
        try {
            $startDate = Carbon::parse($fromDate);
            $endDate = Carbon::parse($toDate);

            // Check if the start or end date is a weekend
            if ($startDate->isWeekend() || $endDate->isWeekend()) {
                return 'Error: Selected dates fall on a weekend. Please choose weekdays.';
            }

            // Check if the start and end sessions are different on the same day
            if ($startDate->isSameDay($endDate)) {
                if (self::getSessionNumber($fromSession) !== self::getSessionNumber($toSession)) {
                    return 1;
                } elseif (self::getSessionNumber($fromSession) == self::getSessionNumber($toSession)) {
                    return 0.5;
                } else {
                    return 0;
                }
            }

            $totalDays = 0;

            while ($startDate->lte($endDate)) {
                // Check if it's a weekday (Monday to Friday)
                if ($startDate->isWeekday()) {
                    $totalDays += 1;
                }
                // Move to the next day
                $startDate->addDay();
            }

            // Deduct weekends based on the session numbers
            if ($this->getSessionNumber($fromSession) > 1) {
                $totalDays -= $this->getSessionNumber($fromSession) - 1; // Deduct days for the starting session
            }
            if ($this->getSessionNumber($toSession) < 2) {
                $totalDays -= 2 - $this->getSessionNumber($toSession); // Deduct days for the ending session
            }
            // Adjust for half days
            if ($this->getSessionNumber($fromSession) === $this->getSessionNumber($toSession)) {
                // If start and end sessions are the same, check if the session is not 1
                if ($this->getSessionNumber($fromSession) !== 1) {
                    $totalDays += 0.5; // Add half a day
                } else {
                    $totalDays += 0.5;
                }
            } elseif ($this->getSessionNumber($fromSession) !== $this->getSessionNumber($toSession)) {
                if ($this->getSessionNumber($fromSession) !== 1) {
                    $totalDays += 1; // Add half a day
                }
            } else {
                $totalDays += ($this->getSessionNumber($toSession) - $this->getSessionNumber($fromSession) + 1) * 0.5;
            }

            return $totalDays;
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    private function getSessionNumber($session)
    {
        return (int) str_replace('Session ', '', $session);
    }

    public function yearDropDown()
    {
        try {
            $currentYear = Carbon::now()->format('Y');
            if ($this->isTrue($currentYear - 2)) {
            } elseif ($this->isTrue($currentYear - 1)) {
            } elseif ($this->isTrue($currentYear)) {
            } else {
            }
        } catch (\Exception $e) {
            // Add an error message or log a message indicating that an error occurred
            $errorMessage = 'An error occurred in yearDropDown() method: ' . $e->getMessage();
            $this->addError('session', 'An error occurred. Please try again later.');
        }
    }

    public function changeYear($year)
    {
        return redirect()->to("/sickleavebalance?year={$year}");
    }

    public function render()
    {
        try {

            $this->currentYear = date('Y');
            $this->year = request()->query('year') ?? date('Y');

            $employeeId = auth()->guard('emp')->user()->emp_id;
            $this->employeeDetails = EmployeeDetails::where('emp_id', $employeeId)->first();
            $this->leaveGrantedData = EmployeeLeaveBalances::where('emp_id', $employeeId)
                ->whereYear('from_date', '<=', $this->year)   // Check if the from_date year is less than or equal to the given year
                ->whereYear('to_date', '>=', $this->year)
                ->get();


            $this->employeeLeaveBalances = EmployeeLeaveBalances::where('emp_id', $employeeId)
                ->whereYear('from_date', '<=', $this->year)   // Check if the from_date year is less than or equal to the given year
                ->whereYear('to_date', '>=', $this->year)
                ->selectRaw("JSON_UNQUOTE(JSON_EXTRACT(leave_balance, '$.\"Sick Leave\"')) AS sick_leave")
                ->pluck('sick_leave')
                ->first();



            // Now $employeeLeaveBalances contains all the rows from employee_leave_balances
            // where emp_id matches and leave_type is "Sick Leave"
            $this->employeeleaveavlid = LeaveRequest::where('emp_id', $employeeId)
                ->where('leave_type', 'Sick Leave')
                ->whereYear('from_date', '<=', $this->year)   // Check if the from_date year is less than or equal to the given year
                ->whereYear('to_date', '>=', $this->year)
                ->where('status', 'approved')
                ->get();


            foreach ($this->employeeleaveavlid as $leaveRequest) {
                //$leaveType = $leaveRequest->leave_type;
                $days = self::calculateNumberOfDays(
                    $leaveRequest->from_date,
                    $leaveRequest->from_session,
                    $leaveRequest->to_date,
                    $leaveRequest->to_session
                );
                $this->totalSickDays += $days;
                // $this->Availablebalance = $this->employeeLeaveBalances->leave_balance - $this->totalSickDays;
            }
            // foreach ($this->employeeLeaveBalances as $employeeLeaveBalance) {
            //     $this->Availablebalance = $employeeLeaveBalance->leave_balance - $this->totalSickDays;

            // }
            $this->Availablebalance = $this->employeeLeaveBalances - $this->totalSickDays;


            $currentMonth = date('n');
            $currentYear = date('Y');
            $startingMonth = 1; // January

            $grantedLeavesByMonth = [];
            $availedLeavesByMonth = [];
            $grantedLeavesCount = EmployeeLeaveBalances::where('emp_id', $employeeId)
                ->whereYear('from_date', '<=', $this->year)   // Check if the from_date year is less than or equal to the given year
                ->whereYear('to_date', '>=', $this->year)
                ->selectRaw("JSON_UNQUOTE(JSON_EXTRACT(leave_balance, '$.\"Sick Leave\"')) AS sick_leave")
                ->pluck('sick_leave')
                ->first();
            for ($month = $startingMonth; $month <= $currentMonth; $month++) {
                // Fetch availed leaves count for this month
                $this->availedLeavesCount =0;
                $availedLeavesRequests = LeaveRequest::where('emp_id', $employeeId)
                    ->where('leave_type', 'Sick Leave')
                    ->where('status', 'approved')
                    ->whereYear('from_date', $currentYear)
                    ->whereMonth('from_date', $month)
                    ->get();

                    foreach ($availedLeavesRequests as $availedleaveRequest) {
                        //$leaveType = $leaveRequest->leave_type;
                        $days = self::calculateNumberOfDays(
                            $availedleaveRequest->from_date,
                            $availedleaveRequest->from_session,
                            $availedleaveRequest->to_date,
                            $availedleaveRequest->to_session
                        );
                        $this->availedLeavesCount += $days;}
                // Adjust granted leaves count by subtracting availed leaves count
                $grantedLeavesCount -= $this->availedLeavesCount;

                // Ensure granted leaves count is non-negative
                $grantedLeavesCount = max(0, $grantedLeavesCount);

                // Store the granted leaves count and availed leaves count in their respective arrays
                $grantedLeavesByMonth[] = $grantedLeavesCount;
                $availedLeavesByMonth[] = $this->availedLeavesCount;
            }

            $chartData = [
                'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                'datasets' => [
                    [
                        'label' => 'Granted Leaves',
                        'data' => $grantedLeavesByMonth,
                        'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                        'borderColor' => 'rgba(54, 162, 235, 1)',
                        'borderWidth' => 1
                    ],
                    [
                        'label' => 'Availed Leaves',
                        'data' => $availedLeavesByMonth,
                        'backgroundColor' => 'rgba(255, 99, 132, 0.5)',
                        'borderColor' => 'rgba(255, 99, 132, 1)',
                        'borderWidth' => 1
                    ]
                ]
            ];

            $chartOptions = [
                'scales' => [
                    'y' => [
                        'ticks' => [
                            'beginAtZero' => true,
                            'min' => 0,
                            'max' => 10,
                            'stepSize' => 2 // Adjust the step size to show values at intervals of 2
                        ],
                        'grid' => [
                            'display' => false // Remove grid lines from the y-axis
                        ]
                    ],
                    'x' => [
                        'grid' => [
                            'display' => false // Remove grid lines from the x-axis
                        ]
                    ]
                ],
                'maintainAspectRatio' => false, // Allow chart to be resized
                'responsive' => true // Make chart responsive
            ];
            return view('livewire.sick-leave-balances', [
                'employeeLeaveBalances' => $this->employeeLeaveBalances,
                'employeeleaveavlid' => $this->employeeleaveavlid,
                'totalSickDays' => $this->totalSickDays,
                'Availablebalance' => $this->Availablebalance,
                'chartData' => $chartData,
                'chartOptions' => $chartOptions // Pass chart options to the view
            ]);
        } catch (\Exception $e) {
            Log::error('Error in Sick Leave Balance render method: ' . $e->getMessage());
            return view('livewire.sick-leave-balances');
        }
    }
}
