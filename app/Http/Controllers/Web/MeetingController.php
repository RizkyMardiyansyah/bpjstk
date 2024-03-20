<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Meeting;
use App\Models\MeetingTime;
use App\Models\Api\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentChannel;
use App\Models\ReserveMeeting;
use App\Models\Sale;
use App\Models\Setting;
use Google\Service\AndroidEnterprise\Resource\Users;
use Illuminate\Http\Request;

use App\Models\Reward;
use App\Models\RewardAccounting;

class MeetingController extends Controller
{
    public function reserve(Request $request)
    {
        $user = auth()->user();

        
        
        

        if (!empty($user)) {

            $timeId = $request->input('time');
            // @dd($timeId);
            $userIdRes = $request->get('userId');
            $deleteMeeting=$request->get('meetingId');
            // dd($userIdRes);
            
            $day = $request->input('day');
            $studentCount = $request->get('student_count', 1);
            $selectedMeetingType = $request->get('meeting_type', 'online');
            $selectedSessionType = $request->get('session_type', 'mentoring');
            $description = $request->get('description');
            $is_reverse = $request->get('is_reverse', false);

            $userId = ($userIdRes === null) ? $user->id : $userIdRes;
            // dd($userIdRes);

            

            if (empty($studentCount)) {
                $studentCount = 1;
            }

            if (!in_array($selectedMeetingType, ['in_person', 'online'])) {
                $selectedMeetingType = 'online';
            }

            if (!empty($timeId)) {
                $meetingTime = MeetingTime::where('id', $timeId)
                    ->with('meeting')
                    ->first();

                if (!empty($meetingTime)) {
                    $meeting = $meetingTime->meeting;

                    // if ($meeting->creator_id == $user->id) {
                    //     $toastData = [
                    //         'title' => trans('public.request_failed'),
                    //         'msg' => trans('update.cant_reserve_your_appointment'),
                    //         'status' => 'error'
                    //     ];
                    //     return response()->json($toastData);
                    // }

                    if (!empty($meeting) and !$meeting->disabled) {
                        if (!empty($meeting->amount) and $meeting->amount > 0) {

                            $reserveMeeting = ReserveMeeting::where('meeting_time_id', $meetingTime->id)
                                ->where('day', $day)
                                ->first();

                            if (!empty($reserveMeeting) and $reserveMeeting->locked_at) {
                                $toastData = [
                                    'title' => trans('public.request_failed'),
                                    'msg' => trans('meeting.locked_time'),
                                    'status' => 'error'
                                ];
                                return response()->json($toastData);
                            }

                            if (!empty($reserveMeeting) and $reserveMeeting->reserved_at) {
                                $toastData = [
                                    'title' => trans('public.request_failed'),
                                    'msg' => trans('meeting.reserved_time'),
                                    'status' => 'error'
                                ];
                                return response()->json($toastData);
                            }

                            $hourlyAmountResult = $this->handleHourlyMeetingAmount($meeting, $meetingTime, $studentCount, $selectedMeetingType);

                            if (!$hourlyAmountResult['status']) {
                                return $hourlyAmountResult['result']; // json response
                            }

                            $hourlyAmount = $hourlyAmountResult['result'];

                            $explodetime = explode('-', $meetingTime->time);

                            $hours = (strtotime($explodetime[1]) - strtotime($explodetime[0])) / 3600;

                            $instructorTimezone = $meeting->getTimezone();

                            $startAt = $this->handleUtcDate($day, $explodetime[0], $instructorTimezone);
                            $endAt = $this->handleUtcDate($day, $explodetime[1], $instructorTimezone);

                            $reserveMeeting = ReserveMeeting::updateOrCreate([
                                
                                'user_id' => $userId,
                                'meeting_time_id' => $meetingTime->id,
                                'meeting_id' => $meetingTime->meeting_id,
                                'status' => ReserveMeeting::$pending,
                                'day' => $day,
                                'meeting_type' => $selectedMeetingType,
                                'session_type' => $selectedSessionType,
                                'mentoring_type' => $is_reverse ? 'reverse' : 'meeting',
                                'student_count' => $studentCount
                            ], [
                                'date' => strtotime($day),
                                'start_at' => $startAt,
                                'end_at' => $endAt,
                                'paid_amount' => (!empty($hourlyAmount) and $hourlyAmount > 0) ? $hourlyAmount * $hours : 0,
                                'discount' => $meetingTime->meeting->discount,
                                'description' => $description,
                                'created_at' => time(),
                            ]);

                            $cart = Cart::where('creator_id', $user->id)
                                ->where('reserve_meeting_id', $reserveMeeting->id)
                                ->first();

                            if (empty($cart)) {
                                Cart::create([
                                    'creator_id' => $user->id,
                                    'reserve_meeting_id' => $reserveMeeting->id,
                                    'created_at' => time()
                                ]);
                            }

                            $toastData = [
                                'status' => 'success',
                                'title' => trans('public.request_success'),
                                'msg' => trans('update.meeting_added_to_cart'),
                                'redirect' => '/cart'
                            ];
                            return response()->json($toastData);
                        } else {
                            return $this->handleFreeMeetingReservation($deleteMeeting, $userId, $userIdRes, $user, $meeting, $meetingTime, $day, $selectedMeetingType, $studentCount, $selectedSessionType, $is_reverse);
                        }
                    } else {
                        $toastData = [
                            'title' => trans('public.request_failed'),
                            'msg' => trans('meeting.meeting_disabled'),
                            'status' => 'error'
                        ];
                        return response()->json($toastData);
                    }
                }
            }

            $toastData = [
                'title' => trans('public.request_failed'),
                'msg' => trans('meeting.select_time_to_reserve'),
                'status' => 'error'
            ];
            return response()->json($toastData);
        }

        $toastData = [
            'title' => trans('public.request_failed'),
            'msg' => trans('public.not_login_toast_msg_lang'),
            'status' => 'error'
        ];
        return response()->json($toastData);
    }











