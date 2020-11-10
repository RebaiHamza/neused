<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Product;
use App\Order;
use App\Store;
use App\Brand;
use App\Coupan;
use App\Category;
use App\Faq;
use App\Invoice;
use PDF;
use App\FullOrderCancelLog;
use App\CanceledOrders;
use App\Genral;
use App\InvoiceDownload;
use App\PendingPayout;
use App\Charts\AdminUserChart;
use App\Charts\AdminUserPieChart;
use App\Charts\OrderChart;
use DB;
use Carbon\Carbon;
use App\DashboardSetting;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Session;

/*==========================================
=            Author: Media City            =
    Author URI: https://mediacity.co.in
=            Author: Media City            =
=            Copyright (c) 2020            =
==========================================*/

class AdminController extends Controller
{

    public function user_read()
    {
        auth()->user()
            ->unreadNotifications
            ->where('n_type', '=', 'user')
            ->markAsRead();
        return redirect()
            ->back();
    }

    public function order_read()
    {
        auth()
            ->user()
            ->unreadNotifications
            ->where('n_type', '=', 'order_v')
            ->markAsRead();
        return redirect()
            ->back();
    }

    public function ticket_read()
    {
        auth()
            ->user()
            ->unreadNotifications
            ->where('n_type', '=', 'ticket')
            ->markAsRead();
        return redirect()
            ->back();
    }

    public function all_read()
    {
        auth()
            ->user()
            ->unreadNotifications
            ->where('n_type', '!=', 'order_v')
            ->markAsRead();
        return redirect()
            ->back();
    }

