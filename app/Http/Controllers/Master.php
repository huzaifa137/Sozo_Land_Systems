<?php
namespace App\Http\Controllers;

use Alert;
use App\Models\AdminRegister;
use App\Models\agreement;
use App\Models\buyer;
use App\Models\Estate;
use App\Models\expenditure;
use App\Models\expenditure_service;
use App\Models\house;
use App\Models\houseBuyer;
use App\Models\pdf_agreements;
use App\Models\pdf_receipt;
use App\Models\plot;
use App\Models\reciept;
use App\Models\resale;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Master extends Controller
{
    public function login(Request $request)
    {

        $ipAddress = $request->ip();
        $user_registered_ip = '127.0.0.1';

        return view('Login.login', compact(['user_registered_ip']));
    }

    public function reload_captcha()
    {

        return response()->json(['captcha' => captcha_img('flat')]);
    }

    public function register()
    {

        $admin_category = Session('LoggedAdmin');

        if ($admin_category == 'Admin') {
            return back()->with('error', 'Only Super Admins can Add more Admins');
        } else if ($admin_category == null) {

            return back()->with('error', 'Only Super Admins can Add more Admins');
        }

        return view('Login.register');
    }

    public function admin_register_data(Request $request)
    {

        $request->validate([
            'username' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email',
            'admin_category' => 'required',
            'password' => 'required',
            'confirm_password' => 'required',
        ]);

        $original_pass = $request->password;
        $confirm_pass = $request->confirm_password;

        if ($original_pass === $confirm_pass) {

            $register_admin = new AdminRegister;

            $register_admin->username = $request->username;
            $register_admin->firstname = $request->firstname;
            $register_admin->lastname = $request->lastname;
            $register_admin->email = $request->email;
            $register_admin->password = Hash::make($request->password);
            $register_admin->admin_category = $request->admin_category;
            $register_admin->added_by = Session('LoggedAdmin');

            $save = $register_admin->save();

            if ($save) {

                return back()->with('success', 'New User has been added to the Sozo Land Systems');
            }

        } else {
            return back()->with('fail', 'Password is not the same as confirm password')->withInput();
        }
    }

    public function admin_check(Request $request)
    {

        $user_registered_ip = $request->user_registered_ip;
        $device_ip = $request->ip();

        if ($user_registered_ip == null) {
            $request->validate([
                'email' => 'required',
                'password' => 'required',
                // 'captcha' => 'required|captcha',
            ]);

            $AdminEmail = AdminRegister::where('email', '=', $request->email)->first();

            if (!$AdminEmail) {
                return back()->with('fail', 'We dont recognise the above email or password');
            } else {

                if (Hash::check($request->password, $AdminEmail->password)) {

                    $user_category = $AdminEmail->admin_category;

                    if ($user_category == 'SuperAdmin') {
                        DB::table('admin_registers')->where('id', $AdminEmail->id)->update(['ip_address' => $device_ip]);
                    }

                    $request->session()->put('LoggedAdmin', $AdminEmail->id);

                    return redirect('admin-dashboard');
                } else {
                    return back()->with('fail', 'incorrect email or password');
                }
            }
        } else {
            $request->validate([
                'email' => 'required',
                'password' => 'required',
            ]);

            $AdminEmail = AdminRegister::where('email', '=', $request->email)->first();

            if (!$AdminEmail) {
                return back()->with('fail', 'We dont recognise the above email or password');
            } else {

                if (Hash::check($request->password, $AdminEmail->password)) {

                    $user_category = $AdminEmail->admin_category;

                    $request->session()->put('LoggedAdmin', $AdminEmail->id);

                    return redirect('admin-dashboard');
                } else {
                    return back()->with('fail', 'incorrect email or password');
                }
            }
        }
    }

    public function logout()
    {
        if (session()->has('LoggedAdmin')) {
            session()->pull('LoggedAdmin');
            return redirect('/');
        }
    }

    public function dashboard()
    {

        $all_sales = Buyer::orderBy('created_at', 'desc')->paginate(10);

        $currentDate = Carbon::today();
        $totalAmount = buyer::whereDate('created_at', $currentDate)->sum('amount_payed');

        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $totalweekSales = buyer::whereBetween('created_at', [$startOfWeek, $endOfWeek])->sum('amount_payed');

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $totalmonthSales = buyer::whereYear('created_at', $currentYear)->whereMonth('created_at', $currentMonth)
            ->sum('amount_payed');

        $plots_fully_paid = DB::table('buyers')->where('next_installment_pay', '=', "Fully payed")->count();
        $under_payment = DB::table('buyers')->where('next_installment_pay', '!=', "Fully payed")->where('next_installment_pay', '!=', "Resold")->count();

        $amount_in_debts = buyer::sum('balance');

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        $paid_not_in_cash = Resale::orderBy('id', 'desc')
            ->paginate(10);

        $User_access_right = $this->user_right_info();

        if ($User_access_right == 'SuperAdmin' || $User_access_right == 'Admin') {
            return view('Admin.dashboard', $data, compact([
                'all_sales',
                'totalAmount',
                'totalweekSales',
                'totalmonthSales'
                ,
                'plots_fully_paid',
                'under_payment',
                'amount_in_debts',
                'User_access_right',
                'paid_not_in_cash'
            ]));
        } else {
            return redirect('estates');
        }
    }

    public function admin_buyer()
    {

        $estates = Estate::all();
        $plots = DB::table('plots')->where('status', '!=', "Fully payed")->get();

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        $User_access_right = $this->user_right_info();

        if ($User_access_right == 'SuperAdmin' || $User_access_right == 'Admin') {
            return view('Admin.buyer', $data, compact(['estates', 'plots']));
        } else {
            return redirect('estates');
        }
    }


    public function store_buyer_details(Request $request)
    {
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->format('Y/m/d');

        // --- Uploads ---
        $frontFile = $request->file('national_id_front');
        $backFile = $request->file('national_id_back');
        $profileFile = $request->file('profile_pic');

        $frontName = $frontFile ? date('YmdHi') . $frontFile->getClientOriginalName() : null;
        $backName = $backFile ? date('YmdHi') . $backFile->getClientOriginalName() : null;
        $profileName = $profileFile ? date('YmdHi') . $profileFile->getClientOriginalName() : null;

        if ($frontFile)
            $frontFile->move(public_path('public/national_id'), $frontName);
        if ($backFile)
            $backFile->move(public_path('public/national_id'), $backName);
        if ($profileFile)
            $profileFile->move(public_path('profile_pic'), $profileName);

        // --- MULTIPLE PURCHASE HANDLING ---
        if ($request->has('multiple_estates')) {

            $multipleEstates = json_decode($request->multiple_estates, true);

            if (is_array($multipleEstates)) {

                // 🔥 Generate one shared ID for the whole batch
                $multipleUserId = Str::uuid();  // or rand()

                foreach ($multipleEstates as $estateData) {
                    $buyer = new buyer();

                    // Common fields
                    $buyer->firstname = $request->firstname;
                    $buyer->lastname = $request->lastname;
                    $buyer->gender = $request->gender;
                    $buyer->date_of_birth = $request->date_of_birth;
                    $buyer->NIN = $request->NIN;
                    $buyer->national_id_front = $frontName;
                    $buyer->national_id_back = $backName;
                    $buyer->profile_pic = $profileName;
                    $buyer->card_number = $request->card_number;
                    $buyer->land_poster = $request->land_poster;
                    $buyer->phonenumber = $request->phonenumber;
                    $buyer->method_payment = $request->payment_method;
                    $buyer->purchase_type = $request->purchase_type;
                    $buyer->half_or_full = $request->half_or_full;
                    $buyer->amount_payed = $request->amount_payed ?? 0;
                    $buyer->balance = $request->balance ?? 0;
                    $buyer->reciepts = $request->receipt_img ?? '0';
                    $buyer->agreement = $request->agreement ?? 'Pending';
                    $buyer->next_installment_pay = "2024-11-12";
                    $buyer->added_by = $request->hidden_user_name;
                    $buyer->date_sold = $formattedDate;

                    // Estate-specific
                    $buyer->estate = $estateData['estate'] ?? null;
                    $buyer->location = $estateData['location'] ?? 'Mukono';
                    $buyer->plot_number = $estateData['plot_number'] ?? null;
                    $buyer->width_1 = $estateData['width_1'] ?? null;
                    $buyer->width_2 = $estateData['width_2'] ?? null;
                    $buyer->height_1 = $estateData['height_1'] ?? null;
                    $buyer->height_2 = $estateData['height_2'] ?? null;

                    // 🔥 Assign same group ID
                    $buyer->multiple_user_id = $multipleUserId;

                    $buyer->save();
                }

                return response()->json([
                    "status" => true,
                    "message" => "Multiple plots have been successfully recorded.",
                ]);
            }
        }

        // --- SINGLE PURCHASE ---
        $buyer = new buyer();

        $buyer->firstname = $request->firstname;
        $buyer->lastname = $request->lastname;
        $buyer->gender = $request->gender;
        $buyer->date_of_birth = $request->date_of_birth;
        $buyer->NIN = $request->NIN;
        $buyer->national_id_front = $frontName;
        $buyer->national_id_back = $backName;
        $buyer->profile_pic = $profileName;
        $buyer->card_number = $request->card_number;
        $buyer->land_poster = $request->land_poster;
        $buyer->phonenumber = $request->phonenumber;
        $buyer->method_payment = $request->payment_method;
        $buyer->purchase_type = $request->purchase_type;
        $buyer->estate = $request->estate;
        $buyer->location = $request->location;
        $buyer->width_1 = $request->width_1;
        $buyer->width_2 = $request->width_2;
        $buyer->height_1 = $request->height_1;
        $buyer->height_2 = $request->height_2;
        $buyer->plot_number = $request->plot_number;
        $buyer->amount_payed = $request->amount_payed ?? 0;
        $buyer->balance = $request->balance ?? 0;
        $buyer->reciepts = $request->receipt_img ?? '0';
        $buyer->agreement = $request->agreement ?? 'Pending';
        $buyer->date_sold = $formattedDate;
        $buyer->half_or_full = $request->half_or_full;
        $buyer->next_installment_pay = "2024-11-12";
        $buyer->added_by = $request->hidden_user_name;

        // ℹ️ No need to set multiple_user_id (will stay NULL)

        $buyer->save();

        return response()->json([
            "status" => true,
            "message" => "Sale has been successfully recorded.",
        ]);
    }



    public function edit_sales(Request $request, $id, $user_id)
    {

        $admin_category = AdminRegister::where('id', $user_id)->value('admin_category');

        if ($admin_category == 'Admin') {
            return back()->with('error', 'Only Super Admins can edit records');
        } else if ($admin_category == null) {

            return back()->with('error', 'Only Super Admins can edit records');
        }

        $info = buyer::find($id);

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        return view('Admin.Edit.buyer_edit', $data, compact(['info']));
    }

    public function edit_user_info(Request $request)
    {

        $post = buyer::find($request->id);

        $post->firstname = $request->firstname;
        $post->lastname = $request->lastname;
        $post->gender = $request->gender;
        $post->date_of_birth = $request->date_of_birth;
        $post->NIN = $request->NIN;
        $post->phonenumber = $request->phonenumber;
        $post->card_number = $request->card_number;

        $save = $post->save();

        if ($save) {
            return back()->with('success', 'Information has been updated successfully');
        } else {
            return back()->with('error', 'Error while trying to update data');
        }
    }

    public function delete_sale($buyerId, $plotNumber, $estate)
    {

        $data = $this->user_right_info();

        if ($data != "SuperAdmin") {
            return redirect()->back()->with('error', 'You dont have rights to use the delete feature');
        }

        $buyerInfo = buyer::find($buyerId);
        $plotId = DB::table('plots')
            ->where('estate', $estate)
            ->where('plot_number', $plotNumber)->value('id');

        $post = plot::find($plotId);

        $post->status = 'Not taken';
        $post->buyer_id = 0;
        $post->save();

        $buyerInfo->delete();

        return redirect()->back()->with('success', 'Record has been deleted successfully, plot has been taken back to market');

    }

    public function view_specific_sale($id)
    {
        $userdata = buyer::find($id);
        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        return view('Admin.view_specific_buyer', $data, compact(['userdata']));
    }

    // ESTATES FUNCTIONS
    public function estates()
    {

        $estates = Estate::all();


        $count_estates = Estate::all()->count();

        $number_plots = plot::all()
            ->where('status', '=', 'Fully payed')->count();

        $Not_fully_paid_plots = plot::all()
            ->where('status', '=', 'Not taken')->count();

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        return view('Admin.estates', $data, compact(['estates', 'count_estates', 'number_plots', 'Not_fully_paid_plots']));
    }

    public function add_estate()
    {

        $User_access_right = $this->user_right_info();

        if ($User_access_right == 'SuperAdmin') {
            $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

            return view('Admin.add_estate', $data);
        } else {
            return redirect('estates');
        }
    }

    public function store_estate(Request $request)
    {

        $validate = $request->validate([
            'estate_name' => 'required',
            'location' => 'required',
            'estate_price' => 'required',
            'number_of_plots' => 'required',
            'estate_pdf' => 'required',
        ]);

        $post = new Estate;

        $post->estate_name = $request->estate_name;
        $post->location = $request->location;
        $post->estate_price = $request->estate_price;
        $post->number_of_plots = $request->number_of_plots;

        $file = $request->estate_pdf;

        $filename = date('YmdHi') . '.' . $file->getClientOriginalExtension();
        $file->move('estate_pdf', $filename);
        $post->estate_pdf = $filename;

        $post->save();

        //    Alert::success('Estate added', 'Congurations on adding a new Estate');

        return back()->with('success', 'Congurations on adding a new Estate');
    }

    public function download_estate($id)
    {

        $estate_name = buyer::where('id', $id)->value('estate');

        $estate_record = estate::where('estate_name', $estate_name)->value('estate_pdf');

        return response()->download(public_path('estate_pdf/' . $estate_record));

    }

    // Modified and Addded new code in the System.

    public function view_estate($id)
    {

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        $specific_estate = Estate::find($id);
        $estate_id = $id;
        $estate_name = $specific_estate->estate_name;

        $estate_pdf_info = $specific_estate->estate_pdf;

        $total_half_plots = DB::table('plots')
            ->where('plot_number', 'LIKE', '%h%')
            ->where('estate', '=', $estate_name)
            ->count();

        $total_plots_fully_taken = DB::table('plots')
            ->where('plot_number', 'LIKE', '%h%')
            ->where('estate', '=', $estate_name)
            ->where('half_or_full', '=', '0')
            ->get();

        // -------------------------->|| CONNECTED AND INDIVIDUAL PLOTS CODE FULLY PAID PLOTS||<----------------------------

        $all_plot_estates_fully = DB::table('plots')
            ->where('estate', '=', $estate_name)
            ->where('status', '=', 'Fully payed')
            ->get();

        // Connected Plots and individual plots fully payed

        // Initialize arrays to hold categorized records
        $connectedPlots = [];
        $individualPlots = [];
        $connectedPlotsProcessed = [];

        // Categorize records into connected and individual

        foreach ($all_plot_estates_fully as $plot) {
            if (strpos($plot->plot_number, '_') !== false) {
                $connectedPlots[] = $plot;
            } else {
                $individualPlots[] = $plot;
            }
        }

        foreach ($connectedPlots as $plot) {

            $plotNumbers = explode('_', $plot->plot_number);

            foreach ($plotNumbers as $number) {
                $connectedPlotsProcessed[] = $number;
            }
        }

        $connectedPlotsProcessedFullyPaid = count($connectedPlotsProcessed);
        $connectedPlotsProcessedFullyPaidJoined = count($connectedPlotsProcessed) + count($individualPlots);

        // -------------------------->|| CONNECTED AND INDIVIDUAL PLOTS CODE NOT PAID PLOTS||<----------------------------

        $all_plot_estates_not_fully = DB::table('plots')
            ->where('estate', '=', $estate_name)
            ->where('status', '=', 'Not taken')
            ->get();

        // Connected Plots and individual plots not fully paid

        // Initialize arrays to hold categorized records
        $connectedPlots = [];
        $individualNotPaidPlots = [];
        $connectedProcessedNotFullyPaidPlots = [];

        // Categorize records into connected and individual

        foreach ($all_plot_estates_not_fully as $plot) {
            if (strpos($plot->plot_number, '_') !== false) {
                $connectedPlots[] = $plot;
            } else {
                $individualNotPaidPlots[] = $plot;
            }
        }

        foreach ($connectedPlots as $plot) {

            $plotNumbers = explode('_', $plot->plot_number);

            foreach ($plotNumbers as $number) {
                $connectedProcessedNotFullyPaidPlots[] = $number;
            }
        }

        $connectedPlotsProcessedNotFullyPaid = count($connectedProcessedNotFullyPaidPlots);
        $connectedPlotsProcessedNotFullyPaidjoined = count($connectedProcessedNotFullyPaidPlots) + count($individualNotPaidPlots);

        // -------------------------->|| HALFS AND FULL PLOTS PROCESSING AND COUNTING CODE ||<----------------------------

        $Fully_taken = [];

        foreach ($total_plots_fully_taken as $key => $plot_one) {

            $Fully_taken[] = $plot_one->plot_number;
        }

        $counted = array_count_values($Fully_taken);

        // New code Implementation for counting plots

        // Fetch records with the specified estate and status
        $plot_estates_fully = DB::table('plots')
            ->where('estate', '=', $estate_name)
            ->where('status', '=', 'Fully payed')
            ->get();

        // Initialize arrays to hold categorized records
        $plot_fully_taken_with_half = [];
        $plot_fully_taken_without_half = [];

        // Categorize records
        foreach ($plot_estates_fully as $plot) {
            if (strpos($plot->plot_number, 'HALF') !== false) {
                // Group plots containing "HALF" by plot_number
                $plot_fully_taken_with_half[$plot->plot_number][] = $plot;
            } else {
                $plot_fully_taken_without_half[] = $plot;
            }
        }

        // Separate and count unique HALF plots
        $merged_half_plots = [];
        $unique_half_plots = [];
        $multiple_occurrence_half_plots = [];

        // Process grouped HALF plots
        foreach ($plot_fully_taken_with_half as $plot_number => $plots) {
            if (count($plots) > 1) {
                // Add one instance of plots with multiple occurrences to merged_half_plots
                $merged_half_plots[] = $plots[0];
                // Track multiple occurrences
                $multiple_occurrence_half_plots[] = $plots[0];
            } else {
                // Add unique HALF plots
                $unique_half_plots[] = $plots[0];
            }
        }

        // Combine the merged HALF plots with plots without HALF
        $combined_records = array_merge($plot_fully_taken_without_half, $merged_half_plots);

        // Prepare the result for output
        $result = [
            'combined_records' => $combined_records,
            'unique_half_plots' => $unique_half_plots,
            'count_combined_records' => count($combined_records),
            'count_unique_half_plots' => count($unique_half_plots),
            'count_multiple_occurrence_half_plots' => count($multiple_occurrence_half_plots),
        ];

        // End of code

        $count_estates_fully = count($combined_records);

        // IMPLEMENTATION 2 2 2 2 2 2 2

        // New code Implementation for counting plots

        // Fetch records with the specified estate and status
        $plot_estates_fully = DB::table('plots')
            ->where('estate', '=', $estate_name)
            ->where('status', '=', 'Not taken')
            ->get();

        // Initialize arrays to hold categorized records
        $plot_fully_taken_with_half = [];
        $plot_fully_taken_without_half = [];

        // Categorize records
        foreach ($plot_estates_fully as $plot) {
            if (strpos($plot->plot_number, 'HALF') !== false) {
                // Group plots containing "HALF" by plot_number
                $plot_fully_taken_with_half[$plot->plot_number][] = $plot;
            } else {
                $plot_fully_taken_without_half[] = $plot;
            }
        }

        // Separate and count unique HALF plots
        $merged_half_plots = [];
        $unique_half_plots = [];
        $multiple_occurrence_half_plots = [];

        // Process grouped HALF plots
        foreach ($plot_fully_taken_with_half as $plot_number => $plots) {
            if (count($plots) > 1) {
                // Add one instance of plots with multiple occurrences to merged_half_plots
                $merged_half_plots[] = $plots[0];
                // Track multiple occurrences
                $multiple_occurrence_half_plots[] = $plots[0];
            } else {
                // Add unique HALF plots
                $unique_half_plots[] = $plots[0];
            }
        }

        // Combine the merged HALF plots with plots without HALF
        $combined_records = array_merge($plot_fully_taken_without_half, $merged_half_plots);

        // Prepare the result for output
        $result = [
            'combined_records' => $combined_records,
            'unique_half_plots' => $unique_half_plots,
            'count_combined_records' => count($combined_records),
            'count_unique_half_plots' => count($unique_half_plots),
            'count_multiple_occurrence_half_plots' => count($multiple_occurrence_half_plots),
        ];

        // Debugging output

        $count_estates_not_fully = count($combined_records) + count($unique_half_plots);

        return view('Admin.view_specific_estate', $data, compact([
            'specific_estate',
            'count_estates_fully',
            'total_half_plots',
            'count_estates_not_fully',
            'estate_id',
            'estate_name',
            'estate_pdf_info',
            'connectedPlotsProcessedFullyPaid',
            'connectedPlotsProcessedNotFullyPaid',
            'connectedPlotsProcessedFullyPaidJoined',
            'connectedPlotsProcessedNotFullyPaidjoined'
        ]));
    }

    public function fetchPendingNumbers()
    {

        $plot_numbers = DB::table('plots')->where('estate', '=', 'Nabuloto_3')->pluck('plot_number');

    }

    public function totalHalfPlots($id)
    {

        $specific_estate = Estate::find($id);
        $estate_id = $id;
        $estate_name = $specific_estate->estate_name;

        $total_half_plots_count = DB::table('plots')
            ->where('plot_number', 'LIKE', '%h%')
            ->where('estate', '=', $estate_name)
            ->count();

        $total_estate_data = DB::table('plots')
            ->where('plot_number', 'LIKE', '%h%')
            ->where('estate', '=', $estate_name)
            ->orderBy('plot_number', 'asc')
            ->get();

        $total_plots_fully_taken = DB::table('plots')
            ->where('plot_number', 'LIKE', '%h%')
            ->where('estate', '=', $estate_name)
            ->where('half_or_full', '=', '0')
            ->get();

        $total_half_plots_not_fully_taken_count = DB::table('plots')
            ->where('plot_number', 'LIKE', '%h%')
            ->where('estate', '=', $estate_name)
            ->where('half_or_full', '=', '1')
            ->count();

        $Fully_taken = [];

        foreach ($total_plots_fully_taken as $key => $plot_one) {

            $Fully_taken[] = $plot_one->plot_number;
        }

        $counted = array_count_values($Fully_taken);

        $duplicates = array_filter($counted, function ($count) {
            return $count > 1;
        });

        $TotalFullyPaidHalfs = array_keys($duplicates);
        $TotalFullyPaidHalfsCount = count($duplicates);
        $TotalFullyPaidHalfsCount *= 2;

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        return view('Admin.all_halfs_data', $data, compact([
            'specific_estate',
            'total_half_plots_count',
            'estate_id',
            'estate_name',
            'total_estate_data',
            'TotalFullyPaidHalfsCount',
            'total_half_plots_not_fully_taken_count'
        ]));
    }

    public function fully_taken_half_plots($id)
    {

        $specific_estate = Estate::find($id);
        $estate_id = $id;
        $estate_name = $specific_estate->estate_name;

        $total_half_plots_fully_taken_data = DB::table('plots')
            ->where('plot_number', 'LIKE', '%h%')
            ->where('estate', '=', $estate_name)
            ->where('half_or_full', '=', '0')
            ->get();

        $total_half_plots_fully_taken_data_count = DB::table('plots')
            ->where('plot_number', 'LIKE', '%h%')
            ->where('estate', '=', $estate_name)
            ->where('half_or_full', '=', '0')
            ->count();

        $Fully_taken = [];

        foreach ($total_half_plots_fully_taken_data as $key => $plot_one) {

            $Fully_taken[] = $plot_one->plot_number;
        }

        $counted = array_count_values($Fully_taken);

        $duplicates = array_filter($counted, function ($count) {
            return $count > 1;
        });

        $TotalFullyPaidHalfs = array_keys($duplicates);
        $TotalFullyPaidHalfsCount = count($duplicates);
        $TotalFullyPaidHalfsCount *= 2;

        // Fetch records where plot_numbers match any value in the array

        $total_half_plots_fully_taken_data = DB::table('plots')
            ->whereIn('plot_number', $TotalFullyPaidHalfs)
            ->where('estate', $estate_name)
            ->orderBy('plot_number', 'asc')
            ->get();

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        return view('Admin.total_half_plots_fully_taken_data', $data, compact(['total_half_plots_fully_taken_data', 'TotalFullyPaidHalfsCount', 'estate_name']));
    }

    public function fully_not_taken_half_plots($id)
    {

        $specific_estate = Estate::find($id);
        $estate_id = $id;
        $estate_name = $specific_estate->estate_name;

        $not_taken_half_plots = DB::table('plots')
            ->where('plot_number', 'LIKE', '%h%')
            ->where('estate', '=', $estate_name)
            ->where('half_or_full', '=', '1')
            ->get();

        $not_taken_half_plots_count = DB::table('plots')
            ->where('plot_number', 'LIKE', '%h%')
            ->where('estate', '=', $estate_name)
            ->where('half_or_full', '=', '1')
            ->count();

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        return view('Admin.not_taken_half_plots', $data, compact(['not_taken_half_plots', 'not_taken_half_plots_count', 'estate_name']));

    }

    public function partially_taken_half_plots($id)
    {

        $specific_estate = Estate::find($id);
        $estate_id = $id;
        $estate_name = $specific_estate->estate_name;

        $not_taken_half_plots = DB::table('plots')
            ->where('plot_number', 'LIKE', '%h%')
            ->where('estate', '=', $estate_name)
            ->where('half_or_full', '=', '0')
            ->get();

        $not_taken_half_plots_count = DB::table('plots')
            ->where('plot_number', 'LIKE', '%h%')
            ->where('estate', '=', $estate_name)
            ->where('half_or_full', '=', '0')
            ->count();

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        return view('Admin.partially_taken_half_plots', $data, compact(['not_taken_half_plots', 'not_taken_half_plots_count', 'estate_name']));

    }

    public function plots()
    {

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        $estates = Estate::all();

        return view('Admin.plots', $data, compact('estates'));
    }

    public function send_plot_estate(Request $request)
    {

        $status = $request->land_status;
        $estate_name = $request->Estate;
        $plot_number = $request->plot_number;
        $user_name = $request->hidden_user_naeme;

        $exceptional_status = $request->exceptional_status;
        $exceptional_amount = $request->exceptional_amount;

        $check_plot_availability = DB::table('buyers')
            ->where('estate', '=', $estate_name)
            ->where('plot_number', '=', $plot_number)->get();

        $estate_existance = plot::where('estate', $estate_name)
            ->where('plot_number', '=', $plot_number)->count();

        $total_plots_fully_taken = DB::table('plots')
            ->where('plot_number', 'LIKE', '%h%')
            ->where('estate', '=', $estate_name)
            ->where('half_or_full', '=', '0')
            ->get();

        $Fully_taken = [];

        foreach ($total_plots_fully_taken as $key => $plot_one) {

            $Fully_taken[] = $plot_one->plot_number;
        }

        $counted = array_count_values($Fully_taken);

        $duplicates = array_filter($counted, function ($count) {
            return $count > 1;
        });

        $TotalFullyPaidHalfs = array_keys($duplicates);
        $TotalFullyPaidHalfsCount = count($duplicates);

        if ($exceptional_status == "Yes") {
            $estate_price = estate::where('estate_name', $estate_name)->value('estate_price');

            if ($exceptional_amount < $estate_price) {

                return response()->json([
                    "status" => false,
                    "message" => "Exceptional Amount entered is less than original estate price",
                ]);
            }
        }

        if ($check_plot_availability->isNotEmpty()) {
            return response()->json([
                "status" => false,
                "message" => "Plot being entered is already taken",
            ]);
        }

        if ($estate_existance > 0) {
            return response()->json([
                "status" => false,
                "message" => "Plot has already been entered before !",
            ]);
        }

        $user_amount_paid = $request->amount_paid;

        $plot_number = $request->plot_number;
        $no_of_plots = estate::where('estate_name', $estate_name)->value('number_of_plots');
        $count_estates = buyer::where('estate', $estate_name)
            ->where('plot_number', 'not like', '%HALF%')->count();

        $user_amount_paid = $request->amount_paid;
        $Date_of_payment = $request->date_sold;
        $plot_numb = $request->House_plot;
        $plot_number = $request->plot_number;

        if ($plot_numb == "Plot") {
            if ($status == "Not_taken") {

                $count_estates = buyer::where('estate', $estate_name)
                    ->where('plot_number', 'not like', '%HALF%')->count();

                $count_estates += $TotalFullyPaidHalfsCount;
                if ($count_estates >= $no_of_plots) {

                    return response()->json([
                        "status" => false,
                        "message" => "The plots in this estate are fully taken",
                    ]);

                }
            }
        }

        if ($status == "Fully_taken") {

            $estate_price = estate::where('estate_name', $estate_name)->value('estate_price');
            $paid_amount = $request->amount_paid;
            if ($paid_amount < $estate_price) {
                return response()->json([
                    "status" => false,
                    "message" => "Amount used to purchase this plot is less",
                ]);
            }
        }

        if ($status == "Fully_taken") {

            $agreement_attached = $request->file('files');

            if ($agreement_attached == null) {
                return response()->json([
                    "status" => false,
                    "message" => "Agreement has to be attached before submitting",
                ]);
            }
        }

        if ($status == "Fully_taken") {

            $post = new buyer;

            $post->firstname = $request->firstname;
            $post->lastname = $request->lastname;
            $post->gender = "-";
            $post->date_of_birth = "-";
            $post->NIN = "-";
            $post->added_by = $user_name;

            $post->phonenumber = $request->phonenumber;

            $mytime = now();
            $random_number_reference = rand(10000, 99999);
            $ref = $random_number_reference . '' . $mytime;

            foreach ($request->file('files') as $key => $file) {

                $filename = date('YmdHi') . $file->getClientOriginalName();
                $file->move('agreements', $filename);

                if ($key + 1 > 1) {

                    $save = new agreement();

                    $save->user_id = $ref;
                    $save->Amount_paid = '-';
                    $save->Date_of_payment = '-';
                    $save->reciept = '-';
                    $save->agreement = $filename;
                    $save->save();

                } else {

                    $save = new agreement();
                    $save->user_id = $ref;
                    $save->Amount_paid = $user_amount_paid;
                    $save->Date_of_payment = $Date_of_payment;
                    $save->reciept = '-';
                    $save->agreement = $filename;
                    $save->save();
                }

            }
            $post->agreement = $ref;

            $file = $request->national_id_front;
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('public/national_id'), $filename);
            $post->national_id_front = $filename;

            $file = $request->national_id_back;
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('public/national_id'), $filename);
            $post->national_id_back = $filename;

            $file = $request->profile_pic;
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('profile_pic'), $filename);
            $post->profile_pic = $filename;

            $post->card_number = "-";
            $post->land_poster = "Paid";
            $post->method_payment = "paying_in_installments";
            $post->purchase_type = $request->House_plot;
            $post->estate = $request->Estate;
            $post->location = $request->location;
            $post->width_1 = $request->width1;
            $post->width_2 = $request->width2;
            $post->height_1 = $request->height1;
            $post->height_2 = $request->height2;
            $post->plot_number = $request->plot_number;
            $post->balance = $request->balance;
            $post->date_sold = $request->date_sold;
            $post->next_installment_pay = "Fully payed";
            $post->amount_payed = $request->amount_paid;
            $post->half_or_full = "0";

            $post->save();

            $buyer_db_id = buyer::where('estate', $estate_name)
                ->where('plot_number', $plot_number)->value('id');
            $post = new plot;

            $post->estate = $request->Estate;
            $post->plot_number = $request->plot_number;
            $post->location = $request->location;
            $post->width_1 = $request->width1;
            $post->width_2 = $request->width2;
            $post->height_1 = $request->height1;
            $post->height_2 = $request->height2;
            $post->buyer_id = $buyer_db_id;
            $post->status = "Fully payed";
            $post->exceptional_status = $exceptional_status;
            if ($exceptional_status == "Yes") {
                $post->exceptional_amount = $exceptional_amount;
            } else {
                $post->exceptional_amount = "0";
            }

            $post->half_or_full = "0";
            $post->save();

            return response()->json([
                "status" => true,
                "message" => "Congurations on adding a new Plot",
            ]);

        } else if ($status == "Under_payment") {

            $post = new buyer;

            $post->firstname = $request->firstname;
            $post->lastname = $request->lastname;
            $post->phonenumber = $request->phonenumber;
            $post->added_by = $user_name;

            $post->gender = "-";
            $post->date_of_birth = "-";
            $post->NIN = "-";
            $post->agreement = 'Pending';

            $file = $request->national_id_front;
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('public/national_id'), $filename);
            $post->national_id_front = $filename;

            $file = $request->national_id_back;
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('public/national_id'), $filename);
            $post->national_id_back = $filename;

            $file = $request->profile_pic;
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('profile_pic'), $filename);
            $post->profile_pic = $filename;

            $post->card_number = "-";
            $post->land_poster = "Paid";
            $post->method_payment = "paying_in_installments";
            $post->purchase_type = $request->House_plot;
            $post->estate = $request->Estate;
            $post->location = $request->location;
            $post->width_1 = $request->width1;
            $post->width_2 = $request->width2;
            $post->height_1 = $request->height1;
            $post->height_2 = $request->height2;
            $post->plot_number = $request->plot_number;
            $post->balance = $request->balance;
            $post->date_sold = $request->date_sold;
            $post->next_installment_pay = $request->next_installment_date;
            $post->amount_payed = $request->amount_paid;
            $post->half_or_full = "0";
            $post->reciepts = '0';

            $post->save();

            $buyer_db_id = buyer::where('estate', $estate_name)
                ->where('plot_number', $plot_number)->value('id');

            $post = new plot;

            $post->estate = $request->Estate;
            $post->plot_number = $request->plot_number;
            $post->location = $request->location;
            $post->width_1 = $request->width1;
            $post->width_2 = $request->width2;
            $post->height_1 = $request->height1;
            $post->height_2 = $request->height2;
            $post->buyer_id = $buyer_db_id;
            $post->exceptional_status = $exceptional_status;
            if ($exceptional_status == "Yes") {
                $post->exceptional_amount = $exceptional_amount;
            } else {
                $post->exceptional_amount = "0";
            }
            $post->half_or_full = "0";
            $post->status = "Not taken";

            $post->save();

            return response()->json([
                "status" => true,
                "message" => "Congurations on adding a new Plot",
            ]);
        } else {

            $post = new plot;

            $post->estate = $request->Estate;
            $post->plot_number = $request->plot_number;
            $post->location = $request->location;
            $post->width_1 = $request->width1;
            $post->width_2 = $request->width2;
            $post->height_1 = $request->height1;
            $post->height_2 = $request->height2;
            $post->buyer_id = '0';
            $post->exceptional_status = $exceptional_status;
            if ($exceptional_status == "Yes") {
                $post->exceptional_amount = $exceptional_amount;
            } else {
                $post->exceptional_amount = "0";
            }
            $post->status = "Not taken";
            $post->half_or_full = "1";

            $post->save();

            return response()->json([
                "status" => true,
                "message" => "Congurations on adding a new Plot",
            ]);
        }
    }

    public function update_data_form(Request $request)
    {

        $info = $request->Estate_plot;

        $specific_estate = DB::table('plots')->select('plot_number')->where('estate', '=', $info)->get();

        return response()->json([
            "status" => true,
            "message" => "data has been creaeted",
            "data" => $specific_estate,
        ]);
    }

    // Reciepts and Agreements

    public function pending_agreements()
    {
        $pending_agreements = DB::table('buyers')->where('reciepts', '=', "0")
            ->where('next_installment_pay', '=', "Fully payed")->get();

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        return view('Admin.Receipts.pending_agreements', $data, compact(['pending_agreements']));

    }

    public function pending_buyers()
    {

        $records = Estate::all();

        $not_fully_paid = DB::table('buyers')->where('next_installment_pay', '!=', "Fully payed")
            ->orderBy('id', 'Desc')
            ->get();

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        return view('Admin.Receipts.pending', $data, compact(['not_fully_paid', 'records']));
    }

    public function pending_receipts()
    {
        $pending_reciepts = DB::table('buyers')->where('reciepts', '=', "0")
            ->where('next_installment_pay', '!=', "Fully payed")->get();

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        return view('Admin.Receipts.pending_receipts', $data, compact(['pending_reciepts']));
    }

    public function accomplished_buyers()
    {
        $fully_paid = DB::table('buyers')
            ->where('next_installment_pay', '=', "Fully payed")
            ->where('reciepts', '!=', "0")
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Attach agreement filename to each buyer
        foreach ($fully_paid as $buyer) {
            $agreement = DB::table('agreements')->where('user_id', $buyer->id)->latest()->first();
            $buyer->agreement_file = $agreement ? $agreement->agreement : null;
        }

        $data = [
            'LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()
        ];

        return view('Admin.Receipts.accomplished', $data, compact('fully_paid'));
    }


    public function add_reciept($id)
    {

        $user_id = $id;
        $userInformation = Buyer::find($id);
        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        return view('Admin.Receipts.add_receipt', $data, compact(['user_id', 'userInformation']));
    }

    public function view_reciept($id)
    {
        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        $user_information = DB::table('buyers')->where('id', '=', $id)->get();
        $user_reciepts = DB::table('pdf_receipts')->where('user_id', '=', $id)->get();
        $user_agreements = DB::table('pdf_agreements')->where('user_id', '=', $id)->get();

        $agreement_reference_in_buyer = DB::table('buyers')->where('id', '=', $id)->value('agreement');
        $user_agreements_uploaded = DB::table('agreements')->where('user_id', '=', $agreement_reference_in_buyer)->get();

        $user_reciepts_pdf = DB::table('reciepts')->where('user_id', '=', $id)->get();
        $User_access_right = $this->user_right_info();

        $user_resell_agreement = DB::table('resales')->where('user_id', '=', $id)->orderBy('id', 'desc')->first();
        if ($user_resell_agreement && !empty($user_resell_agreement->seller_agreeement)) {
            $user_resell_agreement->seller_agreeement = explode(',', $user_resell_agreement->seller_agreeement); // Convert comma-separated file names to array
        }

        $user_agreements_pdf = DB::table('agreements')->where('user_id', '=', $id)->get();

        if ($User_access_right == 'SuperAdmin' || $User_access_right == 'Admin' || $User_access_right == 'Sales') {
            return view('Admin.Receipts.view_agreement', $data, compact(['user_information', 'user_reciepts', 'user_agreements', 'user_reciepts_pdf', 'user_agreements_pdf', 'user_agreements_uploaded', 'user_resell_agreement']));
        } else {
            return redirect('estates');
        }
    }

    public function view_half_plot_info($user_id, $estate)
    {

        $buyer_id = DB::table('plots')->where('id', '=', $user_id)
            ->where('estate', '=', $estate)->value('buyer_id');

        $user_information = DB::table('buyers')->where('id', '=', $buyer_id)
            ->where('estate', '=', $estate)->get();

        $id = $buyer_id;

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];
        $user_reciepts = DB::table('pdf_receipts')->where('user_id', '=', $id)->get();
        $user_reciepts_pdf = DB::table('reciepts')->where('user_id', '=', $id)->get();
        $user_agreements = DB::table('pdf_agreements')->where('user_id', '=', $id)->get();

        $agreement_reference_in_buyer = DB::table('buyers')->where('id', '=', $id)->value('agreement');
        $user_agreements_uploaded = DB::table('agreements')->where('user_id', '=', $agreement_reference_in_buyer)->get();
        $user_agreements_pdf = DB::table('agreements')->where('user_id', '=', $id)->get();

        $User_access_right = $this->user_right_info();

        if ($User_access_right == 'SuperAdmin' || $User_access_right == 'Admin' || $User_access_right == 'Sales') {

            return view('Admin.Receipts.view_receipts', $data, compact(['user_information', 'user_reciepts', 'user_agreements', 'user_reciepts_pdf', 'user_agreements_pdf', 'user_agreements_uploaded']));
        } else {
            return redirect('estates');
        }
    }

    public function add_agreement($id)
    {

        $user_id = $id;

        $userInformation = Buyer::find($id);

        $estate = DB::table('buyers')->where('id', '=', $id)->value('estate');
        $plot_no = DB::table('buyers')->where('id', '=', $id)->value('plot_number');

        $check_plot = DB::table('plots')->where('estate', '=', $estate)
            ->where('plot_number', '=', $plot_no)
            ->value('status');

        $check_half = DB::table('plots')->where('estate', '=', $estate)
            ->where('plot_number', '=', $plot_no)
            ->value('half_or_full');

        if ($check_plot == 'Fully payed' && $check_half == '0') {
            $estates = estate::all();
            $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];
            abort(403, 'This plot has already been sold, contact administrator or CEO');

            // return view('Admin.Receipts.full_paid_plot', $data, compact(['user_id', 'plot_no', 'estate', 'estates', 'userInformation']));
        } else {
            $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];
            return view('Admin.Receipts.add_agreement', $data, compact(['user_id', 'userInformation']));
        }
    }

    public function view_agreement($id)
    {

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];
        $user_information = DB::table('buyers')->where('id', '=', $id)->get();
        $user_reciepts = DB::table('pdf_receipts')->where('user_id', '=', $id)->get();
        $user_agreements = DB::table('pdf_agreements')->where('user_id', '=', $id)->get();
        $user_reciepts_pdf = DB::table('reciepts')->where('user_id', '=', $id)->get();

        $agreement_reference_in_buyer = DB::table('buyers')->where('id', '=', $id)->value('agreement');
        $user_agreements_uploaded = DB::table('agreements')->where('user_id', '=', $agreement_reference_in_buyer)->get();
        $user_agreements_pdf = DB::table('agreements')->where('user_id', '=', $id)->get();

        return view('Admin.Receipts.view_agreement', $data, compact(['user_information', 'user_reciepts', 'user_agreements', 'user_reciepts_pdf', 'user_agreements_pdf', 'user_agreements_uploaded']));
    }

    public function store_new_receipt(Request $request)
    {

        $request->validate([
            'amount_paid' => 'required',
            'Date_of_payment' => 'required',
            'balance_pending' => 'required',
            'phone_number' => 'required',
            'amount_in_words' => 'required',
        ]);

        $amount_paid = str_replace(',', '', $request->amount_paid);
        $amountPaidRaw = $request->amount_paid;
        $balancePendingRaw = $request->balance_pending;
        $balance_pending = str_replace(',', '', $request->balance_pending);

        $AmountFormatted = number_format($amount_paid);
        $BalanceFormatted = number_format($balance_pending);

        $user_email = $request->user_email;
        $user_name = $request->user_name;
        $user_id = $request->user_id;
        $Phonenumber = $request->phone_number;
        $amount_in_words = $request->amount_in_words;

        $admin_user_spec_id = session('LoggedAdmin');
        $admin_user_info = AdminRegister::where('id', $admin_user_spec_id)->first();
        $user_info = buyer::where('id', $user_id)->first();
        $receipt_no = rand(10000, 50000);

        $currentDate = Carbon::now();
        $formattedDate = $currentDate->format('Y/m/d');

        $user_id = $request->user_id;
        $amount_paid = $amount_paid;
        $Balance = $BalanceFormatted;
        $Amount = $amount_paid;

        $post = new reciept();

        $pdf = PDF::loadView('invoice_pdf', compact(['user_email', 'user_name', 'formattedDate', 'receipt_no', 'Amount', 'Balance', 'Phonenumber', 'admin_user_info', 'amount_in_words', 'user_info']));
        $filename = 'payment_reciepet' . time() . '.pdf';
        // Make sure destination directory exists
        $destinationDirectory = public_path("storage/pdf_receipts");

        if (!file_exists($destinationDirectory)) {
            mkdir($destinationDirectory, 0755, true); // create folders recursively
        }

        // Save PDF to Laravel's internal storage path
        $pdf->save(storage_path("app/public/pdf_receipts/{$filename}"));

        // Copy to public folder
        copy(
            storage_path("app/public/pdf_receipts/{$filename}"),
            public_path("storage/pdf_receipts/{$filename}")
        );

        $post->reciept = $filename;
        $post->user_id = $request->user_id;
        $post->Amount = $amount_paid;
        $post->Date_of_payment = $request->Date_of_payment;
        $post->Balance = $BalanceFormatted;
        $post->Phonenumber = $Phonenumber;
        $post->amount_in_words = $request->amount_in_words;
        $post->save();

        $original_amount = buyer::where('id', $user_id)->value('amount_payed');

        $all_cash = $original_amount + str_replace(',', '', $AmountFormatted);

        $update_buyer_amount = buyer::where('id', $user_id)
            ->update([
                'amount_payed' => $all_cash,
                'balance' => $BalanceFormatted,
            ]);

        return redirect()->back()->with('download_link', asset("storage/pdf_receipts/{$filename}"));
    }

    public function add_first_reciept($id)
    {

        $user_id = $id;
        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        return view('Admin.Receipts.add_first_receipt', $data, compact(['user_id']));
    }

    public function store_first_receipt(Request $request)
    {

        $request->validate([

            'amount_paid' => 'required',
            'Date_of_payment' => 'required',
            'balance_pending' => 'required',
            'phone_number' => 'required',
            'amount_in_words' => 'required',
        ]);

        $user_email = $request->user_email;
        $user_name = $request->user_name;

        $user_id = $request->user_id;
        $Amount = $request->amount_paid;
        $Balance = $request->balance_pending;
        $Phonenumber = $request->phone_number;
        $amount_in_words = $request->amount_in_words;

        $user_info = buyer::where('id', $user_id)->first();
        $receipt_no = rand(10000, 50000);

        $admin_user_spec_id = session('LoggedAdmin');
        $admin_user_info = Adminregister::where('id', $admin_user_spec_id)->first();

        $currentDate = Carbon::now();
        $formattedDate = $currentDate->format('Y/m/d');

        $pdf = PDF::loadView('invoice_pdf', compact(['user_email', 'user_name', 'formattedDate', 'admin_user_info', 'receipt_no', 'Amount', 'Balance', 'Phonenumber', 'amount_in_words', 'user_info']));
        $filename = 'payment_reciepet' . time() . '.pdf';

        $pdf->save(storage_path("app/public/pdf_receipts/{$filename}"));

        $post = new reciept();

        $post->user_id = $request->user_id;
        $post->Amount = $request->amount_paid;
        $post->Date_of_payment = $request->Date_of_payment;
        $post->Balance = $request->balance_pending;
        $post->Phonenumber = $Phonenumber;
        $post->amount_in_words = $request->amount_in_words;
        $post->reciept = $filename;

        $post->save();

        // $post = new pdf_receipt();

        // $post->user_id=$user_id;
        // $post->receipt=$filename;

        // $post->save();

        $original_amount = buyer::where('id', $user_id)->value('amount_payed');
        $all_cash = $original_amount + $Amount;

        $update_buyer_amount = buyer::where('id', $user_id)
            ->update([
                'reciepts' => "Pending",
                'amount_payed' => $all_cash,
                'balance' => $Balance
            ]);

        // Update First reciept records

        $estate_no_no = buyer::where('id', $user_id)->value('estate');
        $plot_no_no = buyer::where('id', $user_id)->value('plot_number');
        $half_or_full_status = buyer::where('id', $user_id)->value('half_or_full');
        $half_or_full_status_db = buyer::where('id', $user_id)->value('half_or_full');

        $whereConditions = [
            'estate' => $estate_no_no,
            'plot_number' => $plot_no_no
        ];

        $plot_status = DB::table('plots')->where($whereConditions)->value('status');
        $half_or_full_status = DB::table('plots')->where($whereConditions)->value('half_or_full');

        if ($half_or_full_status_db == '1' && $half_or_full_status != '1') {
            $plot_status = DB::table('plots')->where($whereConditions)
                ->update(['half_or_full' => $half_or_full_status_db]);

        }

        if ($plot_status == "Not taken") {
            $plot_status = DB::table('plots')->where($whereConditions)
                ->update([
                    'status' => 'Underpayment',
                ]);
        }

        return $pdf->download($filename);

        if ($update_buyer_amount) {
            return redirect('pending-buyers')->with('success', 'Reciept has been recorded successfully');
        }
    }

    public function store_agreement(Request $request)
    {

        $amount_in_words = $request->amount_in_words;

        $user_id = $request->user_id;
        $original_amount = buyer::where('id', $user_id)->value('amount_payed');

        $half_or_full = buyer::where('id', $user_id)->value('half_or_full');

        $plot_number = buyer::where('id', $user_id)->value('plot_number');
        $estate_name = buyer::where('id', $user_id)->value('estate');
        $estate_price = Estate::where('estate_name', $estate_name)->value('estate_price');

        $user_amount_paid = $request->amount_paid;
        $all_cash = $original_amount + $user_amount_paid;

        $group_id = plot::where('plot_number', $plot_number)
            ->where('estate', $estate_name)
            ->value('group_id');

        $group_total = Plot::where('group_id', $group_id)->sum('exceptional_amount');

        $interconnected_plots = Plot::where('group_id', $group_id)->pluck('plot_number')->toArray();

        $plot_numbers = implode(', ', $interconnected_plots);

        if ($all_cash < $estate_price) {
            return response()->json([
                'status' => 'error',
                'message' => 'The amount ' . $original_amount . ' so far paid by the client is not enough to purchase this plot in this estate which is ' . $estate_price
            ], 400); // 400 Bad Request
        }

        $record_plot = buyer::where('id', $user_id)->value('plot_number');
        $exceptional_status = plot::where('plot_number', $record_plot)
            ->where('estate', '=', $estate_name)->value('exceptional_status');

        if ($exceptional_status == "Yes") {

            $exceptional_amount = plot::where('plot_number', $record_plot)
                ->where('estate', '=', $estate_name)->value('exceptional_amount');

            if ($all_cash < $exceptional_amount) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Amount used to purchase which is ' . $original_amount . ' plot is less, this is an exceptional plot'
                ], 400);
            }
        }

        $user_id = $request->user_id;
        $reciepts = $request->reciept_added;
        $agreement_reciept = $request->agreement_added;
        $user_amount_paid = $request->amount_paid;
        $Date_of_payment = now();

        $balance = 0;

        $original_amount = buyer::where('id', $user_id)->value('amount_payed');
        $all_cash = $original_amount + $user_amount_paid;

        // Document formulation

        $user_info = buyer::where('id', $user_id)->first();
        $profile_pic = buyer::where('id', $user_id)->value('profile_pic');
        $profile_pic = public_path('profile_pic/' . $profile_pic);

        $day = $user_info->created_at->day;
        $month = $user_info->created_at->month;
        $year = $user_info->created_at->year;
        $user_amount_paid = $original_amount;
        $user_receipt = '-';

        $pdf = PDF::loadView('agreement_pdf', compact([
            'user_amount_paid',
            'user_info',
            'all_cash',
            'Date_of_payment',
            'day',
            'month',
            'year',
            'amount_in_words',
            'profile_pic'
        ]));

        $filename = 'payment_agreement' . time() . '.pdf';
        $publicPath = public_path('agreements');

        if (!file_exists($publicPath)) {
            mkdir($publicPath, 0775, true);
        }

        $pdf->save("{$publicPath}/{$filename}");

        $post = new agreement();
        $post->user_id = $request->user_id;
        $post->Amount_paid = $original_amount;
        $post->Date_of_payment = now();
        $post->reciept = '-';
        $post->agreement = $filename;
        $post->save();

        $original_amount = buyer::where('id', $user_id)->value('amount_payed');
        $all_cash = $original_amount + $user_amount_paid;

        $save = $post->save();

        // Update buyer records

        $update_buyer_agreement = buyer::where('id', $user_id)->update([
            'next_installment_pay' => "Fully payed",
            'reciepts' => '-',
            'agreement' => '-',
            'amount_payed' => $all_cash,
            'balance' => $balance
        ]);

        $estate_no_no = buyer::where('id', $user_id)->value('estate');
        $plot_no_no = buyer::where('id', $user_id)->value('plot_number');

        $whereConditions = [
            'estate' => $estate_no_no,
            'plot_number' => $plot_no_no,
        ];

        DB::table('plots')->where($whereConditions)->update(['status' => 'Fully payed']);

        DB::insert(
            'insert into reciepts (user_id, amount, balance, reciept, Date_of_payment, Phonenumber, amount_in_words) values (?, ?, ?, ?, ?, ?, ?)',
            [$user_id, $user_amount_paid, $balance, $user_receipt, $Date_of_payment, '-', '-']
        );

        $half_plot_or_full_plot = plot::where($whereConditions)->value('half_or_full');
        $full_payed_or_not = plot::where($whereConditions)->value('status');

        if ($half_plot_or_full_plot == '1' && $full_payed_or_not == "Fully payed") {
            DB::table('plots')->where($whereConditions)->update(['half_or_full' => '0']);

        }

        return response()->json([
            'status' => 'success',
            'message' => 'Agreement generated successfully!',
            'download_link' => url("agreements/{$filename}")
        ]);

    }

    public function store_agreement_new_plot(Request $request)
    {

        $amount_in_words = $request->amount_in_words;

        $user_id = $request->user_id;
        $original_amount = buyer::where('id', $user_id)->value('amount_payed');

        $estate_name = buyer::where('id', $user_id)->value('estate');
        $estate_price = estate::where('estate_name', $estate_name)->value('estate_price');

        $user_amount_paid = $request->amount_paid;
        $all_cash = $original_amount + $user_amount_paid;

        if ($all_cash < $estate_price) {
            return back()->with('error', 'This amount paid is not enough to take this plot in this estate');
        }

        $user_id = $request->user_id;
        $reciepts = $request->reciept_added;
        $agreement_reciept = $request->agreement_added;
        $user_amount_paid = $request->amount_paid;
        $Date_of_payment = $request->Date_of_payment;

        $balance = 0;

        $estate_update = $request->Estate_plot;
        $width_update1 = $request->plot_width1;
        $width_update2 = $request->plot_width2;
        $height_update1 = $request->plot_height1;
        $height_update2 = $request->plot_height2;
        $location = $request->location_plot;
        $plot_numb = $request->plot_number;

        $info_update = buyer::where('id', $user_id)->update([
            'estate' => $estate_update,
            'width_1' => $width_update1,
            'width_2' => $width_update2,
            'height_1' => $height_update1,
            'height_2' => $height_update2,
            'location' => $location,
            'plot_number' => $plot_numb,
        ]);

        // Document formulation
        $user_info = buyer::where('id', $user_id)->first();
        $profile_pic = buyer::where('id', $user_id)->value('profile_pic');
        $profile_pic = public_path('profile_pic/' . $profile_pic);

        $day = $user_info->created_at->day;
        $month = $user_info->created_at->month;
        $year = $user_info->created_at->year;

        // Ensure all the variables are correctly passed to the view
        $pdf = PDF::loadView('agreement_pdf', compact('user_amount_paid', 'user_info', 'all_cash', 'Date_of_payment', 'day', 'month', 'year', 'amount_in_words', 'profile_pic'));

        // Generate filename for saving PDF
        $filename = 'payment_agreement_' . time() . '.pdf';

        // Save the PDF to the specified path
        $pdf->save(storage_path("app/public/agreements/{$filename}"));

        // Create a new agreement record (optional)
        $post = new agreement();

        $post->user_id = $request->user_id;
        $post->Amount_paid = $request->amount_paid;
        $post->Date_of_payment = $request->Date_of_payment;
        $user_receipt = '-';
        $post->reciept = '-';
        $post->agreement = $filename;

        $original_amount = buyer::where('id', $user_id)->value('amount_payed');
        $all_cash = $original_amount + $user_amount_paid;

        $save = $post->save();

        // Update buyer records

        $update_buyer_agreement = buyer::where('id', $user_id)->update([
            'next_installment_pay' => "Fully payed",
            'reciepts' => '-',
            'agreement' => '-',
            'amount_payed' => $all_cash,
            'balance' => $balance
        ]);

        $estate_no_no = buyer::where('id', $user_id)->value('estate');
        $plot_no_no = buyer::where('id', $user_id)->value('plot_number');

        $whereConditions = [
            'estate' => $estate_no_no,
            'plot_number' => $plot_no_no
        ];

        DB::table('plots')->where($whereConditions)->update(['status' => 'Fully payed']);

        DB::insert('insert into reciepts (user_id,amount,balance,reciept,Date_of_payment,Phonenumber,amount_in_words) values (?,?,?,?,?,?,?)', [$user_id, $balance, $user_amount_paid, $user_receipt, $Date_of_payment, '-', '-']);

        $profile_pic = buyer::where('id', $user_id)->value('profile_pic');

        // return $pdf->stream($filename);

        return $pdf->download($filename);

        if ($save) {
            return redirect('pending-buyers')->with('success', 'Agreement has been recorded and plot been sold successfully');
        }

    }

    // SALES AND ACCOUNTING

    public function weeklyRecords()
    {

        $data = Carbon::now();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        $weeklyRecords = buyer::whereBetween('created_at', [$startOfWeek, $endOfWeek])->get();
        $totalAmount = buyer::whereBetween('created_at', [$startOfWeek, $endOfWeek])->sum('amount_payed');
        $plots_sold = buyer::whereBetween('created_at', [$startOfWeek, $endOfWeek])->where('next_installment_pay', "Fully payed")->count();
        $under_payment_plots = buyer::whereBetween('created_at', [$startOfWeek, $endOfWeek])->where('next_installment_pay', '!=', "Fully payed")->where('next_installment_pay', '!=', "Resold")->count();

        return view('Admin.Sales.weekly', $data, compact(['weeklyRecords', 'totalAmount', 'plots_sold', 'under_payment_plots']));

    }

    public function different_weekly_sales_collection()
    {


    }

    public function all_sales()
    {

        // $all_sales = buyer::all();
        $all_sales = Buyer::orderBy('created_at', 'desc')->paginate(10);

        $totalAmount = buyer::sum('amount_payed');
        $plots_sold = DB::table('buyers')->where('next_installment_pay', "Fully payed")->count();
        $under_payment_plots = DB::table('buyers')->where('next_installment_pay', '!=', "Fully payed")->where('next_installment_pay', '!=', "Resold")->count();

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        return view('Admin.customer_sales', $data, compact(['all_sales', 'totalAmount', 'plots_sold', 'under_payment_plots']));
    }

    public function recordsOnCurrentDate()
    {

        $currentDate = Carbon::today();
        $records = buyer::whereDate('created_at', $currentDate)->get();
        $totalAmount = buyer::whereDate('created_at', $currentDate)->sum('amount_payed');
        $plots_sold = buyer::whereDate('created_at', $currentDate)->where('next_installment_pay', "Fully payed")->count();
        $under_payment_plots = DB::table('buyers')->where('next_installment_pay', '!=', "Fully payed")->where('next_installment_pay', '!=', "Resold")->count();

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        return view('Admin.Sales.today', $data, compact(['records', 'totalAmount', 'plots_sold', 'under_payment_plots']));
    }

    public function recordsInCurrentMonth()
    {

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $records = buyer::whereYear('created_at', $currentYear)->whereMonth('created_at', $currentMonth)
            ->get();

        $totalAmount = buyer::whereYear('created_at', $currentYear)->whereMonth('created_at', $currentMonth)
            ->sum('amount_payed');

        $plots_sold = buyer::whereYear('created_at', $currentYear)->whereMonth('created_at', $currentMonth)
            ->where('next_installment_pay', "Fully payed")->count();

        $under_payment_plots = buyer::whereYear('created_at', $currentYear)->whereMonth('created_at', $currentMonth)
            ->where('next_installment_pay', '!=', "Fully payed")->where('next_installment_pay', '!=', "Resold")->count();

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        return view('Admin.Sales.monthly', $data, compact(['records', 'totalAmount', 'plots_sold', 'under_payment_plots']));

    }

    // PAYMENT REMINDERS

    public function searchByPaymentDate(Request $request)
    {
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->format('Y/m/d');

        $records = buyer::whereDate('next_installment_pay', $formattedDate)->get();
        $records_count = $records->count();

        $upcomingreminders = buyer::where('next_installment_pay', '!=', 'Fully payed')
            ->whereDate('next_installment_pay', '>', $formattedDate)->get();

        $expiredreminders = buyer::where('next_installment_pay', '!=', 'Fully payed')
            ->whereDate('next_installment_pay', '<', $formattedDate)->get();

        $data = [
            'LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()
        ];

        return view('Admin.Sales.payment_reminders', $data, compact(['records_count', 'records', 'upcomingreminders', 'expiredreminders']));
    }


    public function setReminder(Request $request)
    {
        $currentDate = Carbon::now();

        $formattedDate = $currentDate->format('Y/m/d');
        $records = buyer::whereDate('next_installment_pay', $formattedDate)->get();
        $records_count = buyer::whereDate('next_installment_pay', $formattedDate)->count();
        $estates = Estate::all();

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        return view('Admin.Sales.set-reminder', $data, compact(['records_count', 'records', 'estates']));
    }

    public function update_payment_reminder($id)
    {

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];
        $records = buyer::find($id);

        return view('Admin.Sales.update_payment_reminder', $data, compact(['records']));
    }

    public function store_update_payment_reminder(Request $request)
    {

        $status = $request->status;
        $reminder_date = $request->reminder_date;
        $user_id = $request->user_id;

        if ($status == 'Fully payed') {
            $update_buyer = buyer::where('id', $user_id)
                ->update([
                    'next_installment_pay' => $status,
                ]);
        } else {
            $update_buyer = buyer::where('id', $user_id)
                ->update([
                    'next_installment_pay' => $reminder_date,
                ]);
        }

        return redirect('payment-reminder')->with('success', 'Payment reminder has been updated successfully');
    }

    // Resale Module

    public function search_plot()
    {

        $records = Estate::all();
        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        return view('Admin.Resale.search_plot', $data, compact(['records']));
    }

    public function search_land_db(Request $request)
    {

        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $NIN = $request->NIN;
        $date_of_birth = $request->date_of_birth;
        $date_sold = $request->date_sold;
        $end_date = $request->end_date;

        $estate = $request->estate;
        $land_plot = $request->land_plot;

        if ($date_sold != null && $end_date != null) {

            $startDate = DB::table('buyers')->where('created_at', 'like', '%' . $date_sold . '%')->get();
            $endDate = DB::table('buyers')->where('created_at', 'like', '%' . $end_date . '%')->get();

            $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

            $mergedResults = $startDate->merge($endDate);

            return view('Admin.Search.multiple_results', $data, ['result' => $mergedResults]);
        } elseif ($date_sold != null && $end_date == null) {
            $mergedResults = DB::table('buyers')->where('created_at', 'like', '%' . $date_sold . '%')->get();
            $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

            return view('Admin.Search.multiple_results', $data, ['result' => $mergedResults]);
        } elseif ($date_sold == null && $end_date != null) {
            $mergedResults = DB::table('buyers')->where('created_at', 'like', '%' . $end_date . '%')->get();
            $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];
            return view('Admin.Search.multiple_results', $data, ['result' => $mergedResults]);
        }

        if ($firstname != null && $lastname == null) {

            $firstname = DB::table('buyers')->where('firstname', 'like', '%' . $firstname . '%')->get();
            $lastname = DB::table('buyers')->where('lastname', 'like', '%' . $firstname . '%')->get();

            $mergedResults = $firstname->merge($lastname);

            $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

            return view('Admin.Search.multiple_results', $data, ['result' => $mergedResults]);

        } elseif ($firstname == null && $lastname != null) {

            $firstname = DB::table('buyers')->where('firstname', 'like', '%' . $lastname . '%')->get();
            $lastname = DB::table('buyers')->where('lastname', 'like', '%' . $lastname . '%')->get();

            $mergedResults = $firstname->merge($lastname);

            $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

            return view('Admin.Search.multiple_results', $data, ['result' => $mergedResults]);
        }

        if ($firstname != null && $lastname != null) {

            $firstname = DB::table('buyers')->where('firstname', 'like', '%' . $firstname . '%')->get();
            $lastname = DB::table('buyers')->where('lastname', 'like', '%' . $lastname . '%')->get();
            $mergedResults = $firstname->merge($lastname);

            $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

            return view('Admin.Search.multiple_results', $data, ['result' => $mergedResults]);
        } else if ($NIN != null) {

            $result = DB::table('buyers')
                ->where('NIN', $NIN, )
                ->first();

            if (!$result) {
                return back()->with('error', 'No record has been found with provided information');
            } else {

                $id = $result->id;
                $user_information = DB::table('buyers')->where('id', '=', $id)->get();
                $user_reciepts = DB::table('pdf_receipts')->where('user_id', '=', $id)->get();
                $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

                $user_information = DB::table('buyers')->where('id', '=', $id)->get();
                $user_reciepts = DB::table('pdf_receipts')->where('user_id', '=', $id)->get();
                $user_agreements = DB::table('pdf_agreements')->where('user_id', '=', $id)->get();
                $user_reciepts_pdf = DB::table('reciepts')->where('user_id', '=', $id)->get();

                $agreement_reference_in_buyer = DB::table('buyers')->where('id', '=', $id)->value('agreement');
                $user_agreements_uploaded = DB::table('agreements')->where('user_id', '=', $agreement_reference_in_buyer)->get();
                $user_agreements_pdf = DB::table('agreements')->where('user_id', '=', $id)->get();

                return view('Admin.Resale.show_info', $data, compact(['user_information', 'user_reciepts', 'id', 'user_agreements', 'user_reciepts_pdf', 'user_agreements_pdf', 'user_agreements_uploaded']));

            }
        } else if ($estate != null) {

            // $result = DB::table('buyers')
            //     ->where('estate', $estate, )
            //     ->where('plot_number', $land_plot, )
            //     ->first();

            $result = DB::table('buyers')
                ->where('estate', $estate, )
                ->where('plot_number', $land_plot, )
                ->get();

            if (!$result) {
                return back()->with('error', 'No Plot has been found with provided information');
            } else {

                // $id = $result->id;
                // $user_information = DB::table('buyers')->where('id', '=', $id)->get();
                // $user_reciepts = DB::table('pdf_receipts')->where('user_id', '=', $id)->get();
                // $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

                // $user_information = DB::table('buyers')->where('id', '=', $id)->get();
                // $user_reciepts = DB::table('pdf_receipts')->where('user_id', '=', $id)->get();
                // $user_agreements = DB::table('pdf_agreements')->where('user_id', '=', $id)->get();
                // $user_reciepts_pdf = DB::table('reciepts')->where('user_id', '=', $id)->get();

                // $agreement_reference_in_buyer = DB::table('buyers')->where('id', '=', $id)->value('agreement');
                // $user_agreements_uploaded = DB::table('agreements')->where('user_id', '=', $agreement_reference_in_buyer)->get();
                // $user_agreements_pdf = DB::table('agreements')->where('user_id', '=', $id)->get();

                // return view('Admin.Resale.show_info', $data, compact(['user_information', 'user_reciepts', 'id', 'user_agreements', 'user_reciepts_pdf', 'user_agreements_pdf', 'user_agreements_uploaded']));

                $mergedResults = $result;

                $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

                return view('Admin.Search.multiple_results', $data, ['result' => $mergedResults]);

            }
        } else if ($date_of_birth != null) {

            $result = DB::table('buyers')
                ->where('date_of_birth', $date_of_birth, )
                ->first();

            if (!$result) {
                return back()->with('error', 'No Plot has been found with provided information');
            } else {

                $id = $result->id;
                $user_information = DB::table('buyers')->where('id', '=', $id)->get();
                $user_reciepts = DB::table('pdf_receipts')->where('user_id', '=', $id)->get();
                $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

                $user_information = DB::table('buyers')->where('id', '=', $id)->get();
                $user_reciepts = DB::table('pdf_receipts')->where('user_id', '=', $id)->get();
                $user_agreements = DB::table('pdf_agreements')->where('user_id', '=', $id)->get();
                $user_reciepts_pdf = DB::table('reciepts')->where('user_id', '=', $id)->get();

                $agreement_reference_in_buyer = DB::table('buyers')->where('id', '=', $id)->value('agreement');
                $user_agreements_uploaded = DB::table('agreements')->where('user_id', '=', $agreement_reference_in_buyer)->get();
                $user_agreements_pdf = DB::table('agreements')->where('user_id', '=', $id)->get();

                return view('Admin.Resale.show_info', $data, compact(['user_information', 'user_reciepts', 'id', 'user_agreements', 'user_reciepts_pdf', 'user_agreements_pdf', 'user_agreements_uploaded']));

            }
        }
    }

    public function search_clients_to_setup_reminders(Request $request)
    {

        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $date_sold = $request->date_sold;
        $end_date = $request->end_date;

        $estate = $request->estate;
        $land_plot = $request->land_plot;

        if ($date_sold != null && $end_date != null) {

            $startDate = DB::table('buyers')->where('created_at', 'like', '%' . $date_sold . '%')->get();
            $endDate = DB::table('buyers')->where('created_at', 'like', '%' . $end_date . '%')->get();

            $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

            $mergedResults = $startDate->merge($endDate);

            return view('Admin.Search.multiple_results_reminder_set_up', $data, ['result' => $mergedResults]);
        } elseif ($date_sold != null && $end_date == null) {
            $mergedResults = DB::table('buyers')->where('created_at', 'like', '%' . $date_sold . '%')->get();
            $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

            return view('Admin.Search.multiple_results_reminder_set_up', $data, ['result' => $mergedResults]);
        } elseif ($date_sold == null && $end_date != null) {
            $mergedResults = DB::table('buyers')->where('created_at', 'like', '%' . $end_date . '%')->get();
            $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];
            return view('Admin.Search.multiple_results_reminder_set_up', $data, ['result' => $mergedResults]);
        }

        if ($firstname != null && $lastname == null) {

            $firstname = DB::table('buyers')->where('firstname', 'like', '%' . $firstname . '%')->get();
            $lastname = DB::table('buyers')->where('lastname', 'like', '%' . $firstname . '%')->get();

            $mergedResults = $firstname->merge($lastname);

            $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

            return view('Admin.Search.multiple_results_reminder_set_up', $data, ['result' => $mergedResults]);

        } elseif ($firstname == null && $lastname != null) {

            $firstname = DB::table('buyers')->where('firstname', 'like', '%' . $lastname . '%')->get();
            $lastname = DB::table('buyers')->where('lastname', 'like', '%' . $lastname . '%')->get();

            $mergedResults = $firstname->merge($lastname);

            $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

            return view('Admin.Search.multiple_results_reminder_set_up', $data, ['result' => $mergedResults]);
        }

        if ($firstname != null && $lastname != null) {

            $firstname = DB::table('buyers')->where('firstname', 'like', '%' . $firstname . '%')->get();
            $lastname = DB::table('buyers')->where('lastname', 'like', '%' . $lastname . '%')->get();
            $mergedResults = $firstname->merge($lastname);

            $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

            return view('Admin.Search.multiple_results_reminder_set_up', $data, ['result' => $mergedResults]);

        } else if ($estate != null) {

            $result = DB::table('buyers')
                ->where('estate', $estate, )
                ->where('plot_number', $land_plot, )
                ->first();

            if (!$result) {
                return back()->with('error', 'No Plot has been found with provided information');
            } else {

                $id = $result->id;
                $user_information = DB::table('buyers')->where('id', '=', $id)->get();
                $user_reciepts = DB::table('pdf_receipts')->where('user_id', '=', $id)->get();
                $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

                $user_information = DB::table('buyers')->where('id', '=', $id)->get();
                $user_reciepts = DB::table('pdf_receipts')->where('user_id', '=', $id)->get();
                $user_agreements = DB::table('pdf_agreements')->where('user_id', '=', $id)->get();
                $user_reciepts_pdf = DB::table('reciepts')->where('user_id', '=', $id)->get();

                $agreement_reference_in_buyer = DB::table('buyers')->where('id', '=', $id)->value('agreement');
                $user_agreements_uploaded = DB::table('agreements')->where('user_id', '=', $agreement_reference_in_buyer)->get();
                $user_agreements_pdf = DB::table('agreements')->where('user_id', '=', $id)->get();

                $firstname = DB::table('buyers')->where('id', '=', $id)->get();

                $mergedResults = $firstname;

                return view('Admin.Search.multiple_results_reminder_set_up', $data, ['result' => $mergedResults]);
            }
        }
    }


    public function search_plot_land_db(Request $request)
    {

        $status = $request->land_plot;
        $plot_no = $request->plot_no;
        $half_not_half = $request->half_not_half;

        if ($status == 'House') {
            $land_estate = $request->land_estate;
            $result = DB::table('buyers')
                ->where('purchase_type', $status, )
                ->where('estate', $land_estate, )
                ->where('plot_number', $plot_no)
                ->first();

            if (!$result) {
                return back()->with('error', 'No House has been found with provided information');
            } else {
                $user_id = $result->id;

                $user_information = DB::table('buyers')->where('id', '=', $id)->get();
                $user_reciepts = DB::table('pdf_receipts')->where('user_id', '=', $id)->get();

                $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

                return view('Admin.Search.underpayment', $data, compact(['user_information', 'user_reciepts', 'id']));
            }
        } else {

            $land_estate = $request->estate;

            if ($half_not_half == 1) {
                $result = DB::table('buyers')
                    ->where('purchase_type', $status)
                    ->where('estate', $land_estate)
                    ->where('plot_number', $plot_no)
                    ->orderBy('id', 'desc')
                    ->first();

                if (!$result) {
                    return back()->with('error', 'No Plot has been found with provided information');
                } else {

                    $id = $result->id;
                    $user_information = DB::table('buyers')->where('id', '=', $id)->get();
                    $user_reciepts = DB::table('pdf_receipts')->where('user_id', '=', $id)->get();
                    $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

                    return view('Admin.Search.underpayment', $data, compact(['user_information', 'user_reciepts', 'id']));

                }
            } else {

                $plot_no = $plot_no . ' HALF';

                $result = DB::table('buyers')
                    ->where('purchase_type', $status)
                    ->where('estate', $land_estate)
                    ->where('plot_number', $plot_no)
                    ->where('next_installment_pay', 'Fully payed')
                    ->orderBy('id', 'desc')
                    ->first();

                if (!$result) {
                    return back()->with('error', 'No Plot has been found with provided information');
                } else {

                    $id = $result->id;
                    $user_information = DB::table('buyers')->where('id', '=', $id)->get();
                    $user_reciepts = DB::table('pdf_receipts')->where('user_id', '=', $id)->get();
                    $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

                    return view('Admin.Search.underpayment', $data, compact(['user_information', 'user_reciepts', 'id']));

                }
            }
        }
    }

    public function resale($id)
    {

        $user_information = DB::table('buyers')->where('id', '=', $id)->get();

        $user_reciepts = DB::table('pdf_receipts')->where('user_id', '=', $id)->get();
        $user_agreements = DB::table('pdf_agreements')->where('user_id', '=', $id)->get();
        $user_reciepts_pdf = DB::table('reciepts')->where('user_id', '=', $id)->get();

        $agreement_reference_in_buyer = DB::table('buyers')->where('id', '=', $id)->value('agreement');
        $user_agreements_uploaded = DB::table('agreements')->where('user_id', '=', $agreement_reference_in_buyer)->get();
        $user_agreements_pdf = DB::table('agreements')->where('user_id', '=', $id)->get();

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        return view('Admin.Resale.resale', $data, compact(['user_information', 'user_reciepts', 'user_agreements', 'user_reciepts_pdf', 'user_agreements_pdf', 'user_agreements_uploaded']));
    }

    public function resale_amount($id)
    {

        $user_id = $id;
        $asset_info = DB::table('buyers')->where('id', '=', $id)->first();
        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];
        return view('Admin.Resale.resale_amount', $data, compact(['asset_info', 'user_id']));
    }

    public function store_resale_amount(Request $request)
    {

        $user_id = $request->user_id;
        $purchase_type = $request->purchase_type;
        $estate = $request->estate;
        $plot_no = $request->plot_no;
        $amount_resold = str_replace(',', '', $request->amount_resold);
        $amount_to_be_sold = str_replace(',', '', $request->amount_to_be_sold);

        $post = new resale;

        $post->user_id = $user_id;
        $post->purchase_type = $purchase_type;
        $post->estate = $estate;
        $post->plot_number = $plot_no;
        $post->reciept_resold = '-';

        $post->amount_resold = $amount_resold;
        $post->amount_to_be_sold = $amount_to_be_sold;
        $post->paid_cash = $request->payment_method;

        if ($request->payment_method == 1) {
            $files = $request->file('seller_agreeement');
            $fileNames = [];

            if ($files) {
                foreach ($files as $file) {
                    $filename = date('YmdHi') . $file->getClientOriginalName();
                    $file->move(public_path('public/resoldPlots'), $filename);
                    $fileNames[] = $filename; // Store each file name in the array
                }

                // Convert array of filenames to a string, for example, comma-separated
                $post->seller_agreeement = implode(',', $fileNames);
            } else {
                $post->seller_agreeement = '-';
            }
        } else {
            $post->seller_agreeement = '-';
        }

        $save = $post->save();

        $whereConditions = [
            'estate' => $estate,
            'plot_number' => $plot_no,
        ];

        $buyerWhereConditions = [
            'id' => $user_id,
            'estate' => $estate,
            'plot_number' => $plot_no,
        ];

        DB::table('plots')
            ->where($whereConditions)
            ->update([
                'status' => 'Not taken',
                'exceptional_status' => 'Yes',
                'exceptional_amount' => $amount_to_be_sold,
                'buyer_id' => 0,
            ]);

        DB::table('buyers')
            ->where($buyerWhereConditions)
            ->update([
                'back_on_market_status' => 1
            ]);

        if ($save) {
            return redirect('search-land')->with('success', 'Reselling has been accomplished successfully, plot is back on market');
        } else {
            return redirect('search-land')->with('error', 'Reselling has not been accomplished ');
        }
    }

    // Load data dynamically.

    public function get_secound_option(Request $request)
    {
        $info = $request->input('value');

        $whereConditions = ['estate' => $info];

        $not_fully_paid = DB::table('plots')->where($whereConditions)
            ->where('status', '!=', 'Fully payed')
            ->get();

        $fully_paid_half = DB::table('plots')->where($whereConditions)
            ->where('status', '=', 'Fully payed')
            ->where('half_or_full', '=', '1')
            ->get();

        $unmatchedPlots = [];

        // Implementation of the confirm agreement payment agreement :

        foreach ($not_fully_paid as $plot) {
            $buyer = DB::table('buyers')
                ->where('plot_number', $plot->plot_number)
                ->where('estate', $plot->estate)
                ->where('next_installment_pay', '!=', 'Fully payed')
                ->where('request_permission', 1)
                ->first();

            if (!$buyer) {
                $unmatchedPlots[] = $plot;
            }
        }

        $unmatchedPlotsCollection = collect($unmatchedPlots);

        $info = $unmatchedPlotsCollection->merge($fully_paid_half);

        $data = $info->all();

        return response()->json($data);
    }

    public function get_input_option(Request $request)
    {
        $info = $request->input('selectedValue');

        $whereConditions = ['plot_number' => $info];

        $width_1 = DB::table('plots')->where($whereConditions)->value('width_1');
        $width_2 = DB::table('plots')->where($whereConditions)->value('width_2');
        $height_1 = DB::table('plots')->where($whereConditions)->value('height_1');
        $height_2 = DB::table('plots')->where($whereConditions)->value('height_2');
        $location = DB::table('plots')->where($whereConditions)->value('location');

        return response()->json([
            "status" => true,
            "width_1" => $width_1,
            "width_2" => $width_2,
            "height_1" => $height_1,
            "height_2" => $height_2,
            "location" => $location,
        ]);
    }

    // Generate Invoice and Agreement

    public function generate_invoice()
    {

        $pdf = PDF::loadView('invoice_pdf');
        return $pdf->download('techsolutionstuff.pdf');

    }

    public function show_invoice()
    {
        return view('invoice_pdf');
    }

    public function attach_agreement_view($id)
    {

        $user_id = $id;
        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        return view('Admin.Receipts.attach_agreement_page', $data, compact(['user_id']));
    }

    public function store_agreement_new(Request $request)
    {

        $request->validate([
            'agreement' => 'required',
        ]);

        $post = new pdf_agreements;

        $post->user_id = $request->user_id;

        $file = $request->agreement;
        $filename = date('YmdHi') . $file->getClientOriginalName();
        $file->move(public_path('public/agreements'), $filename);
        $post->agreement = $filename;

        $post->save();

        return redirect('accomplished')->with('success', 'Agreement has been uploaded successfully');
    }

    public function attach_receipt_view($id)
    {

        $user_id = $id;
        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        return view('Admin.Receipts.attach_receipt_page', $data, compact(['user_id']));
    }

    public function store_attach_receipt_new(Request $request)
    {

        $request->validate([
            'receipt' => 'required',
        ]);

        $post = new pdf_receipt;

        $post->user_id = $request->user_id;

        $file = $request->receipt;
        $filename = date('YmdHi') . $file->getClientOriginalName();
        $file->move(public_path('public/receipts'), $filename);
        $post->receipt = $filename;

        $post->save();

        return redirect('pending-buyers')->with('success', 'Receipt has been uploaded successfully');
    }

    public function download_agreement_receipt(Request $request, $Book_pdf)
    {

        $filePath = 'pdf_receipts/' . $Book_pdf;

        if (Storage::exists($filePath)) {
            return response()->download(storage_path($filePath));
        } else {
            abort(404, 'File not found');
        }
    }

    public function download_receipt_payment($filename)
    {
        return response()->download(storage_path('public/pdf_receipts/' . $filename));
    }

    public function add_expenditure()
    {
        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        return view('Admin.Expenditure.add_expenditure', $data);
    }

    public function store_expenditure(Request $request)
    {
        $added_amount = 0;
        $total_amount = $request->total_amount;
        $expenditure_name = $request->expenditure_name;

        $dynamicInputsAmounts = $request->input('dynamic_inputs2', []);

        foreach ($dynamicInputsAmounts as $value) {
            $added_amount += $value;
        }

        if ($added_amount > $total_amount) {
            return back()->with('error', 'Added amount is greater than total amount submitted');
        } else if ($added_amount < $total_amount) {
            return back()->with('error', 'Added amount is less than total amount submitted');
        } else if ($added_amount != $total_amount) {

            return back()->with('error', 'Invalid amount and total expenses provided');

        }
        $random_numb = rand(10000, 50000);

        $post = new expenditure;

        $post->random_numb = $random_numb;
        $post->service = $expenditure_name;
        $post->amount = $total_amount;

        $post->save();

        $dynamicInputs = $request->input('dynamic_inputs', []);

        foreach ($dynamicInputs as $value) {

            $post = new expenditure_service;

            $post->random_number = $random_numb;
            $post->all_services = $value;

            $post->save();
        }

        return back()->with('success', 'Expenditure has been added successfully');
    }

    public function today_expense()
    {

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        $currentDate = Carbon::today();
        $totalExpenditureAll = expenditure::whereDate('created_at', $currentDate)->get();
        $totalExpenditureServices = expenditure_service::whereDate('created_at', $currentDate)->get();

        $totalExpenditureServices = $totalExpenditureServices->groupBy('random_number');

        $totalAmount = expenditure::whereDate('created_at', $currentDate)->sum('amount');

        $totalExpenditure = expenditure::whereDate('created_at', $currentDate)->count();

        return view('Admin.Expenditure.today_expenditure', $data, compact(['totalExpenditureAll', 'totalAmount', 'totalExpenditure', 'totalExpenditureServices']));
    }

    // SEARCH MODULE

    public function search_module()
    {

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        $records = Estate::all();

        return view('Admin.Search.search_module', $data, compact(['records']));
    }

    public function download_national_id(Request $request, $id)
    {

        $user_id = $id;

        $user_info = buyer::where('id', $user_id)->first();
        $national_id_front = buyer::where('id', $user_id)->value('national_id_front');
        $national_id_back = buyer::where('id', $user_id)->value('national_id_back');

        $national_id_front = public_path('public/national_id/' . $national_id_front);
        $national_id_back = public_path('public/national_id/' . $national_id_back);

        $national_id_front = $national_id_front;
        $national_id_back = $national_id_back;

        $pdf = PDF::loadView('national_id', compact(['national_id_front', 'national_id_back']));

        return $pdf->download('national_id.pdf');

    }

    public function all_plots_in_estate($id)
    {

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        $specific_estate = Estate::find($id);
        $estate_id = $id;
        $estate_name = $specific_estate->estate_name;

        $estate_pdf_info = $specific_estate->estate_pdf;

        $estate_data = plot::where('estate', $estate_name)
            ->orderBy('plot_number', 'asc')
            ->get();

        $count_estates_fully = DB::table('buyers')
            ->where('estate', '=', $estate_name)
            ->where('next_installment_pay', '=', "Fully payed")->count();

        return view('Admin.total_plots_list_in_estate', $data, compact(['specific_estate', 'count_estates_fully', 'estate_id', 'estate_name', 'estate_data', 'estate_pdf_info']));
    }

    public function total_fully_paid_plots_in_estate($id)
    {
        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        $specific_estate = Estate::find($id);
        $estate_id = $id;
        $estate_name = $specific_estate->estate_name;

        $estate_pdf_info = $specific_estate->estate_pdf;

        $estate_data = buyer::where('estate', $estate_name)
            ->where('next_installment_pay', '=', 'Fully payed')
            ->orderBy('plot_number', 'asc')
            ->get();

        $count_estates_fully = plot::where('estate', $estate_name)
            ->where('status', '=', 'Fully payed')->count();

        return view('Admin.total_fully_paid_plots_in_estate', $data, compact(['specific_estate', 'count_estates_fully', 'estate_id', 'estate_name', 'estate_data', 'estate_pdf_info']));
    }

    public function total_not_taken_plots_in_estate($id)
    {

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        $specific_estate = Estate::find($id);
        $estate_id = $id;
        $estate_name = $specific_estate->estate_name;

        $estate_pdf_info = $specific_estate->estate_pdf;

        $estate_data = plot::where('estate', $estate_name)
            ->where('status', '=', 'Not taken')->orderBy('plot_number', 'asc')->get();

        // $count_estates_fully = plot::where('estate', $estate_name)
        //     ->where('status', '=', 'Not taken')->count();

        foreach ($estate_data as $plot) {
            $buyer = DB::table('buyers')
                ->where('plot_number', $plot->plot_number)
                ->where('estate', $plot->estate)
                ->where('next_installment_pay', '!=', 'Fully payed')
                ->where('request_permission', 1)
                ->first();

            if (!$buyer) {
                $unmatchedPlots[] = $plot;
            }
        }

        $estate_data = $unmatchedPlots;
        $count_estates_fully = count($unmatchedPlots);

        $estate_price = estate::where('estate_name', $estate_name)->value('estate_price');

        return view('Admin.total_not_taken_plots_in_estate', $data, compact(['specific_estate', 'count_estates_fully', 'estate_id', 'estate_name', 'estate_data', 'estate_pdf_info', 'estate_price']));
    }

    public function enter_saved_estate()
    {
        $estate_names = Estate::all()->pluck('estate_name');

        foreach ($estate_names as $estate_name) {

            $estate_records = buyer::where('estate', $estate_name)->get();

            foreach ($estate_records as $estate_record) {

                $estate_record_plot_number = buyer::where('id', $estate_record->id)->value('plot_number');

                $existing_record = plot::where('plot_number', $estate_record_plot_number)
                    ->where('estate', '=', $estate_name)->first();

                if ($existing_record == null) {

                    $post = new plot;

                    $post->estate = $estate_name;
                    $post->plot_number = $estate_record->plot_number;
                    $post->location = $estate_record->location;
                    $post->width_1 = $estate_record->width_1;
                    $post->width_2 = $estate_record->width_2;
                    $post->height_1 = $estate_record->height_1;
                    $post->height_2 = $estate_record->height_2;
                    $post->buyer_id = $estate_record->id;
                    $post->status = $estate_record->next_installment_pay;
                    $post->exceptional_status = "No";
                    $post->exceptional_amount = "0";
                    $post->half_or_full = "0";
                    $post->save();

                } else {
                    continue;
                }
            }
        }

        dd('Data has been saved successfully in the db');
    }

    public function view_estate_pdf($id, $estate)
    {

        $estate_name = buyer::where('id', $id)->value('estate');
        $estate_record = estate::where('estate_name', $estate)->value('estate_pdf');

        return response()->download(public_path('estate_pdf/' . $estate_record));
    }

    public function back_on_market()
    {

        $count_resold_to_company_in_cash = resale::where('paid_cash', '=', 1)->count();
        $count_sell_for_client_not_in_cash = resale::where('paid_cash', '=', 0)->count();

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];
        return view('Admin.Resale.Backonmarket', $data, compact(['count_resold_to_company_in_cash', 'count_sell_for_client_not_in_cash']));

    }


    public function back_on_market_all(Request $request)
    {
        $query = resale::query();

        $filterType = $request->input('filter_type');

        // Estate filter
        if ($filterType === 'estate' && $request->filled('estate')) {
            $query->where('estate', $request->estate);
        }

        // Date filter
        if ($filterType === 'date' && $request->filled('filter_date')) {
            try {
                $date = Carbon::parse($request->filter_date)->toDateString();
                $query->whereDate('created_at', $date);
            } catch (\Exception $e) {
                // Optional: log error or notify user
            }
        }

        // Name filter (via buyer_id from hidden input)
        if ($filterType === 'name' && $request->filled('buyer_id')) {
            $query->where('user_id', $request->buyer_id);
        }

        $allResales = $query->get();
        $estates = Estate::all();

        return view('Admin.Resale.back-on-market-all', [
            'LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first(),
            'allResales' => $allResales,
            'estates' => $estates,
            'selectedEstate' => $request->estate,
            'filterType' => $filterType,
            'filterDate' => $request->filter_date,
            'buyer_id' => $request->buyer_id, // ✅ required to repopulate selected buyer
        ]);
    }


    public function searchBuyers(Request $request)
    {
        $search = $request->input('query');

        $buyers = DB::table('buyers')
            ->where('firstname', 'like', "%{$search}%")
            ->orWhere('lastname', 'like', "%{$search}%")
            ->select('id', 'firstname', 'lastname')
            ->limit(10)
            ->get();

        return response()->json($buyers);
    }


    public function back_for_client_on_sale()
    {

        $paid_in_cash = Resale::where('paid_cash', '=', 1)
            ->orderBy('estate', 'asc')
            ->orderBy('plot_number', 'asc')
            ->get();

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];
        return view('Admin.Resale.back_for_client_on_sale', $data, compact(['paid_in_cash']));
    }

    public function back_for_company_on_sale()
    {

        $paid_not_in_cash = Resale::where('paid_cash', '=', 0)
            ->orderBy('estate', 'asc')
            ->orderBy('plot_number', 'asc')
            ->get();

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];
        return view('Admin.Resale.back_for_company_on_sale', $data, compact(['paid_not_in_cash']));
    }

    public function user_right_info()
    {

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];
        $user_id = session('LoggedAdmin');

        $user_category = AdminRegister::where('id', '=', $user_id)->value('admin_category');

        return $user_category;
    }

    // User rights and previledges

    public function userInformation()
    {

        $user_records = AdminRegister::all();
        $user_records_count = AdminRegister::all()->count();

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        return view('Admin.Users.users', $data, compact(['user_records', 'user_records_count']));
    }

    public function deleteUser($id)
    {
        $record = AdminRegister::find($id);
        $record->delete();

        return redirect()->back()->with('success', 'User has been deleted successfully');
    }

    public function editUser($id)
    {

        $record = AdminRegister::find($id);
        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        return view('Login.editUser', $data, compact(['record']));
    }

    public function storeUserRecord(Request $request)
    {

        $request->validate([
            'username' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email',
            'admin_category' => 'required',
            'password' => 'required',
            'confirm_password' => 'required',
        ]);

        $original_pass = $request->password;
        $confirm_pass = $request->confirm_password;

        if ($original_pass === $confirm_pass) {

            $record_id = $request->record_id;

            $register_admin = AdminRegister::find($record_id);

            $register_admin->username = $request->username;
            $register_admin->firstname = $request->firstname;
            $register_admin->lastname = $request->lastname;
            $register_admin->email = $request->email;
            $register_admin->password = Hash::make($request->password);
            $register_admin->admin_category = $request->admin_category;
            $register_admin->added_by = Session('LoggedAdmin');

            $save = $register_admin->save();

            if ($save) {

                return redirect('user-information')->with('success', 'User Information has been updated successfully in the Systems');
            }

        } else {
            return back()->with('fail', 'Password is not the same as confirm password')->withInput();
        }
    }

    public function updateReminder($id)
    {

        $result = DB::table('buyers')->where('id', $id)->get();

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        return view('update_reminder', $data, compact(['result', 'id']));
    }

    public function saveUpdateReminder(Request $request)
    {

        $user_id = $request->user_id;
        $updated_reminder_date = $request->updated_reminder_date;

        DB::table('buyers')
            ->where('id', $user_id)
            ->update(['next_installment_pay' => $updated_reminder_date]);

        return back()->with('success', 'Installement date has been updated successfully');

    }

    // Poster Methods and Functions

    public function all_posters()
    {

        $estates = Estate::all();

        $Plots_with_posters = buyer::where('plot_poster', 0)->count();
        $Plots_without_posters = buyer::where('plot_poster', 1)->count();

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        return view('Admin.Posters.all_posters', $data, compact(['estates', 'Plots_with_posters', 'Plots_without_posters']));
    }

    public function view_estate_poster($id)
    {

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        $specific_estate = Estate::find($id);
        $estate_id = $id;
        $estate_name = $specific_estate->estate_name;
        $estate_pdf_info = $specific_estate->estate_pdf;

        $estate_with_plot_posters = buyer::where('estate', $specific_estate->estate_name)
            ->where('plot_poster', 1)->count();

        $estate_without_plot_posters = buyer::where('estate', $specific_estate->estate_name)
            ->where('plot_poster', 0)->count();

        return view('Admin.Posters.view_specific_estate_posters', $data, compact([
            'specific_estate',
            'estate_id',
            'estate_name',
            'estate_with_plot_posters',
            'estate_without_plot_posters'
        ]));
    }

    public function all_plot_posters_in_estate($id)
    {
        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        $specific_estate = Estate::find($id);
        $estate_id = $id;
        $estate_name = $specific_estate->estate_name;

        $estate_pdf_info = $specific_estate->estate_pdf;

        $estate_data = buyer::where('estate', $specific_estate->estate_name)
            ->where('plot_poster', 1)->orderBy('plot_number', 'asc')->get();

        $total_plots_with_posters = buyer::where('estate', $specific_estate->estate_name)
            ->where('plot_poster', 1)->count();

        return view('Admin.Posters.all_plot_posters_in_estate', $data, compact(['specific_estate', 'estate_id', 'estate_name', 'estate_data', 'estate_pdf_info', 'total_plots_with_posters']));
    }

    public function all_plot_without_posters_in_estate($id)
    {
        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        $specific_estate = Estate::find($id);
        $estate_id = $id;
        $estate_name = $specific_estate->estate_name;

        $estate_pdf_info = $specific_estate->estate_pdf;

        $estate_data = buyer::where('estate', $specific_estate->estate_name)
            ->where('plot_poster', 0)->orderBy('plot_number', 'asc')->get();

        $total_plots_without_posters = buyer::where('estate', $specific_estate->estate_name)
            ->where('plot_poster', 0)->count();

        return view('Admin.Posters.all_plot_without_posters_in_estate', $data, compact(['specific_estate', 'estate_id', 'estate_name', 'estate_data', 'estate_pdf_info', 'total_plots_without_posters']));
    }

    public function save_plot_poster(Request $request)
    {
        $selected_items = $request->selected_items;

        if ($selected_items != null) {
            foreach ($selected_items as $key => $selected_item) {

                DB::table('buyers')->where('id', $selected_item)
                    ->update(['plot_poster' => 1]);
            }

            Alert::success('Plots selected have been provided with posters');

            return back()->with('success', 'Plots selected have been provided with posters');
        } else {
            return back()->with('fail', 'Please select atleast one plot');
        }
    }

    public function remove_poster_from_plots(Request $request)
    {
        $selected_items = $request->selected_items;

        if ($selected_items != null) {
            foreach ($selected_items as $key => $selected_item) {

                DB::table('buyers')->where('id', $selected_item)
                    ->update(['plot_poster' => 0]);
            }

            return back()->with('success', 'Posters have been removed from the selected plots');
        } else {
            return back()->with('fail', 'Please select atleast one plot');
        }
    }

    public function get_multiple_records()
    {

        $records = [];

        for ($counter = 1; $counter < 100; $counter++) {

            $one = DB::table('plots')
                ->where('estate', '=', 'Nakibano_1')
                ->where('status', '!=', 'Fully payed')
                ->where('plot_number', '=', $counter)->first();

            $two = DB::table('plots')
                ->where('estate', '=', 'Nakibano_1')
                ->where('status', '=', 'Fully payed')
                ->where('plot_number', '=', $counter)->first();

            if ($one != null && $two != null) {
                $records[] = $one;
            }
        }

        dd($records);
    }

    public function get_repeatitive_records()
    {

        $buyer_records = DB::table('buyers')->where('estate', '=', 'kiwafu_1')->pluck('plot_number');

        $records = [];
        $updatedRecords = [];

        foreach ($buyer_records as $buyer_record) {

            if (in_array($buyer_record, $records)) {

                $updatedRecords[] = $buyer_record;
            } else {

                $records[] = $buyer_record;
            }
        }

        if (!empty($updatedRecords)) {
            echo "Duplicates found: " . implode(", ", array_unique($updatedRecords));
        } else {
            echo "No duplicates found.";
        }

    }

    public function clearenceUserAgreement($userId)
    {

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        return view('Admin.Resale.later_user_agreement', $data, compact(['userId']));
    }

    public function attachSellerAgreement(Request $request)
    {
        // Find the user's existing resale record
        $user = DB::table('resales')->where('user_id', $request->user_id)->first();
        $post = resale::find($user->id);

        // Initialize an array to store file names
        $fileNames = [];

        // Loop through the uploaded files
        if ($request->hasFile('seller_agreeement')) {
            foreach ($request->file('seller_agreeement') as $file) {
                $filename = date('YmdHi') . $file->getClientOriginalName();
                $file->move(public_path('public/resoldPlots'), $filename);
                $fileNames[] = $filename; // Store each file name in the array
            }

            // Convert array of filenames to a comma-separated string
            $post->seller_agreeement = implode(',', $fileNames);
        } else {
            $post->seller_agreeement = '-'; // Set default value if no files are uploaded
        }

        // Save the updated record
        $save = $post->save();

        if ($save) {
            return redirect('back-for-company-on-sale')->with('success', 'Plot has been cleared successfully and seller agreement has been uploaded successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to upload seller agreement.');
        }
    }

    public function view_reciept_info($plot_id, $estate)
    {

        $user_information = DB::table('buyers')
            ->where('plot_number', '=', $plot_id)
            ->where('estate', '=', $estate)->get();

        $id = DB::table('buyers')->where('plot_number', '=', $plot_id)
            ->where('estate', '=', $estate)->value('id');

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];
        $user_reciepts = DB::table('pdf_receipts')->where('user_id', '=', $id)->get();
        $user_reciepts_pdf = DB::table('reciepts')->where('user_id', '=', $id)->get();
        $user_agreements = DB::table('pdf_agreements')->where('user_id', '=', $id)->get();

        $agreement_reference_in_buyer = DB::table('buyers')->where('id', '=', $id)->value('agreement');
        $user_agreements_uploaded = DB::table('agreements')->where('user_id', '=', $agreement_reference_in_buyer)->get();
        $user_agreements_pdf = DB::table('agreements')->where('user_id', '=', $id)->get();

        $receiptId = DB::table('reciepts')->where('user_id', '=', $id)->value('id');

        $user_resell_agreement = DB::table('resales')->where('user_id', '=', $id)->get();

        return view('Admin.Receipts.view_agreement', $data, compact(['user_information', 'user_reciepts', 'user_agreements', 'user_reciepts_pdf', 'user_agreements_pdf', 'user_agreements_uploaded']));

    }

    public function showReceipt($receiptId)
    {
        $receipt = reciept::findOrFail($receiptId);
        $pdfPath = asset("storage/pdf_receipts/{$receipt->reciept}");

        if (file_exists(storage_path("app/public/pdf_receipts/{$receipt->reciept}"))) {
            return view('show_receipt', compact('pdfPath'));
        } else {
            return "File not found!";
        }
    }

    public function editPlotInformation($plotId)
    {

        $plotInformation = plot::find($plotId);

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        return view('Admin.Edit.edit-plot-information', $data, compact(['plotInformation', 'plotId']));
    }

    public function storePlotUpdatedInformation(Request $request)
    {

        $estateId = DB::table('estates')->where('estate_name', $request->estateName)->value('id');

        $post = plot::find($request->plotId);

        $post->width_1 = $request->width1;
        $post->width_2 = $request->width2;
        $post->height_1 = $request->height1;
        $post->height_2 = $request->height2;
        $post->exceptional_amount = $request->exceptional_amount;

        $post->save();

        return redirect()->route('total-not-taken-plots-in-estate', ['id' => $estateId])
            ->with('success', 'Plot information updated successfully.');
    }

    public function editEstateInformation($estateId)
    {

        $estateInformation = estate::find($estateId);
        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        return view('Admin.Edit.edit-estate-information', $data, compact(['estateInformation', 'estateId']));
    }

    public function storeEstateUpdatedInformation(Request $request)
    {

        $post = estate::find($request->estateId);

        $post->estate_name = $request->estateName;
        $post->estate_price = $request->estatPrice;
        $post->location = $request->location;
        $post->number_of_plots = $request->numberofPlot;

        $post->save();

        return redirect()->route('view-estate', ['id' => $request->estateId])
            ->with('success', 'Estate information updated successfully.');
    }

    public function privacyPolicy()
    {
        return view('Admin.privacy-policy');
    }

    public function add_house()
    {

        $estates = Estate::all();

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        return view('Admin.addhouse', $data, compact(['estates']));
    }

    public function send_house_data(Request $request)
    {
        $record = new House();

        $record->price = $request->price;
        $record->location = $request->location;
        $record->width1 = $request->width1;
        $record->width2 = $request->width2;
        $record->height1 = $request->height1;
        $record->height2 = $request->height2;
        $record->LandTenure = $request->LandTenure;
        $record->bedroom = $request->bedroom;
        $record->purchase_procedure = $request->purchase_procedure;
        if ($request->amenities !== null) {
            $record->amenities = is_array($request->amenities) ? implode(',', $request->amenities) : $request->amenities;
        } else {
            $record->amenities = '-';
        }
        $record->status = 0;

        $agreementPaths = [];
        if ($request->hasFile('agreements')) {
            foreach ($request->file('agreements') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('uploads/agreements', 'public');
                    $agreementPaths[] = $path;
                }
            }
        }

        $houseImagePaths = [];
        if ($request->hasFile('housePics')) {
            foreach ($request->file('housePics') as $file) {
                if ($file->isValid()) {
                    $path = $file->move(public_path('uploads/housePics'), $file->getClientOriginalName());
                    $houseImagePaths[] = 'uploads/housePics/' . $file->getClientOriginalName();
                }
            }
        }

        $record->agreement_files = json_encode($agreementPaths);
        $record->house_images = json_encode($houseImagePaths);

        $record->save();

        return response()->json([
            'status' => 'success',
            'message' => 'House added successfully!',
        ]);
    }

    public function adminSellHouseFetch(Request $request)
    {
        $estates = Estate::all();
        $plots = DB::table('plots')->where('status', '!=', "Fully payed")->get();
        $data = ['LoggedAdminInfo' => AdminRegister::where('id', session('LoggedAdmin'))->first()];
        $User_access_right = $this->user_right_info();

        if (!in_array($User_access_right, ['SuperAdmin', 'Admin', 'Sales'])) {
            return redirect('estates');
        }

        $query = House::query();

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('min_price')) {
            $price = $request->min_price;
            $query->where('price', '>=', $price);
        }

        if ($request->filled('max_price')) {
            $price = $request->max_price;
            $query->where('price', '<=', $price);
        }

        if ($request->filled('bedroom')) {
            $query->where('bedroom', $request->bedroom);
        }

        if ($request->filled('land_tenure')) {
            $query->where('LandTenure', $request->land_tenure);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $houses = $query->orderBy('id', 'desc')->paginate(50);

        if ($request->ajax()) {
            return response()->view('Admin.house-images', compact('houses'), 200)
                ->header('X-Partial', 'true');
        }

        return view('Admin.house-images', $data, compact('estates', 'plots', 'houses'));
    }

    public function showHouseInfo($id)
    {
        $house = house::findOrFail($id);
        return view('Admin.show-house-information', compact('house'));
    }

    public function store_buyer_house_details(Request $request)
    {

        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'gender' => 'required|string',
            'date_of_birth' => 'required|date',
            'NIN' => 'required|string|max:255',
            'card_number' => 'required|string|max:255',
            'phonenumber' => 'required|string|max:255',
            'national_id_front' => 'required|file|mimes:jpg,png,jpeg|max:2048',
            'national_id_back' => 'required|file|mimes:jpg,png,jpeg|max:2048',
            'profile_pic' => 'required|file|mimes:jpg,png,jpeg|max:2048',
        ]);

        $nationalIdFrontPath = null;
        if ($request->hasFile('national_id_front') && $request->file('national_id_front')->isValid()) {
            $file = $request->file('national_id_front');
            $filename = time() . '_front_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/houseBuyerIds'), $filename);
            $nationalIdFrontPath = 'uploads/houseBuyerIds/' . $filename;
        }

        $nationalIdBackPath = null;
        if ($request->hasFile('national_id_back') && $request->file('national_id_back')->isValid()) {
            $file = $request->file('national_id_back');
            $filename = time() . '_back_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/houseBuyerIds'), $filename);
            $nationalIdBackPath = 'uploads/houseBuyerIds/' . $filename;
        }

        $profilePicPath = null;
        if ($request->hasFile('profile_pic') && $request->file('profile_pic')->isValid()) {
            $file = $request->file('profile_pic');
            $filename = time() . '_profile_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/houseBuyerIds'), $filename);
            $profilePicPath = 'uploads/houseBuyerIds/' . $filename;
        }

        $buyer = new houseBuyer();

        $buyer->firstname = $request->input('firstname');
        $buyer->lastname = $request->input('lastname');
        $buyer->gender = $request->input('gender');
        $buyer->date_of_birth = $request->input('date_of_birth');
        $buyer->NIN = $request->input('NIN');
        $buyer->card_number = $request->input('card_number');
        $buyer->phonenumber = $request->input('phonenumber');
        $buyer->national_id_front = $nationalIdFrontPath;
        $buyer->national_id_back = $nationalIdBackPath;
        $buyer->profile_pic = $profilePicPath;
        $buyer->house_id = $request->input('house_id');
        $buyer->sold_by = Session('LoggedAdmin');
        // $buyer->save();

        // Generate and save the PDF

        $user = DB::table('buyers')->where('id', 1)->first();

        $data = [
            'buyer' => $user,
            'agreement_date' => now()->day,
            'agreement_month' => now()->format('F'),
            'agreement_year' => now()->year,
        ];

        $pdf = Pdf::loadView('house_agreement', $data)->setPaper('A4');
        $fileName = 'buyer_agreement_' . $user->id . '.pdf';
        $filePath = 'agreements/' . $fileName;

        Storage::disk('public')->put($filePath, $pdf->output());

        $buyer->buyer_pdf = $filePath;
        $buyer->save();

        $id = $request->input('house_id');

        DB::table('houses')->where('id', $id)
            ->update(['status' => 1]);

        return response()->json([
            'message' => 'Buyer details stored successfully!',
        ]);
    }

    public function approvalHouseSell()
    {
        $pendingApprovals = house::where('status', 1)->paginate(2);

        return view('Admin.show-pending-house-approval', compact(['pendingApprovals']));
    }

    public function pendingHouseInformation($id)
    {
        $pendingHouses = house::where('id', $id)->first();

        return view('Admin.show-pending-approval-houses', compact(['pendingHouses']));
    }

    public function approveHouseSell(Request $request)
    {
        $houseId = $request->input('house_id');

        DB::table('house_buyers')->where('house_id', $houseId)->update([
            'selling_status' => 10,
        ]);

        DB::table('houses')->where('id', $houseId)->update([
            'status' => 10,
        ]);

        return response()->json(['success' => true]);
    }

    public function downloadAgreementPdf($houseId)
    {
        $buyer = houseBuyer::where('house_id', $houseId)->firstOrFail();

        if (!$buyer->buyer_pdf || !Storage::disk('public')->exists($buyer->buyer_pdf)) {
            abort(404, 'PDF not found.');
        }

        return Storage::disk('public')->download($buyer->buyer_pdf, 'land-sale-agreement.pdf');
    }

    public function assignEmptyPlot(Request $request)
    {
        $currentDate = Carbon::now();

        $formattedDate = $currentDate->format('Y/m/d');
        $records = buyer::whereDate('next_installment_pay', $formattedDate)->get();
        $records_count = buyer::whereDate('next_installment_pay', $formattedDate)->count();
        $estates = Estate::all();

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        return view('Admin.Sales.assign-empty-plot', $data, compact(['records_count', 'records', 'estates']));
    }

    public function search_clients_to_transfer(Request $request)
    {

        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $date_sold = $request->date_sold;
        $end_date = $request->end_date;

        $estate = $request->estate;
        $land_plot = $request->land_plot;

        if ($date_sold != null && $end_date != null) {

            $startDate = DB::table('buyers')->where('created_at', 'like', '%' . $date_sold . '%')->get();
            $endDate = DB::table('buyers')->where('created_at', 'like', '%' . $end_date . '%')->get();

            $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

            $mergedResults = $startDate->merge($endDate);

            return view('Admin.Search.multiple_results_empty_plot_transfer_set_up', $data, ['result' => $mergedResults]);
        } elseif ($date_sold != null && $end_date == null) {
            $mergedResults = DB::table('buyers')->where('created_at', 'like', '%' . $date_sold . '%')->get();
            $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

            return view('Admin.Search.multiple_results_empty_plot_transfer_set_up', $data, ['result' => $mergedResults]);
        } elseif ($date_sold == null && $end_date != null) {
            $mergedResults = DB::table('buyers')->where('created_at', 'like', '%' . $end_date . '%')->get();
            $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];
            return view('Admin.Search.multiple_results_empty_plot_transfer_set_up', $data, ['result' => $mergedResults]);
        }

        if ($firstname != null && $lastname == null) {

            $firstname = DB::table('buyers')->where('firstname', 'like', '%' . $firstname . '%')->get();
            $lastname = DB::table('buyers')->where('lastname', 'like', '%' . $firstname . '%')->get();

            $mergedResults = $firstname->merge($lastname);

            $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

            return view('Admin.Search.multiple_results_empty_plot_transfer_set_up', $data, ['result' => $mergedResults]);

        } elseif ($firstname == null && $lastname != null) {

            $firstname = DB::table('buyers')->where('firstname', 'like', '%' . $lastname . '%')->get();
            $lastname = DB::table('buyers')->where('lastname', 'like', '%' . $lastname . '%')->get();

            $mergedResults = $firstname->merge($lastname);

            $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

            return view('Admin.Search.multiple_results_empty_plot_transfer_set_up', $data, ['result' => $mergedResults]);
        }

        if ($firstname != null && $lastname != null) {

            $firstname = DB::table('buyers')->where('firstname', 'like', '%' . $firstname . '%')->get();
            $lastname = DB::table('buyers')->where('lastname', 'like', '%' . $lastname . '%')->get();
            $mergedResults = $firstname->merge($lastname);

            $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

            return view('Admin.Search.multiple_results_empty_plot_transfer_set_up', $data, ['result' => $mergedResults]);

        } else if ($estate != null) {

            $result = DB::table('buyers')
                ->where('estate', $estate)
                ->where('plot_number', $land_plot)
                ->first();

            if (!$result) {
                return back()->with('error', 'No Plot has been found with provided information');
            } else {

                $id = $result->id;
                $user_information = DB::table('buyers')->where('id', '=', $id)->get();
                $user_reciepts = DB::table('pdf_receipts')->where('user_id', '=', $id)->get();
                $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

                $user_information = DB::table('buyers')->where('id', '=', $id)->get();
                $user_reciepts = DB::table('pdf_receipts')->where('user_id', '=', $id)->get();
                $user_agreements = DB::table('pdf_agreements')->where('user_id', '=', $id)->get();
                $user_reciepts_pdf = DB::table('reciepts')->where('user_id', '=', $id)->get();

                $agreement_reference_in_buyer = DB::table('buyers')->where('id', '=', $id)->value('agreement');
                $user_agreements_uploaded = DB::table('agreements')->where('user_id', '=', $agreement_reference_in_buyer)->get();
                $user_agreements_pdf = DB::table('agreements')->where('user_id', '=', $id)->get();

                $firstname = DB::table('buyers')->where('id', '=', $id)->get();

                $mergedResults = $firstname;

                return view('Admin.Search.multiple_results_empty_plot_transfer_set_up', $data, ['result' => $mergedResults]);
            }
        }
    }

    public function searchAvailableEmptyPlots(Request $request)
    {

        $estates = Estate::all();

        // $estates = Estate::orderBy('estate_name','asc')->get();

        $count_estates = Estate::all()->count();
        // $number_plots = Estate::all()->sum('number_of_plots');

        $number_plots = plot::all()
            ->where('status', '=', 'Fully payed')->count();

        $Not_fully_paid_plots = plot::all()
            ->where('status', '=', 'Not taken')->count();

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        return view('Admin.Sales.available-estates-in-the-system', $data, compact(['estates', 'count_estates', 'number_plots', 'Not_fully_paid_plots']));
    }

    public function total_available_plots_in_estate($id)
    {

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        $specific_estate = Estate::find($id);
        $estate_id = $id;
        $estate_name = $specific_estate->estate_name;

        $estate_pdf_info = $specific_estate->estate_pdf;

        $estate_data = plot::where('estate', $estate_name)
            ->where('status', '=', 'Not taken')->get();

        $count_estates_fully = plot::where('estate', $estate_name)
            ->where('status', '=', 'Not taken')->count();

        $estate_price = estate::where('estate_name', $estate_name)->value('estate_price');

        return view('Admin.total_available_plots_in_estate', $data, compact(['specific_estate', 'count_estates_fully', 'estate_id', 'estate_name', 'estate_data', 'estate_pdf_info', 'estate_price']));
    }


    public function view_available_plots_in_estate($id)
    {


        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        $specific_estate = Estate::find($id);
        $estate_id = $id;
        $estate_name = $specific_estate->estate_name;

        $estate_pdf_info = $specific_estate->estate_pdf;

        $estate_data = plot::where('estate', $estate_name)
            ->where('status', '=', 'Not taken')->get();

        $count_estates_fully = plot::where('estate', $estate_name)
            ->where('status', '=', 'Not taken')->count();

        $estate_price = estate::where('estate_name', $estate_name)->value('estate_price');

        return view('Admin.Sales.view_specific_available_plots_in_estate', $data, compact(['specific_estate', 'count_estates_fully', 'estate_id', 'estate_name', 'estate_data', 'estate_pdf_info', 'estate_price']));
    }


    public function transfer(Request $request)
    {

        $userData = DB::table('buyers')->where('id', Session('TransferedClient'))->first();
        $originalBuyer = DB::table('buyers')->where('id', Session('TransferedClient'))->first();

        $estateId = DB::table('estates')->where('estate_name', $request->estate)->value('id');

        if (!$originalBuyer) {
            return response()->json([
                'message' => 'Client doesnot exist to be transfered'
            ], 400);
        }

        $user_id = Session('TransferedClient');
        $purchase_type = 'plot';
        $estate = $request->estate;
        $plot_no = $userData->plot_number;
        $amount_resold = str_replace(',', '', $userData->amount_payed);
        $amount_to_be_sold = str_replace(',', '', $userData->amount_payed);

        $post = new resale;

        $post->user_id = $user_id;
        $post->purchase_type = $purchase_type;
        $post->estate = $estate;
        $post->plot_number = $plot_no;
        $post->reciept_resold = '-';

        $post->amount_resold = $amount_resold;
        $post->amount_to_be_sold = $userData->amount_payed;
        $post->paid_cash = 1;

        $post->seller_agreeement = '-';

        $post->save();

        $whereConditions = [
            'estate' => $estate,
            'plot_number' => $plot_no,
        ];

        $buyerWhereConditions = [
            'id' => $user_id,
            'estate' => $estate,
            'plot_number' => $plot_no,
        ];


        DB::table('plots')
            ->where($whereConditions)
            ->update([
                'status' => 'Not taken',
                'exceptional_status' => 'Yes',
                'exceptional_amount' => $amount_to_be_sold,
                'buyer_id' => 0,
            ]);

        DB::table('buyers')
            ->where($buyerWhereConditions)
            ->update([
                'back_on_market_status' => 1,
                'shift_plot_status' => 1,
            ]);

        // Assigning new plot to buyer and removing it from the available

        $buyerData = (array) $originalBuyer;

        $buyerData['estate'] = $request->estate;
        $buyerData['plot_number'] = $request->plot_number;
        $buyerData['shifted_to_status'] = 2;

        unset(
            $buyerData['id'],
            $buyerData['created_at'],
            $buyerData['updated_at']
        );

        $buyerData['created_at'] = now();
        $buyerData['updated_at'] = now();

        $newUserId = DB::table('buyers')->insertGetId($buyerData);

        $plotsWhereConditions = [
            'estate' => $request->estate,
            'plot_number' => $request->plot_number,
        ];

        DB::table('plots')->where($plotsWhereConditions)->update(
            [
                'status' => 'Fully payed',
                'buyer_id' => $newUserId,
            ]
        );

        return response()->json([
            'message' => 'Client transferred successfully.',
            'redirect_url' => url('/total-fully-paid-plots-in-estate/' . $estateId)
        ]);

    }

    public function updateBuyersBackOnMarketStatus()
    {

        $resales = DB::table('resales')
            ->select('estate', 'plot_number', 'user_id')
            ->get();

        foreach ($resales as $resale) {
            DB::table('buyers')
                ->where('estate', $resale->estate)
                ->where('plot_number', $resale->plot_number)
                ->where('id', $resale->user_id)
                ->update(['back_on_market_status' => 1]);

        }

        return 'Buyers updated based on resales.';
    }

    public function insertPlots()
    {
        for ($i = 1; $i <= 143; $i++) {
            DB::table('plots')->insert([
                'estate' => 'kabimbiri',
                'plot_number' => $i,
                'width_1' => 20,
                'width_2' => 20,
                'height_1' => 40,
                'height_2' => 40,
                'status' => 'Not taken',
                'location' => 'Mukono',
                'half_or_full' => 0,
                'buyer_id' => 0,
                'exceptional_status' => 0,
                'is_half' => 1,
            ]);
        }

        dd('Records inserted successfully from plot 2 to 143.');

    }

    public function updateRequestPermission(Request $request, $id)
    {
        try {
            DB::table('buyers')->where('id', $id)->update([
                'request_permission' => 1,
                'updated_at' => now(),
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function confirmRequestPermission(Request $request, $id)
    {

        DB::table('buyers')->where('id', $id)->update([
            'request_permission' => 2,
            'updated_at' => now()
        ]);

        $amount_in_words = $request->amount_in_words;

        $user_id = $id;
        $original_amount = buyer::where('id', $user_id)->value('amount_payed');

        $half_or_full = buyer::where('id', $user_id)->value('half_or_full');

        $plot_number = buyer::where('id', $user_id)->value('plot_number');
        $estate_name = buyer::where('id', $user_id)->value('estate');
        $estate_price = Estate::where('estate_name', $estate_name)->value('estate_price');

        $user_amount_paid = $request->amount_paid;
        $all_cash = $original_amount + $user_amount_paid;

        $group_id = plot::where('plot_number', $plot_number)
            ->where('estate', $estate_name)
            ->value('group_id');

        $group_total = Plot::where('group_id', $group_id)->sum('exceptional_amount');

        $interconnected_plots = Plot::where('group_id', $group_id)->pluck('plot_number')->toArray();

        $plot_numbers = implode(', ', $interconnected_plots);

        if ($all_cash < $estate_price) {
            return response()->json([
                'status' => 'error',
                'message' => 'The amount ' . $original_amount . ' so far paid by the client is not enough to purchase this plot in this estate which is ' . $estate_price
            ], 400); // 400 Bad Request
        }

        $record_plot = buyer::where('id', $user_id)->value('plot_number');
        $exceptional_status = plot::where('plot_number', $record_plot)
            ->where('estate', '=', $estate_name)->value('exceptional_status');

        if ($exceptional_status == "Yes") {

            $exceptional_amount = plot::where('plot_number', $record_plot)
                ->where('estate', '=', $estate_name)->value('exceptional_amount');

            if ($all_cash < $exceptional_amount) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Amount used to purchase which is ' . $original_amount . ' plot is less, this is an exceptional plot'
                ], 400);
            }
        }

        $user_id = $id;
        $reciepts = $request->reciept_added;
        $agreement_reciept = $request->agreement_added;
        $user_amount_paid = $request->amount_paid;
        $Date_of_payment = now();

        $balance = 0;

        $original_amount = buyer::where('id', $user_id)->value('amount_payed');
        $all_cash = $original_amount + $user_amount_paid;

        // Document formulation

        $user_info = buyer::where('id', $user_id)->first();
        $profile_pic = buyer::where('id', $user_id)->value('profile_pic');
        $profile_pic = public_path('profile_pic/' . $profile_pic);

        $day = $user_info->created_at->day;
        $month = $user_info->created_at->month;
        $year = $user_info->created_at->year;
        $user_amount_paid = $original_amount;
        $user_receipt = '-';

        $pdf = PDF::loadView('agreement_pdf', compact([
            'user_amount_paid',
            'user_info',
            'all_cash',
            'Date_of_payment',
            'day',
            'month',
            'year',
            'amount_in_words',
            'profile_pic'
        ]));

        $filename = 'payment_agreement' . time() . '.pdf';
        $publicPath = public_path('agreements');

        if (!file_exists($publicPath)) {
            mkdir($publicPath, 0775, true);
        }

        $pdf->save("{$publicPath}/{$filename}");

        $post = new agreement();
        $post->user_id = $id;
        $post->Amount_paid = $original_amount;
        $post->Date_of_payment = now();
        $post->reciept = '-';
        $post->agreement = $filename;
        $post->save();

        $original_amount = buyer::where('id', $user_id)->value('amount_payed');
        $all_cash = $original_amount + $user_amount_paid;

        $save = $post->save();

        // Update buyer records

        $update_buyer_agreement = buyer::where('id', $user_id)->update([
            'next_installment_pay' => "Fully payed",
            'reciepts' => '-',
            'agreement' => '-',
            'amount_payed' => $all_cash,
            'balance' => $balance
        ]);

        $estate_no_no = buyer::where('id', $user_id)->value('estate');
        $plot_no_no = buyer::where('id', $user_id)->value('plot_number');

        $whereConditions = [
            'estate' => $estate_no_no,
            'plot_number' => $plot_no_no,
        ];

        DB::table('plots')->where($whereConditions)->update(['status' => 'Fully payed']);

        DB::insert(
            'insert into reciepts (user_id, amount, balance, reciept, Date_of_payment, Phonenumber, amount_in_words) values (?, ?, ?, ?, ?, ?, ?)',
            [$user_id, $user_amount_paid, $balance, $user_receipt, $Date_of_payment, '-', '-']
        );

        $half_plot_or_full_plot = plot::where($whereConditions)->value('half_or_full');
        $full_payed_or_not = plot::where($whereConditions)->value('status');

        if ($half_plot_or_full_plot == '1' && $full_payed_or_not == "Fully payed") {
            DB::table('plots')->where($whereConditions)->update(['half_or_full' => '0']);

        }

        return response()->json([
            'status' => 'success',
            'message' => 'Agreement generated successfully!',
            'download_link' => url("agreements/{$filename}")
        ]);
    }

    public function grantAgreementPermission()
    {

        $records = Estate::all();

        $not_fully_paid = DB::table('buyers')->where('next_installment_pay', '!=', "Fully payed")
            ->where('request_permission', '=', 1)
            ->get();

        // $not_fully_paid = DB::table('buyers')
        //     ->select('multiple_user_id', DB::raw('COUNT(*) as plots'), DB::raw('SUM(amount_payed) as total_paid'))
        //     ->where('next_installment_pay', '!=', "Fully payed")
        //     ->where('request_permission', '=', 1)
        //     ->groupBy('multiple_user_id')
        //     ->get();

        $data = ['LoggedAdminInfo' => AdminRegister::where('id', '=', session('LoggedAdmin'))->first()];

        return view('Admin.Receipts.grant-agreement-permission', $data, compact(['not_fully_paid', 'records']));
    }



    public function allClientReciepts($id)
    {
        $user_id = $id;

        $userInformation = Buyer::find($id);

        $data = [
            'LoggedAdminInfo' => AdminRegister::where('id', session('LoggedAdmin'))->first(),
            'user_id' => $user_id,
            'userInformation' => $userInformation,
            'receipts' => Reciept::where('user_id', $user_id)->get(),
            'oldReceipts' => DB::table('oldreceipts')->where('buyer_id', $user_id)->get(),
        ];

        return view('Admin.Receipts.all-client-reciepts', $data);
    }



    public function uploadOldReciepts(Request $request)
    {
        $file = $request->file('receipt');
        $filename = time() . '_' . $file->getClientOriginalName();

        $path = $file->storeAs('receipts', $filename, 'public');

        copy(
            storage_path("app/public/receipts/{$filename}"),
            public_path("storage/receipts/{$filename}")
        );

        $original_amount = buyer::where('id', $request->item_id)->value('amount_payed');

        DB::table('oldreceipts')->insert([
            'buyer_id' => $request->item_id,
            'file_path' => 'receipts/' . $filename, // Just save relative path
            'amount_paid' => $request->amount_paid,
            'balance' => $request->balance,
        ]);


        $all_cash = $original_amount + $request->amount_paid;

        $update_buyer_amount = buyer::where('id', $request->item_id)
            ->update([
                'amount_payed' => $all_cash,
            ]);

        return redirect()->back()->with('success', 'Receipt uploaded successfully.');
    }

    public function downloadOldAgreement($id)
    {
        $agreement = DB::table('agreements')->where('user_id', $id)->latest()->first();

        if (!$agreement) {
            return back()->with('error', 'Agreement not found.');
        }

        $path = storage_path('app/public/agreements/' . $agreement->agreement);

        if (!file_exists($path)) {
            return back()->with('error', 'Agreement file not found.');
        }

        return response()->download($path);
    }
}