<?php

namespace App\Http\Controllers\Panel;

use App\User;
use App\Models\UpcomingCourse;
use App\Models\Role;
use App\Models\Group;
use App\Models\RewardAccounting;
use App\Http\Controllers\Controller;
use App\Mixins\RegistrationPackage\UserPackage;
use App\Models\Comment;
use App\Models\Gift;
use App\Models\Meeting;
use App\Models\MeetingTime;
use App\Models\ReserveMeeting;
use App\Models\Sale;
use App\Models\Support;
use App\Models\Webinar;
use App\Models\Diary;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\WebinarAssignment;




class DashboardController extends Controller
{

    public function dashboard(Request $request)
    {


        $user = auth()->user();

        $nextBadge = $user->getBadges(true, true);

        $data = [
            'pageTitle' => trans('panel.dashboard'),
            'nextBadge' => $nextBadge
        ];

        if (!$user->isUser()) {

            $meetingIds = Meeting::where('creator_id', $user->id)->pluck('id')->toArray();
            $pendingAppointments = ReserveMeeting::whereIn('meeting_id', $meetingIds)
                ->whereHas('sale')
                //->where('status', ReserveMeeting::$pending) uncomment this line if you want to show pending appointments
                ->count();

            $userWebinarsIds = $user->webinars->pluck('id')->toArray();
            $supports = Support::whereIn('webinar_id', $userWebinarsIds)->where('status', 'open')->get();

            $comments = Comment::whereIn('webinar_id', $userWebinarsIds)
                ->where('status', 'active')
                ->whereNull('viewed_at')
                ->get();

            $time = time();
            $firstDayMonth = strtotime(date('Y-m-01', $time)); // First day of the month.
            $lastDayMonth = strtotime(date('Y-m-t', $time)); // Last day of the month.
            $myWebinarsCount = Webinar::where(function ($query) use ($user) {
                if ($user->isTeacher()) {
                    $query->where('teacher_id', $user->id);
                } elseif ($user->isOrganization()) {
                    $query->where('creator_id', $user->id);
                }
            })->count();

            $courses = Webinar::where(function ($query) use ($user) {
                if ($user->isTeacher()) {
                    $query->where('teacher_id', $user->id);
                } elseif ($user->isOrganization()) {
                    $query->where('creator_id', $user->id);
                } elseif ($user->isUser()) {
                    $query->where('creator_id', $user->id);
                }
            })->get();
            $monthlySales = Sale::where('seller_id', $user->id)
                ->whereNull('refund_at')
                ->whereBetween('created_at', [$firstDayMonth, $lastDayMonth])
                ->get();

            $meetingIds = Meeting::where('creator_id', auth()->user()->id)->pluck('id');


            $coachingMeetingHours = ReserveMeeting::whereIn('meeting_id', $meetingIds)
                ->where('status', ReserveMeeting::$finished)
                ->where('session_type', ReserveMeeting::$coaching);

            $mentoringMeetingHours = ReserveMeeting::whereIn('meeting_id', $meetingIds)
                ->where('status', ReserveMeeting::$finished)
                ->where('session_type', ReserveMeeting::$mentoring);

            $trainingMeetingHours = ReserveMeeting::whereIn('meeting_id', $meetingIds)
                ->where('status', ReserveMeeting::$finished)
                ->where('session_type', ReserveMeeting::$training);

            $reverseMeetingHours = ReserveMeeting::whereIn('meeting_id', $meetingIds)
                ->where('status', ReserveMeeting::$finished)
                ->where('mentoring_type', ReserveMeeting::$reverse);





            function getMeetingsTotalHours(array $meetings)
            {

                $activeHoursCount = 0;
                foreach ($meetings as $meeting) {
                    $activeHoursCount += $meeting['end_at'] - $meeting['start_at'];
                }
                $activeHoursCount = $activeHoursCount / 3600; //convert to hours

                return $activeHoursCount;
            }

            $coachingMeetingHours = getMeetingsTotalHours($coachingMeetingHours->get()->toArray());
            $mentoringMeetingHours = getMeetingsTotalHours($mentoringMeetingHours->get()->toArray());
            $trainingMeetingHours = getMeetingsTotalHours($trainingMeetingHours->get()->toArray());
            $reverseMeetingHours = getMeetingsTotalHours($reverseMeetingHours->get()->toArray());


            $data['coachingMeetingHours'] = $coachingMeetingHours;
            $data['mentoringMeetingHours'] = $mentoringMeetingHours;
            $data['trainingMeetingHours'] = $trainingMeetingHours;
            $data['reverseMeetingHours'] = $reverseMeetingHours;

            $data['pendingAppointments'] = $pendingAppointments;
            $data['supportsCount'] = count($supports);
            $data['commentsCount'] = count($comments);
            $data['monthlySalesCount'] = count($monthlySales) ? $monthlySales->sum('total_amount') : 0;
            $data['myWebinarsCount'] = $myWebinarsCount;
            $data['courses'] = $courses;
            $data['monthlyChart'] = $this->getMonthlySalesOrPurchase($user);
        } else {
            $webinarsIds = $user->getPurchasedCoursesIds();

            $webinars = Webinar::whereIn('id', $webinarsIds)
                ->where('status', 'active')
                ->get();



            $supports = Support::where('user_id', $user->id)
                ->whereNotNull('webinar_id')
                ->where('status', 'open')
                ->get();

            $comments = Comment::where('user_id', $user->id)
                ->whereNotNull('webinar_id')
                ->where('status', 'active')
                ->get();


            $reserveMeetings = ReserveMeeting::where('user_id', $user->id)
                ->whereHas('sale', function ($query) {
                    $query->whereNull('refund_at');
                })
                ->where('status', ReserveMeeting::$open)
                ->get();



            $data['webinarsCount'] = count($webinars);
            $data['supportsCount'] = count($supports);
            $data['commentsCount'] = count($comments);
            $data['reserveMeetingsCount'] = count($reserveMeetings);
            $data['monthlyChart'] = $this->getMonthlySalesOrPurchase($user);
        }

        if ($user->isTeacher()) {

            // // count skills1
            // $skills1Count = Diary::whereHas('user', function ($query) use ($user) {
            //     $query->where('organ_id', $user->organ_id);
            // })
            // ->where('organ_id', $user->organ_id)
            // ->where('skills', 'like', '%Agile Leadership%')
            // ->count();
            // // end skills1

            // // count skills2
            // $skills2Count = Diary::whereHas('user', function ($query) use ($user) {
            //     $query->where('user_id', $user->organ_id);
            // })
            // ->where('organ_id', $user->id)
            // ->where('skills', 'like', '%Analytical Problem Solving & Planing%')
            // ->count();
            // // end skills2

            // // count skills3
            // $skills3Count = Diary::whereHas('user', function ($query) use ($user) {
            //     $query->where('user_id', $user->organ_id);
            // })
            // ->where('organ_id', $user->organ_id)
            // ->where('skills', 'like', '%Developing Capabilities%')
            // ->count();
            // // end skills3

            // // count skills4
            // $skills4Count = Diary::whereHas('user', function ($query) use ($user) {
            //     $query->where('user_id', $user->organ_id);
            // })
            // ->where('organ_id', $user->organ_id)
            // ->where('skills', 'like', '%Driving Digital Innovation%')
            // ->count();
            // // end skills4

            // // count skills5
            // $skills5Count = Diary::whereHas('user', function ($query) use ($user) {
            //     $query->where('user_id', $user->organ_id);
            // })
            // ->where('organ_id', $user->organ_id)
            // ->where('skills', 'like', '%Results Orientation & Execution%')
            // ->count();
            // // end skills5

            // // count skills6
            // $skills6Count = Diary::whereHas('user', function ($query) use ($user) {
            //     $query->where('user_id', $user->organ_id);
            // })
            // ->where('organ_id', $user->organ_id)
            // ->where('skills', 'like', '%Stakeholder Orientation%')
            // ->count();
            // // end skills6

            // // count skills7
            // $skills7Count = Diary::whereHas('user', function ($query) use ($user) {
            //     $query->where('user_id', $user->organ_id);
            // })
            // ->where('organ_id', $user->organ_id)
            // ->where('skills', 'like', '%Strategy & Business Acumen%')
            // ->count();
            // // end skills7

            // // count skills8
            // $skills8Count = Diary::whereHas('user', function ($query) use ($user) {
            //     $query->where('user_id', $user->organ_id);
            // })
            // ->where('organ_id', $user->organ_id)
            // ->where('skills', 'like', '%Synergistic Collaboration%')
            // ->count();
            // // end skills8

            // count skills
            $skills1Count = Diary::where('skills', 'like', '%Agile Leadership%')->count();        
            $skills2Count = Diary::where('skills', 'like', '%Analytical Problem Solving & Planing%')->count();
            $skills3Count = Diary::where('skills', 'like', '%Developing Capabilities%')->count();            
            $skills4Count = Diary::where('skills', 'like', '%Driving Digital Innovation%')->count();            
            $skills5Count = Diary::where('skills', 'like', '%Results Orientation & Execution%')->count();            
            $skills6Count = Diary::where('skills', 'like', '%Stakeholder Orientation%')->count();            
            $skills7Count = Diary::where('skills', 'like', '%Strategy & Business Acumen%')->count();           
            $skills8Count = Diary::where('skills', 'like', '%Synergistic Collaboration%')->count();
            // end skills

           

            $data['skills1Count'] = $skills1Count;
            $data['skills2Count'] = $skills2Count;
            $data['skills3Count'] = $skills3Count;
            $data['skills4Count'] = $skills4Count;
            $data['skills5Count'] = $skills5Count;
            $data['skills6Count'] = $skills6Count;
            $data['skills7Count'] = $skills7Count;
            $data['skills8Count'] = $skills8Count;
        
            
            // end count







            $bestSaleWebinarsIds = Sale::whereNotNull('webinar_id')
                ->select(DB::raw('COUNT(id) as cnt,webinar_id'))
                ->groupBy('webinar_id')
                ->orderBy('cnt', 'DESC')
                ->limit(6)
                ->pluck('webinar_id')
                ->toArray();

            $bestSaleWebinars = Webinar::whereIn('id', $bestSaleWebinarsIds)
                ->where('status', Webinar::$active)
                ->where('private', false)
                ->with([
                    'teacher' => function ($qu) {
                        $qu->select('id', 'full_name', 'avatar');
                    },
                    'reviews' => function ($query) {
                        $query->where('status', 'active');
                    },
                    'sales',
                    'tickets',
                    'feature'
                ])
                ->get();
            $data['bestSaleWebinars'] = $bestSaleWebinars;





            $userr = auth()->user();



            $query = WebinarAssignment::where('creator_id', $userr->id);

            $assignments = $query->with([
                'webinar',
                'instructorAssignmentHistories' => function ($query) use ($user) {
                    $query->where('instructor_id', $user->id);
                },
            ])->orderBy('created_at', 'desc')
                ->paginate(10);

            $data['assignments'] = $assignments;


            // $query = WebinarAssignment::query();
            // $query = $this->handleAssignmentsFilters($request, $query);
            // $assignments = $query->with([
            //     'webinar',
            //     'instructorAssignmentHistories' => function ($query) {
            //         $query->orderBy('created_at', 'desc');
            //         $query->with([
            //             'messages' => function ($query) {
            //                 $query->orderBy('created_at', 'desc');
            //             }
            //         ]);
            //     },
            // ])
            //     ->orderBy('created_at', 'desc')
            //     ->paginate(10);

            // $data['assignments'] = $assignments;

            $currentDate = Carbon::today()->format('d M Y');
            $data['currentDate'] = $currentDate;

            // Top Organization

            // $this->authorize('admin_organizations_list');

            $query = User::where('role_name', Role::$organization);

            $totalOrganizations = deepClone($query)->count();
            $verifiedOrganizations = deepClone($query)->where('verified', true)
                ->count();
            $totalOrganizationsTeachers = User::where('role_name', Role::$teacher)
                ->whereNotNull('organ_id')
                ->count();
            $totalOrganizationsStudents = User::where('role_name', Role::$user)
                ->whereNotNull('organ_id')
                ->count();
            $userGroups = Group::where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->get();


            $query = $this->filters($query, $request);


            $topOrganization = User::where('role_name', Role::$organization)->withCount('getOrganizationStudents')->orderBy('get_organization_students_count', 'desc')->limit(5)->get();
            $topOrganizationChart = [
                "labels" => [],
                "data" => []
            ];
            foreach ($topOrganization as $top) {
                $topOrganizationChart["labels"][] = $top->full_name;
                $topOrganizationChart["data"][] = $top->get_organization_students_count;
            }

            $topOrganizationTeacher = User::where('role_name', Role::$organization)->withCount('getOrganizationTeachers')->orderBy('get_organization_teachers_count', 'desc')->limit(5)->get();
            $topOrganizationChartTeacher = [
                "labels" => [],
                "data" => []
            ];
            foreach ($topOrganizationTeacher as $topTeacher) {
                $topOrganizationChartTeacher["labels"][] = $topTeacher->full_name;
                $topOrganizationChartTeacher["data"][] = $topTeacher->get_organization_teachers_count;
            }

            $data['topOrganizationChart'] = $topOrganizationChart;
            $data['topOrganizationChartTeacher'] = $topOrganizationChartTeacher;

            // end top organization


           
            // $kursusCount = Sale::where('seller_id', $userr->id)->count();
            $kursusCount = Sale::where('seller_id', $userr->id)
            ->distinct('buyer_id')
            ->count();


            $meetingIds = Meeting::where('creator_id', auth()->user()->id)->pluck('id');

            $reserveMeetingsQuery = ReserveMeeting::whereIn('meeting_id', $meetingIds)
                ->where(function ($query) {
                    $query->whereHas('sale', function ($query) {
                        $query->whereNull('refund_at');
                    });
    
                    $query->orWhere(function ($query) {
                        $query->whereIn('status', ['canceled']);
                        $query->whereHas('sale');
                    });
                });
            $userIdsReservedTime = deepClone($reserveMeetingsQuery)->pluck('user_id')->toArray();
            $usersReservedCount = User::whereIn('id', array_unique($userIdsReservedTime))->count();

            $totalCount = Sale::where('buyer_id', $user->id)->count() +
            User::whereIn('id', array_unique($userIdsReservedTime))->count();

            // $totalCount sekarang berisi jumlah kedua nilai dengan ID yang tidak sama


            $data['kursusCount'] = $kursusCount;
            $data['usersReservedCount'] = $usersReservedCount;
            $data['totalCount'] = $totalCount;


            $mostPointsUsers = RewardAccounting::selectRaw('*, sum(score) as total_points')
                ->groupBy('user_id')
                ->whereHas('user', function ($query) {
                    $query->where('role_name', 'user');
                })
                ->with([
                    'user'
                ])
                ->orderBy('total_points', 'desc')
                ->limit(4)
                ->get();
            $mostPointsTeachers = RewardAccounting::selectRaw('*, sum(score) as total_points')
                ->groupBy('user_id')
                ->whereHas('user', function ($query) {
                    $query->where('role_name', 'teacher');
                })
                ->with([
                    'user'
                ])
                ->orderBy('total_points', 'desc')
                ->limit(4)
                ->get();


            $data['mostPointsUsers'] = $mostPointsUsers;
            $data['mostPointsTeachers'] = $mostPointsTeachers;



        }







        if ($user->isUser()) {


            // count skills1
            $skills1Count = Diary::whereHas('user', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('user_id', $user->id)
            ->where('skills', 'like', '%Agile Leadership%')
            ->count();
            // end skills1

            // count skills2
            $skills2Count = Diary::whereHas('user', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('user_id', $user->id)
            ->where('skills', 'like', '%Analytical Problem Solving & Planing%')
            ->count();
            // end skills2

            // count skills3
            $skills3Count = Diary::whereHas('user', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('user_id', $user->id)
            ->where('skills', 'like', '%Developing Capabilities%')
            ->count();
            // end skills3

            // count skills4
            $skills4Count = Diary::whereHas('user', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('user_id', $user->id)
            ->where('skills', 'like', '%Driving Digital Innovation%')
            ->count();
            // end skills4

            // count skills5
            $skills5Count = Diary::whereHas('user', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('user_id', $user->id)
            ->where('skills', 'like', '%Results Orientation & Execution%')
            ->count();
            // end skills5

            // count skills6
            $skills6Count = Diary::whereHas('user', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('user_id', $user->id)
            ->where('skills', 'like', '%Stakeholder Orientation%')
            ->count();
            // end skills6

            // count skills7
            $skills7Count = Diary::whereHas('user', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('user_id', $user->id)
            ->where('skills', 'like', '%Strategy & Business Acumen%')
            ->count();
            // end skills7

            // count skills8
            $skills8Count = Diary::whereHas('user', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('user_id', $user->id)
            ->where('skills', 'like', '%Synergistic Collaboration%')
            ->count();
            // end skills8

            $data['skills1Count'] = $skills1Count;
            $data['skills2Count'] = $skills2Count;
            $data['skills3Count'] = $skills3Count;
            $data['skills4Count'] = $skills4Count;
            $data['skills5Count'] = $skills5Count;
            $data['skills6Count'] = $skills6Count;
            $data['skills7Count'] = $skills7Count;
            $data['skills8Count'] = $skills8Count;
        
            
            // end count






            $bestSaleWebinarsIds = Sale::whereNotNull('webinar_id')
                ->select(DB::raw('COUNT(id) as cnt,webinar_id'))
                ->groupBy('webinar_id')
                ->orderBy('cnt', 'DESC')
                ->limit(6)
                ->pluck('webinar_id')
                ->toArray();

            $bestSaleWebinars = Webinar::whereIn('id', $bestSaleWebinarsIds)
                ->where('status', Webinar::$active)
                ->where('private', false)
                ->with([
                    'teacher' => function ($qu) {
                        $qu->select('id', 'full_name', 'avatar');
                    },
                    'reviews' => function ($query) {
                        $query->where('status', 'active');
                    },
                    'sales',
                    'tickets',
                    'feature'
                ])
                ->get();
            $data['bestSaleWebinars'] = $bestSaleWebinars;

            $query = WebinarAssignment::query();
            $query = $this->handleAssignmentsFilters($request, $query);
            $assignments = $query->with([
                'webinar',
                'instructorAssignmentHistories' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                    $query->with([
                        'messages' => function ($query) {
                            $query->orderBy('created_at', 'desc');
                        }
                    ]);
                },
            ])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $data['assignments'] = $assignments;

            $currentDate = Carbon::today()->format('d M Y');
            $data['currentDate'] = $currentDate;

            // count user organization
            $getTeacher = $user->getOrganizationTeachers();
            $teacher = $getTeacher->orderBy('created_at', 'desc')
                ->paginate(10);
            $data['teacher'] = $teacher;
            // counting learner
            $getlearner = $user->getOrganizationStudents();
            $learner = $getlearner->orderBy('created_at', 'desc')
                ->paginate(10);
            $data['learner'] = $learner;
            // end count
       
            





            $mostPointsUsers = RewardAccounting::selectRaw('*, sum(score) as total_points')
                ->groupBy('user_id')
                ->whereHas('user', function ($query) {
                    $query->where('role_name', 'user');
                })
                ->with([
                    'user'
                ])
                ->orderBy('total_points', 'desc')
                ->limit(4)
                ->get();
            $mostPointsTeachers = RewardAccounting::selectRaw('*, sum(score) as total_points')
                ->groupBy('user_id')
                ->whereHas('user', function ($query) {
                    $query->where('role_name', 'teacher');
                })
                ->with([
                    'user'
                ])
                ->orderBy('total_points', 'desc')
                ->limit(4)
                ->get();


            $data['mostPointsUsers'] = $mostPointsUsers;
            $data['mostPointsTeachers'] = $mostPointsTeachers;


            //total hour session
            $activeMeetingTimeIds = ReserveMeeting::where('user_id', $user->id)
            ->whereHas('sale', function ($query) {
                $query->whereNull('refund_at');
            })
            ->pluck('meeting_time_id');
            $activeMeetingTimes = MeetingTime::whereIn('id', $activeMeetingTimeIds)->get();
            $timeTable = array_count_values($activeMeetingTimeIds->toArray());
            $activeHoursCount = 0;
            foreach ($activeMeetingTimes as $time) {
            $explodetime = explode('-', $time->time);
            $hours = strtotime($explodetime[1]) - strtotime($explodetime[0]);
            $howMany = $timeTable[$time->id];
            $activeHoursCount += $hours * $howMany;
        }
            $data['activeHoursCount'] = round($activeHoursCount / 3600, 2);
// end total hour session

        }

        if ($user->isOrganization()) {
            // // count skills1
            //  $skills1Count = Diary::whereHas('user', function ($query) use ($user) {
            //     $query->where('organ_id', $user->id);
            // })
            // ->where('organ_id', $user->id)
            // ->where('skills', 'like', '%Agile Leadership%')
            // ->count();
            // // end skills1

            // // count skills2
            // $skills2Count = Diary::whereHas('user', function ($query) use ($user) {
            //     $query->where('organ_id', $user->id);
            // })
            // ->where('organ_id', $user->id)
            // ->where('skills', 'like', '%Analytical Problem Solving & Planing%')
            // ->count();
            // // end skills2

            // // count skills3
            // $skills3Count = Diary::whereHas('user', function ($query) use ($user) {
            //     $query->where('organ_id', $user->id);
            // })
            // ->where('organ_id', $user->id)
            // ->where('skills', 'like', '%Developing Capabilities%')
            // ->count();
            // // end skills3

            // // count skills4
            // $skills4Count = Diary::whereHas('user', function ($query) use ($user) {
            //     $query->where('organ_id', $user->id);
            // })
            // ->where('organ_id', $user->id)
            // ->where('skills', 'like', '%Driving Digital Innovation%')
            // ->count();
            // // end skills4

            // // count skills5
            // $skills5Count = Diary::whereHas('user', function ($query) use ($user) {
            //     $query->where('organ_id', $user->id);
            // })
            // ->where('organ_id', $user->id)
            // ->where('skills', 'like', '%Results Orientation & Execution%')
            // ->count();
            // // end skills5

            // // count skills6
            // $skills6Count = Diary::whereHas('user', function ($query) use ($user) {
            //     $query->where('organ_id', $user->id);
            // })
            // ->where('organ_id', $user->id)
            // ->where('skills', 'like', '%Stakeholder Orientation%')
            // ->count();
            // // end skills6

            // // count skills7
            // $skills7Count = Diary::whereHas('user', function ($query) use ($user) {
            //     $query->where('organ_id', $user->id);
            // })
            // ->where('organ_id', $user->id)
            // ->where('skills', 'like', '%Strategy & Business Acumen%')
            // ->count();
            // // end skills7

            // // count skills8
            // $skills8Count = Diary::whereHas('user', function ($query) use ($user) {
            //     $query->where('organ_id', $user->id);
            // })
            // ->where('organ_id', $user->id)
            // ->where('skills', 'like', '%Synergistic Collaboration%')
            // ->count();
            // // end skills8

            // count skills
            $skills1Count = Diary::where('skills', 'like', '%Agile Leadership%')->count();        
            $skills2Count = Diary::where('skills', 'like', '%Analytical Problem Solving & Planing%')->count();
            $skills3Count = Diary::where('skills', 'like', '%Developing Capabilities%')->count();            
            $skills4Count = Diary::where('skills', 'like', '%Driving Digital Innovation%')->count();            
            $skills5Count = Diary::where('skills', 'like', '%Results Orientation & Execution%')->count();            
            $skills6Count = Diary::where('skills', 'like', '%Stakeholder Orientation%')->count();            
            $skills7Count = Diary::where('skills', 'like', '%Strategy & Business Acumen%')->count();           
            $skills8Count = Diary::where('skills', 'like', '%Synergistic Collaboration%')->count();
            // end skills

            $data['skills1Count'] = $skills1Count;
            $data['skills2Count'] = $skills2Count;
            $data['skills3Count'] = $skills3Count;
            $data['skills4Count'] = $skills4Count;
            $data['skills5Count'] = $skills5Count;
            $data['skills6Count'] = $skills6Count;
            $data['skills7Count'] = $skills7Count;
            $data['skills8Count'] = $skills8Count;
        
            
            // end count


            $bestSaleWebinarsIds = Sale::whereNotNull('webinar_id')
                ->select(DB::raw('COUNT(id) as cnt,webinar_id'))
                ->groupBy('webinar_id')
                ->orderBy('cnt', 'DESC')
                ->limit(6)
                ->pluck('webinar_id')
                ->toArray();

            $bestSaleWebinars = Webinar::whereIn('id', $bestSaleWebinarsIds)
                ->where('status', Webinar::$active)
                ->where('private', false)
                ->with([
                    'teacher' => function ($qu) {
                        $qu->select('id', 'full_name', 'avatar');
                    },
                    'reviews' => function ($query) {
                        $query->where('status', 'active');
                    },
                    'sales',
                    'tickets',
                    'feature'
                ])
                ->get();
            $data['bestSaleWebinars'] = $bestSaleWebinars;





            $query = WebinarAssignment::query();
            $query = $this->handleAssignmentsFilters($request, $query);
            $assignments = $query->with([
                'webinar',
                'instructorAssignmentHistories' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                    $query->with([
                        'messages' => function ($query) {
                            $query->orderBy('created_at', 'desc');
                        }
                    ]);
                },
            ])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $data['assignments'] = $assignments;

            $currentDate = Carbon::today()->format('d M Y');
            $data['currentDate'] = $currentDate;


            $getTeacher = $user->getOrganizationTeachers();
            $teacher = $getTeacher->orderBy('created_at', 'desc')
                ->paginate(10);
            $data['teacher'] = $teacher;
            // counting learner
            $getlearner = $user->getOrganizationStudents();
            $learner = $getlearner->orderBy('created_at', 'desc')
                ->paginate(10);
            $data['learner'] = $learner;
        } else {


            $bestSaleWebinarsIds = Sale::whereNotNull('webinar_id')
                ->select(DB::raw('COUNT(id) as cnt,webinar_id'))
                ->groupBy('webinar_id')
                ->orderBy('cnt', 'DESC')
                ->limit(6)
                ->pluck('webinar_id')
                ->toArray();

            $bestSaleWebinars = Webinar::whereIn('id', $bestSaleWebinarsIds)
                ->where('status', Webinar::$active)
                ->where('private', false)
                ->with([
                    'teacher' => function ($qu) {
                        $qu->select('id', 'full_name', 'avatar');
                    },
                    'reviews' => function ($query) {
                        $query->where('status', 'active');
                    },
                    'sales',
                    'tickets',
                    'feature'
                ])
                ->get();

            $upcomingCourses = UpcomingCourse::where('status', Webinar::$active)
                ->orderBy('created_at', 'desc')
                ->with([
                    'teacher' => function ($qu) {
                        $qu->select('id', 'full_name', 'avatar');
                    }
                ])
                ->limit(6)
                ->get();

            $data['upcomingCourses'] = $upcomingCourses;
            $data['bestSaleWebinars'] = $bestSaleWebinars;





            $query = WebinarAssignment::query();
            $query = $this->handleAssignmentsFilters($request, $query);
            $assignments = $query->with([
                'webinar',
                'instructorAssignmentHistories' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                    $query->with([
                        'messages' => function ($query) {
                            $query->orderBy('created_at', 'desc');
                        }
                    ]);
                },
            ])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $data['assignments'] = $assignments;

            $currentDate = Carbon::today()->format('d M Y');
            $data['currentDate'] = $currentDate;


            $getTeacher = $user->getOrganizationTeachers();
            $teacher = $getTeacher->orderBy('created_at', 'desc')
                ->paginate(10);
            $data['teacher'] = $teacher;
            // counting learner
            $getlearner = $user->getOrganizationStudents();
            $learner = $getlearner->orderBy('created_at', 'desc')
                ->paginate(10);
            $data['learner'] = $learner;

            $giftsIds = Gift::query()->where('email', $user->email)
                ->where('status', 'active')
                ->whereNull('product_id')
                ->where(function ($query) {
                    $query->whereNull('date');
                    $query->orWhere('date', '<', time());
                })
                ->whereHas('sale')
                ->pluck('id')
                ->toArray();

            $query = Sale::query()
                ->where(function ($query) use ($user, $giftsIds) {
                    $query->where('sales.buyer_id', $user->id);
                    $query->orWhereIn('sales.gift_id', $giftsIds);
                })
                ->whereNull('sales.refund_at')
                ->where('access_to_purchased_item', true)
                ->where(function ($query) {
                    $query->where(function ($query) {
                        $query->whereNotNull('sales.webinar_id')
                            ->where('sales.type', 'webinar')
                            ->whereHas('webinar', function ($query) {
                                $query->where('status', 'active');
                            });
                    });
                    $query->orWhere(function ($query) {
                        $query->whereNotNull('sales.bundle_id')
                            ->where('sales.type', 'bundle')
                            ->whereHas('bundle', function ($query) {
                                $query->where('status', 'active');
                            });
                    });
                    $query->orWhere(function ($query) {
                        $query->whereNotNull('gift_id');
                        $query->whereHas('gift');
                    });
                });


            $sales = deepClone($query)
                ->with([
                    'webinar' => function ($query) {
                        $query->with([
                            'files',
                            'reviews' => function ($query) {
                                $query->where('status', 'active');
                            },
                            'category',
                            'teacher' => function ($query) {
                                $query->select('id', 'full_name');
                            },
                        ]);
                        $query->withCount([
                            'sales' => function ($query) {
                                $query->whereNull('refund_at');
                            }
                        ]);
                    },
                    'bundle' => function ($query) {
                        $query->with([
                            'reviews' => function ($query) {
                                $query->where('status', 'active');
                            },
                            'category',
                            'teacher' => function ($query) {
                                $query->select('id', 'full_name');
                            },
                        ]);
                    }
                ])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $time = time();

            $giftDurations = 0;
            $giftUpcoming = 0;
            $giftPurchasedCount = 0;

            foreach ($sales as $sale) {
                if (!empty($sale->gift_id)) {
                    $gift = $sale->gift;

                    $sale->webinar_id = $gift->webinar_id;
                    $sale->bundle_id = $gift->bundle_id;

                    $sale->webinar = !empty($gift->webinar_id) ? $gift->webinar : null;
                    $sale->bundle = !empty($gift->bundle_id) ? $gift->bundle : null;

                    $sale->gift_recipient = !empty($gift->receipt) ? $gift->receipt->full_name : $gift->name;
                    $sale->gift_sender = $sale->buyer->full_name;
                    $sale->gift_date = $gift->date;;

                    $giftPurchasedCount += 1;

                    if (!empty($sale->webinar)) {
                        $giftDurations += $sale->webinar->duration;

                        if ($sale->webinar->start_date > $time) {
                            $giftUpcoming += 1;
                        }
                    }

                    if (!empty($sale->bundle)) {
                        $bundleWebinars = $sale->bundle->bundleWebinars;

                        foreach ($bundleWebinars as $bundleWebinar) {
                            $giftDurations += $bundleWebinar->webinar->duration;
                        }
                    }
                }
            }

            $purchasedCount = deepClone($query)
                ->where(function ($query) {
                    $query->whereHas('webinar');
                    $query->orWhereHas('bundle');
                })
                ->count();

            $webinarsHours = deepClone($query)->join('webinars', 'webinars.id', 'sales.webinar_id')
                ->select(DB::raw('sum(webinars.duration) as duration'))
                ->sum('duration');
            $bundlesHours = deepClone($query)->join('bundle_webinars', 'bundle_webinars.bundle_id', 'sales.bundle_id')
                ->join('webinars', 'webinars.id', 'bundle_webinars.webinar_id')
                ->select(DB::raw('sum(webinars.duration) as duration'))
                ->sum('duration');

            $hours = $webinarsHours + $bundlesHours + $giftDurations;

            $upComing = deepClone($query)->join('webinars', 'webinars.id', 'sales.webinar_id')
                ->where('webinars.start_date', '>', $time)
                ->count();

            $data['purchasedCount'] = $purchasedCount;
            $data['sales'] = $sales;
            $data['webinarsHours'] = $webinarsHours;
        }


        $data['giftModal'] = $this->showGiftModal($user);

        return view(getTemplate() . '.panel.dashboard.index', $data);
    }



    private function addUsersExtraInfo($users)
    {
        foreach ($users as $user) {
            $salesQuery = Sale::where('seller_id', $user->id)
                ->whereNull('refund_at');

            $classesSaleQuery = deepClone($salesQuery)->whereNotNull('webinar_id')
                ->whereNull('meeting_id')
                ->whereNull('promotion_id')
                ->whereNull('subscribe_id');

            $user->classesSalesCount = $classesSaleQuery->count();
            $user->classesSalesSum = $classesSaleQuery->sum('total_amount');

            $meetingIds = Meeting::where('creator_id', $user->id)->pluck('id');
            $reserveMeetingsQuery = ReserveMeeting::whereIn('meeting_id', $meetingIds)
                ->where(function ($query) {
                    $query->whereHas('sale', function ($query) {
                        $query->whereNull('refund_at');
                    });

                    $query->orWhere(function ($query) {
                        $query->whereIn('status', ['canceled']);
                        $query->whereHas('sale');
                    });
                });

            $user->meetingsSalesCount = deepClone($reserveMeetingsQuery)->count();
            $user->meetingsSalesSum = deepClone($reserveMeetingsQuery)->sum('paid_amount');


            $purchasedQuery = Sale::where('buyer_id', $user->id)
                ->whereNull('refund_at');

            $classesPurchasedQuery = deepClone($purchasedQuery)->whereNotNull('webinar_id')
                ->whereNull('meeting_id')
                ->whereNull('promotion_id')
                ->whereNull('subscribe_id');

            $user->classesPurchasedsCount = $classesPurchasedQuery->count();
            $user->classesPurchasedsSum = $classesPurchasedQuery->sum('total_amount');

            $meetingsPurchasedQuery = deepClone($purchasedQuery)->whereNotNull('meeting_id')
                ->whereNull('webinar_id')
                ->whereNull('promotion_id')
                ->whereNull('subscribe_id');

            $user->meetingsPurchasedsCount = $meetingsPurchasedQuery->count();
            $user->meetingsPurchasedsSum = $meetingsPurchasedQuery->sum('total_amount');
        }

        return $users;
    }
    private function filters($query, $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $full_name = $request->get('full_name');
        $sort = $request->get('sort');
        $group_id = $request->get('group_id');
        $status = $request->get('status');
        $role_id = $request->get('role_id');
        $organization_id = $request->get('organization_id');

        $query = fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($full_name)) {
            $query->where('full_name', 'like', "%$full_name%");
        }

        if (!empty($sort)) {
            switch ($sort) {
                case 'sales_classes_asc':
                    $query->join('sales', 'users.id', '=', 'sales.seller_id')
                        ->select('users.*', 'sales.seller_id', 'sales.webinar_id', 'sales.refund_at', DB::raw('count(sales.seller_id) as sales_count'))
                        ->whereNotNull('sales.webinar_id')
                        ->whereNull('sales.refund_at')
                        ->groupBy('sales.seller_id')
                        ->orderBy('sales_count', 'asc');
                    break;
                case 'sales_classes_desc':
                    $query->join('sales', 'users.id', '=', 'sales.seller_id')
                        ->select('users.*', 'sales.seller_id', 'sales.webinar_id', 'sales.refund_at', DB::raw('count(sales.seller_id) as sales_count'))
                        ->whereNotNull('sales.webinar_id')
                        ->whereNull('sales.refund_at')
                        ->groupBy('sales.seller_id')
                        ->orderBy('sales_count', 'desc');
                    break;
                case 'purchased_classes_asc':
                    $query->join('sales', 'users.id', '=', 'sales.buyer_id')
                        ->select('users.*', 'sales.buyer_id', 'sales.refund_at', DB::raw('count(sales.buyer_id) as purchased_count'))
                        ->whereNull('sales.refund_at')
                        ->groupBy('sales.buyer_id')
                        ->orderBy('purchased_count', 'asc');
                    break;
                case 'purchased_classes_desc':
                    $query->join('sales', 'users.id', '=', 'sales.buyer_id')
                        ->select('users.*', 'sales.buyer_id', 'sales.refund_at', DB::raw('count(sales.buyer_id) as purchased_count'))
                        ->groupBy('sales.buyer_id')
                        ->whereNull('sales.refund_at')
                        ->orderBy('purchased_count', 'desc');
                    break;
                case 'purchased_classes_amount_asc':
                    $query->join('sales', 'users.id', '=', 'sales.buyer_id')
                        ->select('users.*', 'sales.buyer_id', 'sales.amount', 'sales.refund_at', DB::raw('sum(sales.amount) as purchased_amount'))
                        ->groupBy('sales.buyer_id')
                        ->whereNull('sales.refund_at')
                        ->orderBy('purchased_amount', 'asc');
                    break;
                case 'purchased_classes_amount_desc':
                    $query->join('sales', 'users.id', '=', 'sales.buyer_id')
                        ->select('users.*', 'sales.buyer_id', 'sales.amount', 'sales.refund_at', DB::raw('sum(sales.amount) as purchased_amount'))
                        ->groupBy('sales.buyer_id')
                        ->whereNull('sales.refund_at')
                        ->orderBy('purchased_amount', 'desc');
                    break;
                case 'sales_appointments_asc':
                    $query->join('sales', 'users.id', '=', 'sales.seller_id')
                        ->select('users.*', 'sales.seller_id', 'sales.meeting_id', 'sales.refund_at', DB::raw('count(sales.seller_id) as sales_count'))
                        ->whereNotNull('sales.meeting_id')
                        ->whereNull('sales.refund_at')
                        ->groupBy('sales.seller_id')
                        ->orderBy('sales_count', 'asc');
                    break;
                case 'sales_appointments_desc':
                    $query->join('sales', 'users.id', '=', 'sales.seller_id')
                        ->select('users.*', 'sales.seller_id', 'sales.meeting_id', 'sales.refund_at', DB::raw('count(sales.seller_id) as sales_count'))
                        ->whereNotNull('sales.meeting_id')
                        ->whereNull('sales.refund_at')
                        ->groupBy('sales.seller_id')
                        ->orderBy('sales_count', 'desc');
                    break;
                    break;
                case 'purchased_appointments_asc':
                    $query->join('sales', 'users.id', '=', 'sales.buyer_id')
                        ->select('users.*', 'sales.buyer_id', 'sales.meeting_id', 'sales.refund_at', DB::raw('count(sales.buyer_id) as purchased_count'))
                        ->whereNotNull('sales.meeting_id')
                        ->whereNull('sales.refund_at')
                        ->groupBy('sales.buyer_id')
                        ->orderBy('purchased_count', 'asc');
                    break;
                case 'purchased_appointments_desc':
                    $query->join('sales', 'users.id', '=', 'sales.buyer_id')
                        ->select('users.*', 'sales.buyer_id', 'sales.meeting_id', 'sales.refund_at', DB::raw('count(sales.buyer_id) as purchased_count'))
                        ->whereNotNull('sales.meeting_id')
                        ->whereNull('sales.refund_at')
                        ->groupBy('sales.buyer_id')
                        ->orderBy('purchased_count', 'desc');
                    break;
                case 'purchased_appointments_amount_asc':
                    $query->join('sales', 'users.id', '=', 'sales.buyer_id')
                        ->select('users.*', 'sales.buyer_id', 'sales.amount', 'sales.meeting_id', 'sales.refund_at', DB::raw('sum(sales.amount) as purchased_amount'))
                        ->whereNotNull('sales.meeting_id')
                        ->whereNull('sales.refund_at')
                        ->groupBy('sales.buyer_id')
                        ->orderBy('purchased_amount', 'asc');
                    break;
                case 'purchased_appointments_amount_desc':
                    $query->join('sales', 'users.id', '=', 'sales.buyer_id')
                        ->select('users.*', 'sales.buyer_id', 'sales.amount', 'sales.meeting_id', 'sales.refund_at', DB::raw('sum(sales.amount) as purchased_amount'))
                        ->whereNotNull('sales.meeting_id')
                        ->whereNull('sales.refund_at')
                        ->groupBy('sales.buyer_id')
                        ->orderBy('purchased_amount', 'desc');
                    break;
                case 'register_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'register_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        }

        if (!empty($group_id)) {
            $userIds = GroupUser::where('group_id', $group_id)->pluck('user_id')->toArray();

            $query->whereIn('id', $userIds);
        }

        if (!empty($status)) {
            switch ($status) {
                case 'active_verified':
                    $query->where('status', 'active')
                        ->where('verified', true);
                    break;
                case 'active_notVerified':
                    $query->where('status', 'active')
                        ->where('verified', false);
                    break;
                case 'inactive':
                    $query->where('status', 'inactive');
                    break;
                case 'ban':
                    $query->where('ban', true)
                        ->whereNotNull('ban_end_at')
                        ->where('ban_end_at', '>', time());
                    break;
            }
        }

        if (!empty($role_id)) {
            $query->where('role_id', $role_id);
        }

        if (!empty($organization_id)) {
            $query->where('organ_id', $organization_id);
        }

        //dd($query->get());
        return $query;
    }

    private function handleAssignmentsFilters(Request $request, $query)
    {
        $from = $request->get('from', null);
        $to = $request->get('to', null);
        $webinar_ids = $request->get('webinar_ids');
        $student_ids = $request->get('student_ids');
        $status = $request->get('status', null);

        $query = fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($webinar_ids)) {
            $query->whereIn('webinar_id', $webinar_ids);
        }

        if (!empty($student_ids)) {
            $query->whereIn('student_id', $student_ids);
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        return $query;
    }

    private function showGiftModal($user)
    {
        $gift = Gift::query()->where('email', $user->email)
            ->where('status', 'active')
            ->where('viewed', false)
            ->where(function ($query) {
                $query->whereNull('date');
                $query->orWhere('date', '<', time());
            })
            ->whereHas('sale')
            ->first();

        if (!empty($gift)) {
            $gift->update([
                'viewed' => true
            ]);

            $data = [
                'gift' => $gift
            ];

            $result = (string)view()->make('web.default.panel.dashboard.gift_modal', $data);
            $result = str_replace(array("\r\n", "\n", "  "), '', $result);

            return $result;
        }

        return null;
    }

    private function getMonthlySalesOrPurchase($user)
    {
        $months = [];
        $data = [];

        // all 12 months
        for ($month = 1; $month <= 12; $month++) {
            $date = Carbon::create(date('Y'), $month);

            $start_date = $date->timestamp;
            $end_date = $date->copy()->endOfMonth()->timestamp;

            $months[] = trans('panel.month_' . $month);

            if (!$user->isUser()) {
                $monthlySales = Sale::where('seller_id', $user->id)
                    ->whereNull('refund_at')
                    ->whereBetween('created_at', [$start_date, $end_date])
                    ->sum('total_amount');

                $data[] = round($monthlySales, 2);
            } else {
                $monthlyPurchase = Sale::where('buyer_id', $user->id)
                    ->whereNull('refund_at')
                    ->whereBetween('created_at', [$start_date, $end_date])
                    ->count();

                $data[] = $monthlyPurchase;
            }
        }

        return [
            'months' => $months,
            'data' => $data
        ];
    }
}