    public function index()
    {   
        $lang = Session::get('changed_language');

       $products = Product::join('stores', 'stores.id', '=', 'products.store_id')->join('users','users.id','=','stores.user_id')
                    ->select('products.*')->get();
        
        $order = Order::where('status','=','1')->count();
        $user = User::count();
        $store = Store::count();
        $coupan = Coupan::count();
        $faqs = Faq::count();
        $category = Category::count();
        $cancelorder = CanceledOrders::count();
        $fcanorder = FullOrderCancelLog::count();
        $totalcancelorder = $fcanorder + $cancelorder;
        $inv_cus = Invoice::first();
        $setting = Genral::first();

        $dashsetting = DashboardSetting::first();

        $fillColors = [
            "rgba(255, 99, 132, 0.2)",
            "rgba(22,160,133, 0.2)",
            "rgba(255, 205, 86, 0.2)",
            "rgba(51,105,232, 0.2)",
            "rgba(244,67,54, 0.2)",
            "rgba(34,198,246, 0.2)",
            "rgba(153, 102, 255, 0.2)",
            "rgba(255, 159, 64, 0.2)",
            "rgba(233,30,99, 0.2)",
            "rgba(205,220,57, 0.2)"
        ];

        $fillColors2 = ['rgba(224, 36, 36, 0.70)','rgba(62, 123, 229, 0.7)','rgba(81, 200, 106, 0.70)','#7158e2','#3ae374', '#ff3838'];

        /*Creating Userbarchart*/

            $users = array(

                 User::whereMonth('created_at','01')
                  ->whereYear('created_at', date('Y'))
                  ->count(), //January

                  User::whereMonth('created_at','02')
                  ->whereYear('created_at', date('Y'))
                  ->count(), //Feb

                  User::whereMonth('created_at','03')
                  ->whereYear('created_at', date('Y'))
                  ->count(), //March

                  User::whereMonth('created_at','04')
                  ->whereYear('created_at', date('Y'))
                  ->count(), //April

                  User::whereMonth('created_at','05')
                  ->whereYear('created_at', date('Y'))
                  ->count(), //May

                  User::whereMonth('created_at','06')
                  ->whereYear('created_at', date('Y'))
                  ->count(), //June

                  User::whereMonth('created_at','07')
                  ->whereYear('created_at', date('Y'))
                  ->count(), //July

                  User::whereMonth('created_at','08')
                  ->whereYear('created_at', date('Y'))
                  ->count(), //August

                   User::whereMonth('created_at','09')
                  ->whereYear('created_at', date('Y'))
                  ->count(), //September

                  User::whereMonth('created_at','10')
                  ->whereYear('created_at', date('Y'))
                  ->count(), //October

                  User::whereMonth('created_at','11')
                  ->whereYear('created_at', date('Y'))
                  ->count(), //November

                  User::whereMonth('created_at','12')
                  ->whereYear('created_at', date('Y'))
                  ->count(), //December

            );

            
            $userchart = new AdminUserChart;

            $userchart->labels(['January', 'Febuary', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']);

            $userchart->title('Monthly Registered Users in '.date('Y'))->dataset('Monthly Registered Users', 'bar', $users)->options([
                'fill' => 'true',
                'shadow' => 'true',
                'borderWidth' => '1'
            ])->backgroundColor($fillColors)->color($fillColors);

        /*END*/

        /*Creating order chart*/

           $totalorder = array(

                  Order::where('status','1')->whereMonth('created_at','01')
                  ->whereYear('created_at', date('Y'))
                  ->count(), //January

                  Order::where('status','1')->whereMonth('created_at','02')
                  ->whereYear('created_at', date('Y'))
                  ->count(), //Feb

                  Order::where('status','1')->whereMonth('created_at','03')
                  ->whereYear('created_at', date('Y'))
                  ->count(), //March

                  Order::where('status','1')->whereMonth('created_at','04')
                  ->whereYear('created_at', date('Y'))
                  ->count(), //April

                  Order::where('status','1')->whereMonth('created_at','05')
                  ->whereYear('created_at', date('Y'))
                  ->count(), //May

                  Order::where('status','1')->whereMonth('created_at','06')
                  ->whereYear('created_at', date('Y'))
                  ->count(), //June

                  Order::where('status','1')->whereMonth('created_at','07')
                  ->whereYear('created_at', date('Y'))
                  ->count(), //July

                  Order::where('status','1')->whereMonth('created_at','08')
                  ->whereYear('created_at', date('Y'))
                  ->count(), //August

                  Order::where('status','1')->whereMonth('created_at','09')
                  ->whereYear('created_at', date('Y'))
                  ->count(), //September

                  Order::where('status','1')->whereMonth('created_at','10')
                  ->whereYear('created_at', date('Y'))
                  ->count(), //October

                  Order::where('status','1')->whereMonth('created_at','11')
                  ->whereYear('created_at', date('Y'))
                  ->count(), //November

                  Order::where('status','1')->whereMonth('created_at','12')
                  ->whereYear('created_at', date('Y'))
                  ->count(), //December

            );

            $orderchart = new OrderChart;

            $orderchart->labels(['January', 'Febuary', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']);

            $orderchart->title('Total Orders in '.date('Y'))->label('Sales')->dataset('Total sale', 'area', $totalorder)->options([
                'fill' => 'true',
                'borderColor' => '#51C1C0',
                'shadow' => true
            ]);


        /*END*/

        /*Creating Piechart of user */

            $admins = User::where('role_id', '=', 'a')->count();
            $sellers = User::where('role_id', '=', 'v')->count();
            $customers = User::where('role_id', '=', 'u')->count();
        
            $piechart = new AdminUserPieChart;

            $piechart->labels(['Admin', 'Seller', 'Customers']);

            $piechart->minimalist(true);

            

            $data = [$admins,$sellers,$customers];

            $piechart->title('User Distribution')->dataset('User Distribution', 'pie', $data)->options([
                'fill' => 'true',
                'shadow' => true,
            ])->color($fillColors2);

        /*End Piechart for user*/


        if ($setting->vendor_enable == 1)
        {
            $filterpayout = collect();
            $pendingPayout = PendingPayout::join('users','users.id','=','pending_payouts.sellerid')->get();

            foreach ($pendingPayout as $key => $outp)
            {

                if ($outp->singleorder->variant->products->return_avbl == 1)
                  {

                        $days = $outp->singleorder->variant->products->returnPolicy->days;
                        $endOn = date("Y-m-d", strtotime("$outp->updated_at +$days days"));
                        $today = date('Y-m-d');

                        if ($today <= $endOn)
                        {

                        }
                        else
                        {

                            $filterpayout->push($outp);

                        }

                  }
                  else
                  {
                      $filterpayout->push($outp);
                  }

                

            }

        }
        else
        {
            $filterpayout = NULL;
        }

        $latestorders = Order::join('users','users.id','=','orders.user_id')->select('users.name as customername','orders.order_id as orderid','orders.qty_total as qty','orders.paid_in as paid_in','orders.order_total as ordertotal','orders.created_at as created_at')->orderBy('orders.id','DESC')->take($dashsetting->max_item_ord)->get();

        $storerequest = Store::join('users','users.id','=','stores.user_id')->where('stores.apply_vender','=','0')->where('users.status','=','1')->select('users.name as owner','stores.email as email','stores.name as name')->take($dashsetting->max_item_str)->get();

        return view("admin.dashbord.index", compact('latestorders','filterpayout', 'products', 'order', 'user', 'store', 'coupan', 'category', 'totalcancelorder', 'faqs', 'inv_cus', 'userchart','piechart','orderchart','storerequest'));
    }

   

    public function user()
    {
        $users = User::all();

        return view("admin.user.show", compact("users"));
    }

    public function order_print($id)
    {
        $invpre = Invoice::first();
        $order = order::where('id', $id)->first();

        $pdf = PDF::loadView('admin.print.pdfView', compact('order', 'invpre'));

        return $pdf->setPaper('a4', 'landscape')
            ->download('invoice.pdf');
    }

    public function single(Request $request)
    {
        $a = isset($request['id1']) ? $request['id1'] : 'not yet';

        $userUnreadNotification = auth()->user()
            ->unreadNotifications
            ->where('id', $a)->first();

        if ($userUnreadNotification)
        {
            $userUnreadNotification->markAsRead();
            return response()->json(['status' => 'success']);
        }

        
    }

}