    private function handleUtcDate($day, $clock, $instructorTimezone)
    {
        $date = $day . ' ' . $clock;

        $utcDate = convertTimeToUTCzone($date, $instructorTimezone);

        return $utcDate->getTimestamp();
    }

    private function handleHourlyMeetingAmount(Meeting $meeting, MeetingTime $meetingTime, $studentCount, $selectedMeetingType)
    {
        if (empty($studentCount)) {
            $studentCount = 1;
        }

        $status = true;
        $hourlyAmount = $meeting->amount;

        if ($selectedMeetingType == 'in_person' and in_array($meetingTime->meeting_type, ['in_person', 'all'])) {
            if ($meeting->in_person) {
                $hourlyAmount = $meeting->in_person_amount;
            } else {
                $toastData = [
                    'status' => 'error',
                    'title' => trans('public.request_failed'),
                    'msg' => trans('update.in_person_meetings_unavailable'),
                ];
                $hourlyAmount = response()->json($toastData);
                $status = false;
            }
        }

        if ($meeting->group_meeting and $status) {
            $types = ['in_person', 'online'];

            foreach ($types as $type) {
                if ($selectedMeetingType == $type and in_array($meetingTime->meeting_type, ['all', $type])) {

                    $meetingMaxVar = $type . '_group_max_student';
                    $meetingMinVar = $type . '_group_min_student';
                    $meetingAmountVar = $type . '_group_amount';

                    if ($studentCount < $meeting->$meetingMinVar) {
                        $hourlyAmount = $hourlyAmount * $studentCount;
                    } else if ($studentCount > $meeting->$meetingMaxVar) {
                        $toastData = [
                            'status' => 'error',
                            'title' => trans('public.request_failed'),
                            'msg' => trans('update.group_meeting_max_student_count_hint', ['max' => $meeting->$meetingMaxVar]),
                        ];
                        $hourlyAmount = response()->json($toastData);
                        $status = false;
                    } else if ($studentCount >= $meeting->$meetingMinVar and $studentCount <= $meeting->$meetingMaxVar) {
                        $hourlyAmount = $meeting->$meetingAmountVar * $studentCount;
                    }
                }
            }
        }

        return [
            'status' => $status,
            'result' => $hourlyAmount
        ];
    }

    private function handleFreeMeetingReservation($deleteMeeting, $userId, $userIdRes, $user, $meeting, $meetingTime, $day, $selectedMeetingType, $studentCount, $selectedSessionType, $is_reverse)
    {
        $dateMeeting = date('d M Y', strtotime($day));
        // @dd($dateMeeting);
        $reserveMeeting = ReserveMeeting::find($deleteMeeting);
        $nameTeacher = User::find($meeting->creator_id);
        $nameLearner = User::find($userId);
        
        $dateMeeting = date('d M Y', strtotime($day));
        
        if (!empty($reserveMeeting)) {
            $oldDateMeeting = date('d M Y', strtotime($reserveMeeting->day));
            // @dd($nameLearner->full_name, $nameTeacher->full_name, $oldDateMeeting);
            
            
            $notifyOptions = [
                '[student.name]' =>  $nameLearner->full_name,
                '[instructor.name]' => $nameTeacher->full_name,
                '[time.date]' => $oldDateMeeting,
                '[link]' => $dateMeeting,
            ];
            sendNotification('meeting_reschedule', $notifyOptions, $userId);
            sendNotification('meeting_reschedule', $notifyOptions, $meeting->creator_id);

            $reserveMeeting->delete();
        
        }
        // @dd( $deleteMeeting);

        
        





        $instructorTimezone = $meeting->getTimezone();
        $explodetime = explode('-', $meetingTime->time);

        $startAt = $this->handleUtcDate($day, $explodetime[0], $instructorTimezone);
        $endAt = $this->handleUtcDate($day, $explodetime[1], $instructorTimezone);

      
        $reserve = ReserveMeeting::updateOrCreate([
            'user_id' => $userId,
            'meeting_time_id' => $meetingTime->id,
            'meeting_id' => $meetingTime->meeting_id,
            'status' => ReserveMeeting::$pending,
            'day' => $day,
            'meeting_type' => $selectedMeetingType,
            'session_type' => $selectedSessionType,
            'mentoring_type' => $is_reverse ? 'reverse' : 'meeting',
            'student_count' => $studentCount
        ], [
            'date' => strtotime($day),
            'start_at' => $startAt,
            'end_at' => $endAt,
            'paid_amount' => 0,
            'discount' => $meetingTime->meeting->discount,
            'created_at' => time(),
        ]);


        if (!empty($reserve)) {
            $sale = Sale::create([
                'buyer_id' => $user->id,
                'seller_id' => $meeting->creator_id,
                'meeting_id' => $meeting->id,
                'type' => Sale::$meeting,
                'payment_method' => Sale::$credit,
                'amount' => 0,
                'total_amount' => 0,
                'created_at' => time(),
            ]);

            if (!empty($sale)) {
                $reserve->update([
                    'sale_id' => $sale->id,
                    'reserved_at' => time()
                ]);
            }
        }

        $toastData = [
            'title' => '',
            'msg' => trans('cart.success_pay_msg_for_free_meeting'),
            'status' => 'success'
            
        ];

        $notifyOptions = [
            '[student.name]' =>  $nameLearner->full_name,
            '[instructor.name]' => $nameTeacher->full_name,
            '[time.date]' => $dateMeeting,
        ];
        sendNotification('new_appointment', $notifyOptions, $userId);
        sendNotification('new_appointment', $notifyOptions, $meeting->creator_id);

        if (empty($reserveMeeting)) {

            $student = Reward::STUDENT_MEETING_RESERVE;
            $instructor = Reward::INSTRUCTOR_MEETING_RESERVE;        
           
            $RewardStudent = RewardAccounting::calculateScore($student);
            $RewarInstructor = RewardAccounting::calculateScore($instructor); 

            RewardAccounting::makeRewardAccounting($userId, $RewardStudent, $student);
            RewardAccounting::makeRewardAccounting($meeting->creator_id, $RewarInstructor, $instructor);

        }

        return response()->json($toastData);
    }
}
