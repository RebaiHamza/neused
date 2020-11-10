<?php

namespace App\Http\Controllers;

use App\AddProductVariant;
use App\AddSubVariant;
use App\admin_return_product;
use App\Brand;
use App\Category;
use App\Commission;
use App\CommissionSetting;
use App\FaqProduct;
use App\Genral;
use App\Grandcategory;
use App\Product;
use App\ProductSpecifications;
use App\RealatedProduct;
use App\Related_setting;
use App\Shipping;
use App\Store;
use App\Subcategory;
use App\TaxClass;
use App\usedProductImage;
use App\User;
use App\UserReview;
//use Auth;
use Avatar;
use DataTables;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as Auth;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Image;
use Rap2hpoutre\FastExcel\FastExcel;

class usedProductsController extends Controller
{
    public function storeSpecs(Request $request, $id)
    {
        $product = Product::find($id);

        if (isset($product)) {
            foreach ($request->prokeys as $key => $value) {
                $newspec = new ProductSpecifications();
                $newspec->pro_id = $product->id;
                $newspec->prokeys = $value;
                $newspec->provalues = $request->provalues[$key];
                $newspec->save();
            }
        }

        return back()
            ->with('added', 'Product Specification created !');
    }

    public function deleteSpecs(Request $request, $id)
    {
        $validator = Validator::make($request->all(), ['checked' => 'required']);

        if ($validator->fails()) {
            return back()
                ->with('warning', 'Please select one of them to delete');
        }

        foreach ($request->checked as $key => $check) {
            $specs = ProductSpecifications::find($check);

            if (isset($specs)) {
                $specs->delete();
            }
        }

        return back()
            ->with('deleted', 'Selected Specification Deleted');
    }

    public function updateSpecs(Request $request, $id)
    {
        $spec = ProductSpecifications::findOrFail($id);

        $spec->prokeys = $request->pro_key;
        $spec->provalues = $request->pro_val;

        $spec->save();

        return back()
            ->with('updated', 'Specification has been Updated');
    }

    public function bulk_delete(Request $request)
    {
        $validator = Validator::make($request->all(), ['checked' => 'required']);

        if ($validator->fails()) {
            return back()
                ->with('warning', 'Please select one of them to delete');
        }

        foreach ($request->checked as $checked) {
            $pro = Product::find($checked);

            if ($pro) {
                // $provar = AddProductVariant::where('pro_id', $checked)->first();

                // $subvar = AddSubVariant::where('pro_id', $checked)->get();

                DB::table('add_sub_variants')
                    ->where('pro_id', $checked)->delete();

                if (isset($provar)) {
                    DB::table('add_product_variants')->where('pro_id', $checked)->delete();
                }

                foreach ($pro->reviews as $value) {
                    $value->delete();
                }

                if (isset($subvar)) {
                    foreach ($subvar as $s) {
                        if ($s->variantimages['image1'] != null) {
                            unlink('../public/variantimages/'.$s->variantimages['image1']);
                        }

                        if ($s->variantimages['image2'] != null) {
                            unlink('../public/variantimages/'.$s->variantimages['image2']);
                        }

                        if ($s->variantimages['image3'] != null) {
                            unlink('../public/variantimages/'.$s->variantimages['image3']);
                        }

                        if ($s->variantimages['image4'] != null) {
                            unlink('../public/variantimages/'.$s->variantimages['image4']);
                        }

                        if ($s->variantimages['image5'] != null) {
                            unlink('../public/variantimages/'.$s->variantimages['image5']);
                        }

                        if ($s->variantimages['image6'] != null) {
                            unlink('../public/variantimages/'.$s->variantimages['image6']);
                        }

                        DB::table('variant_images')
                            ->where('var_id', $s->id)
                            ->delete();
                        $s->delete();
                    }
                }

                $pro::destroy($checked);
            }
        }

        return back()->with('deleted', 'Selected Products has been deleted !');
    }

    public function allreviews($id)
    {
        require_once 'price.php';

        $product = Product::find($id);

        $allreviews = UserReview::orderBy('id', 'DESC')->where('status', '=', '1')->where('pro_id', $id)->paginate(10);

        $reviewcount = UserReview::where('pro_id', $id)->where('status', '1')->WhereNotNull('review')->count();

        $mainproreviews = UserReview::orderBy('id', 'DESC')->where('status', '=', '1')->where('pro_id', $id)->get();
        $review_t = 0;
        $price_t = 0;
        $value_t = 0;
        $sub_total = 0;
        $count = count($mainproreviews);

        foreach ($mainproreviews as $review) {
            $review_t = $review->qty * 5;
            $price_t = $review->price * 5;
            $value_t = $review->value * 5;
            $sub_total = $sub_total + $review_t + $price_t + $value_t;
        }

        $count = ($count * 3) * 5;

        if (!isset($overallrating)) {
            $overallrating = 0;
            $ratings_var = 0;
        }

        if ($count != '') {
            $rat = $sub_total / $count;

            $ratings_var = ($rat * 100) / 5;

            $overallrating = ($ratings_var / 2) / 10;
        }

        $overallrating = round($overallrating, 1);

        $qualityprogress = 0;
        $quality = 0;
        $tq = 0;

        $priceprogress = 0;
        $price = 0;
        $tp = 0;

        $valueprogress = 0;
        $value = 0;
        $vp = 0;

        if (!empty($mainproreviews[0])) {
            $count = count($mainproreviews);

            foreach ($mainproreviews as $key => $r) {
                $quality = $tq + $r->qty * 5;
            }

            $countq = ($count * 1) * 5;
            $ratq = $quality / $countq;
            $qualityprogress = ($ratq * 100) / 5;

            foreach ($mainproreviews as $key => $r) {
                $price = $tp + $r->price * 5;
            }

            $countp = ($count * 1) * 5;
            $ratp = $price / $countp;
            $priceprogress = ($ratp * 100) / 5;

            foreach ($mainproreviews as $key => $r) {
                $value = $vp + $r->value * 5;
            }

            $countv = ($count * 1) * 5;
            $ratv = $value / $countv;
            $valueprogress = ($ratv * 100) / 5;
        }

        if (isset($product)) {
            return view('front.allreviews', compact('conversion_rate', 'product', 'ratings_var', 'allreviews', 'overallrating', 'mainproreviews', 'qualityprogress', 'priceprogress', 'valueprogress', 'reviewcount'));
        } else {
            notify()->error('404 Product not found !');

            return back();
        }
    }

    public function importPage()
    {
        return view('admin.product.importindex');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,xlsx,xls']);

        if (!$request->has('file')) {
            return back()->with('warning', 'Please choose a file !');
        }

        $fileName = time().'.'.$request->file->extension();

        if (!is_dir(public_path().'/excel')) {
            mkdir(public_path().'/excel');
        }

        $request->file->move(public_path('excel'), $fileName);

        ini_set('max_execution_time', 0);
        ini_set('memory_limit', -1);

        $productfile = (new FastExcel())->import(public_path().'/excel/'.$fileName);
        $lang = Session::get('changed_language');

        if (count($productfile) > 0) {
            foreach ($productfile as $key => $line) {
                $rowno = $key + 1;
                $sellPrice = 0;
                $sellofferPrice = 0;
                $commissionRate = 0;

                $catname = $line['category_name'];

                $catid = Category::whereRaw("JSON_EXTRACT(title, '$.$lang') = '$catname'")->first();

                if (!isset($catid)) {
                    $catid = new Category();
                    $catid->title = $line['category_name'];
                    $catid->status = '1';
                    $catid->featured = '1';
                    $catid->position = (Category::count() + 1);
                    $catid->save();
                }

                $subcatname = $line['subcategory_name'];
                $subcatid = Subcategory::whereRaw("JSON_EXTRACT(title, '$.$lang') = '$subcatname'")->first();

                if (!isset($subcatid)) {
                    $subcatid = new Subcategory();
                    $subcatid->title = $line['subcategory_name'];
                    $subcatid->status = '1';
                    $subcatid->position = (Subcategory::count() + 1);
                    $subcatid->featured = '0';
                    $subcatid->parent_cat = $catid->id;
                    $subcatid->save();
                }

                $brandnid = Brand::where('name', $line['brand_name'])->first();

                if (!isset($brandnid)) {
                    $brandnid = new Brand();
                    $brandnid->name = $line['brand_name'];
                    $brandnid->status = '1';
                    $brandnid->show_image = '1';
                    $brandnid->is_requested = '0';
                    $brandnid->save();
                }

                $store = Store::where('name', $line['store_name'])->first();

                if (!isset($store)) {
                    $file = @file_get_contents(public_path().'/excel/'.$fileName);

                    if ($file) {
                        unlink(public_path().'/excel/'.$fileName);
                    }

                    return back()->with('warning', "Invalid Store name at Row no $rowno Store not found ! Please create it and than try to import this file again !");
                    break;
                }

                if ($line['return_available'] != '0') {
                    $p = admin_return_product::where('name', $line['return_policy'])->first();

                    if (!isset($p)) {
                        $file = @file_get_contents(public_path().'/excel/'.$fileName);

                        if ($file) {
                            unlink(public_path().'/excel/'.$fileName);
                        }

                        return back()->with('warning', "Invalid Return Policy name at Row no $rowno Return Policy not found ! Please create it and than try to import this file again !");
                        break;
                    }

                    $policy = $p->id;
                } else {
                    $policy = 0;
                }

                if ($line['tax'] != '0') {
                    $tc = TaxClass::where('title', $line['tax'])->first();

                    if (!isset($tc)) {
                        $file = @file_get_contents(public_path().'/excel/'.$fileName);

                        if ($file) {
                            unlink(public_path().'/excel/'.$fileName);
                        }

                        return back()->with('warning', "Invalid TaxClass name at Row no $rowno TaxClass not found ! Please create it and than try to import this file again !");
                        break;
                    }

                    $taxClass = $tc->id;
                } else {
                    $taxClass = 0;
                }

                if ($line['free_shipping'] != '1') {
                    $freeShipping = 1;
                    $ship = Shipping::where('default_status', '1')->first();

                    if (!isset($ship)) {
                        $file = @file_get_contents(public_path().'/excel/'.$fileName);

                        if ($file) {
                            unlink(public_path().'/excel/'.$fileName);
                        }

                        return back()->with('warning', "Invalid Shipping name at Row no $rowno Childcategory not found ! Please create it and than try to import this file again !");
                        break;
                    }

                    $shippingID = $ship->id;
                } else {
                    $freeShipping = 0;
                    $shippingID = null;
                }

                if ($line['childcategory'] != '') {
                    $childcatname = $line['childcategory'];
                    $c = Grandcategory::whereRaw("JSON_EXTRACT(title, '$.$lang') = '$childcatname'")->first();

                    if (!isset($c)) {
                        $child = new Grandcategory();
                        $child->title = $line['childcategory'];
                        $child->status = '1';
                        $child->position = (Grandcategory::count() + 1);
                        $child->featured = '0';
                        $child->parent_id = $catid->id;
                        $child->subcat_id = $subcatid->id;
                        $child->save();

                        $childid = $child->id;
                    } else {
                        $childid = $c->id;
                    }
                } else {
                    $childid = '0';
                }

                /*Commission Price*/
                $sellofferPrice = 0;
                $commissions = CommissionSetting::all();
                foreach ($commissions as $commission) {
                    if ($commission->type == 'flat') {
                        if ($commission->p_type == 'f') {
                            if ($line['tax_rate'] != '') {
                                $cit = $commission->rate * $line['tax_rate'] / 100;
                                $price = $line['price'] + $commission->rate + $cit;

                                if ($line['offer_price'] != '' && $line['offer_price'] != '0') {
                                    $offer = $line['offer_price'] + $commission->rate + $cit;
                                }
                            } else {
                                $price = $line['price'] + $commission->rate;

                                if ($line['offer_price'] != '' && $line['offer_price'] != '0') {
                                    $offer = $line['offer_price'] + $commission->rate;
                                }
                            }

                            $sellPrice = $price;
                            $sellofferPrice = $offer;
                            $commissionRate = $commission->rate;
                        } else {
                            $taxrate = $commission->rate;
                            $price1 = $line['price'];

                            if ($line['offer_price'] != '') {
                                $price2 = $line['offer_price'];
                                $tax2 = ($price2 * (($taxrate / 100)));
                                $sellofferPrice = $price2 + $tax2;
                            }

                            $tax1 = ($price1 * (($taxrate / 100)));

                            $sellPrice = $price1 + $tax1;

                            if ($line['offer_price'] != '' && $line['offer_price'] != '0') {
                                $commissionRate = $tax2;
                            } else {
                                $commissionRate = $tax1;
                            }
                        }
                    } else {
                        $comm = Commission::where('category_id', $catid)->first();

                        if (isset($comm)) {
                            if ($comm->type == 'f') {
                                if ($line['tax_rate'] != '') {
                                    $cit = $comm->rate * $line['tax_rate'] / 100;
                                    $price = $line['price'] + $comm->rate + $cit;

                                    if ($line['offer_price'] != '' && $line['offer_price'] != '0') {
                                        $offer = $line['offer_price'] + $comm->rate + $cit;
                                    }
                                } else {
                                    $price = $line['price'] + $comm->rate;

                                    if ($line['offer_price'] != '' && $line['offer_price'] != '0') {
                                        $offer = $line['offer_price'] + $comm->rate;
                                    }
                                }

                                $sellPrice = $price;
                                $sellofferPrice = $offer;
                                $commissionRate = $comm->rate;
                            } else {
                                $taxrate = $comm->rate;
                                $price1 = $line['price'];
                                $price2 = $line['offer_price'];
                                $tax1 = ($price1 * (($taxrate / 100)));
                                $tax2 = ($price2 * (($taxrate / 100)));
                                $price = $line['price'] + $tax1;
                                $offer = $line['offer_price'] + $tax2;
                                $sellPrice = $price;
                                $sellofferPrice = $offer;

                                if ($line['offer_price'] != '') {
                                    $commissionRate = $tax2;
                                } else {
                                    $commissionRate = $tax1;
                                }
                            }
                        } else {
                            $commissionRate = 0;
                            $sellPrice = $line['price'] + $commissionRate;

                            if ($line['offer_price'] != '' && $line['offer_price'] != '0') {
                                $sellofferPrice = $line['offer_price'] + $commissionRate;
                            }
                        }
                    }
                }

                //convert for enum value
                if ($line['featured'] == 0) {
                    $featured = '0';
                } else {
                    $featured = '1';
                }

                if ($line['status'] == 0) {
                    $pstatus = '0';
                } else {
                    $pstatus = '1';
                }

                $product = Product::create([
                    'category_id' => $catid->id,
                    'child' => $subcatid->id,
                    'grand_id' => $childid,
                    'store_id' => $store->id,
                    'vender_id' => $store->user->id,
                    'brand_id' => $brandnid->id,
                    'name' => $line['product_name'],
                    'des' => clean($line['product_description']),
                    'tags' => $line['tags'],
                    'model' => $line['model_no'],
                    'sku' => $line['sku'],
                    'price_in' => $line['price_in'],
                    'price' => $sellPrice,
                    'offer_price' => $sellofferPrice,
                    'featured' => $featured,
                    'status' => $pstatus,
                    'vender_price' => $line['price'],
                    'vender_offer_price' => $line['offer_price'],
                    'tax' => $taxClass,
                    'codcheck' => $line['cash_on_delivery'],
                    'free_shipping' => $freeShipping,
                    'selling_start_at' => $line['selling_start_at'],
                    'return_avbl' => $line['return_available'],
                    'cancel_avl' => $line['cancel_available'],
                    'w_d' => $line['warranty_in_days'],
                    'w_my' => $line['warranty_in_monthsyears'],
                    'w_type' => $line['warranty_type'],
                    'commission_rate' => $commissionRate,
                    'shipping_id' => $shippingID,
                    'return_policy' => $policy,
                    'tax_r' => $line['tax_rate'],
                    'tax_name' => $line['tax_name'],
                    'created_at' => date('Y-m-d h:i:s'),
                    'updated_at' => date('Y-m-d h:i:s'),
                ]);

                $relsetting = new Related_setting();
                $relsetting->pro_id = $product->id;
                $relsetting->status = '0';
                $relsetting->save();
            }

            Session::flash('added', 'Your Data has successfully imported');
            $file = @file_get_contents(public_path().'/excel/'.$fileName);

            if ($file) {
                unlink(public_path().'/excel/'.$fileName);
            }

            return back();
        } else {
            Session::flash('warning', 'Your Excel file is empty !');
            $file = @file_get_contents(public_path().'/excel/'.$fileName);

            if ($file) {
                unlink(public_path().'/excel/'.$fileName);
            }

            return back();
        }
    }

    public function index(Request $request)
    {
        $lang = Session::get('changed_language');

        $products = DB::table('products')->join('users', 'users.id', '=', 'products.vender_id')
            ->join('brands', 'brands.id', '=', 'products.brand_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->join('subcategories', 'subcategories.id', '=', 'products.child')
            ->leftJoin('grandcategories', 'grandcategories.id', '=', 'products.grand_id')
            ->leftJoin('used_product_images', 'used_product_images.pro_id', '=', 'products.id')
            ->select(
                'products.id as proid',
                'products.category_id as category_id',
                FacadesDB::raw("JSON_EXTRACT(products.name, '$.$lang') as productname"),
                'products.featured as featured', 'products.status as status',
                'products.created_at as createdat', 'products.updated_at as updateat',
                'users.name as ownername',
                'brands.name as brandname',
                FacadesDB::raw("JSON_EXTRACT(categories.title, '$.$lang') as catname"),
                FacadesDB::raw("JSON_EXTRACT(subcategories.title, '$.$lang') as subcatname"),
                FacadesDB::raw("JSON_EXTRACT(grandcategories.title, '$.$lang') as childname"),
                'products.price as price',
                'used_product_images.main_image as mainimage'
                /*'products.vender_price as vender_price',
                'products.vender_offer_price as vender_offer_price'*/
            )
            ->where('products.deleted_at', '=', null)
            ->where('products.is_used', '=', 1)
            //->where('products.is_new',"=",0)
            ->get();
        //dd($products);
        if ($request->ajax()) {
            return DataTables::of($products)
                ->editColumn('checkbox', function ($row) {
                    $chk = "<div class='inline'>
                          <input type='checkbox' form='bulk_delete_form' class='filled-in material-checkbox-input' name='checked[]'' value='$row->proid' id='checkbox$row->proid'>
                          <label for='checkbox$row->proid' class='material-checkbox'></label>
                        </div>";

                    return $chk;
                })
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    $image = '';

                    if ($row->mainimage) {
                        $image .= '<img title='.str_replace('"', '', $row->productname).' class="pro-img" src='.url('usedProducts/thumbnails/'.$row->mainimage).' alt='.$row->mainimage.'>';
                    } else {
                        $image = '<img title="Make a variant first !" src="'.Avatar::create(str_replace('"', '', $row->productname))->toBase64().'"/>';
                    }

                    return $image;
                })
                ->addColumn('prodetail', function ($row) {
                    $html = '';
                    $html .= '<p><b>'.str_replace('"', '', $row->productname).'</b></p>';
                    $html .= "<p><b>Owner:</b> $row->ownername</p>";
                    $html .= "<p><b>Brand:</b> $row->brandname</p>";

                    return $html;
                })
                //->editColumn('price', function ($row) { return $row->price;})
                ->addColumn('catdtl', function ($row) {
                    $catdtl = '';
                    $catdtl .= '<p><i class="fa fa-angle-double-right"></i> '.str_replace('"', '', $row->catname).'</p>';

                    $catdtl .= '<p class="font-weight600"><i class="fa fa-angle-double-right"></i> '.str_replace('"', '', $row->subcatname).'</p>';

                    if ($row->childname) {
                        $catdtl .= '<p class="font-weight600"><i class="fa fa-angle-double-right"></i> '.str_replace('"', '', $row->childname).'</p>';
                    } else {
                        $catdtl .= '--';
                    }

                    return $catdtl;
                })
                ->editColumn('featured', 'admin.usedProduct.dtablecolumn.featured')
                ->editColumn('status', 'admin.usedProduct.dtablecolumn.status')
                ->addColumn('history', 'admin.usedProduct.dtablecolumn.history')
                ->editColumn('action', 'admin.usedProduct.dtablecolumn.action')
                ->rawColumns(['checkbox', 'image', 'prodetail', 'price', 'catdtl', 'featured', 'status', 'history', 'action'])
                ->make(true);
        }

        return view('admin.usedProduct.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload_info(Request $request)
    {
        $id = $request['catId'];

        $category = Category::findOrFail($id);
        $upload = $category
            ->subcategory
            ->where('parent_cat', $id)->pluck('title', 'id')
            ->all();

        return response()
            ->json($upload);
    }

    public function gcato(Request $request)
    {
        $id = $request['catId'];

        $category = Subcategory::findOrFail($id);

        $upload = $category
            ->childcategory
            ->where('subcat_id', $category->id)
            ->pluck('title', 'id')
            ->all();

        return response()
            ->json($upload);
    }

    public function create()
    {
        $categorys = Category::all();
        $brands = Brand::all();

        $users = User::where('status', 1)->get();

        $product = Product::all();

        return view('admin.usedProduct.create', compact('categorys', 'users', 'brands', 'product'));
    }

    public function saveImages(Request $request, $image)
    {
        $path = public_path().'/usedProducts';

        $thumbpath = public_path().'/usedProducts/thumbnails';

        if ($file = $request->file($image)) {
            $request->validate([
                $image => 'mimes:png,jpg,jpeg',
            ]);

            $name = 'usedProd_'.time().str_random(10).'.'.$file->getClientOriginalExtension();
            $img = Image::make($file);

            $img->save($path.'/'.$name, 95);

            $img->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
            });

            $img->save($thumbpath.'/'.$name, 95);

            return $name;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        $data = $this->validate($request,
            [
                'name' => 'required|string|max:255',
                'brand_id' => 'required|not_in:0|exists:brands,id',
                'category_id' => 'required|not_in:0|exists:categories,id',
                'child' => 'required|not_in:0|exists:subcategories,id',
                'grand_id' => 'required|not_in:0|exists:grandcategories,id',
                'price' => 'required|numeric',
                'user_id' => 'required|exists:users,id',
                'key_features' => 'string',
                'des' => 'string',
                'image1' => 'required',
                'image2' => 'required',
        ], [
            'name.required' => 'Product Name is needed',
            'price.required' => 'Price is needed',
            'brand_id.required' => 'Please Choose Brand',
        ]);

        $input = $request->all();
        $input['offer_price'] = $input['price'];
        $currency_code = Genral::first()->currency_code;

        if (isset($request->codcheck)) {
            $input['codcheck'] = '1';
        } else {
            $input['codcheck'] = '0';
        }

        if (isset($request->featured)) {
            $input['featured'] = '1';
        } else {
            $input['featured'] = '0';
        }

        if (isset($request->free_shipping)) {
            $input['free_shipping'] = '1';
        } else {
            $sid = Shipping::where('default_status', '1')->first();
            $input['shipping_id'] = $sid->id;
            $input['free_shipping'] = '0';
        }

        $input['price_in'] = $currency_code;

        if ($request->vender_price == '') {
            $input['vender_price'] = $request->price;
            $input['vender_offer_price'] = $request->offer_price;
        }

        $commissions = CommissionSetting::all();
        foreach ($commissions as $commission) {
            if ($commission->type == 'flat') {
                if ($commission->p_type == 'f') {
                    if (!isset($request->tax_r)) {
                        $price = $input['price'] + $commission->rate;
                        $offer = $input['offer_price'] + $commission->rate;

                        $input['price'] = $price;
                        $input['offer_price'] = $offer;
                        $input['commission_rate'] = $commission->rate;
                    } else {
                        $cit = $commission->rate * $input['tax_r'] / 100;
                        $price = $input['price'] + $commission->rate + $cit;
                        $offer = $input['offer_price'] + $commission->rate + $cit;

                        $input['price'] = $price;
                        $input['offer_price'] = $offer;
                        $input['commission_rate'] = $commission->rate + $cit;
                    }
                } else {
                    $taxrate = $commission->rate;
                    $price1 = $input['price'];
                    $price2 = $input['offer_price'];
                    $tax1 = $priceMinusTax = ($price1 * (($taxrate / 100)));
                    $tax2 = $priceMinusTax = ($price2 * (($taxrate / 100)));
                    $price = $input['price'] + $tax1;
                    $offer = $input['offer_price'] + $tax2;
                    $input['price'] = $price;
                    $input['offer_price'] = $offer;
                    if (!empty($tax2)) {
                        $input['commission_rate'] = $tax2;
                    } else {
                        $input['commission_rate'] = $tax1;
                    }
                }
            } else {
                $comm = Commission::where('category_id', $request->category_id)
                    ->first();
                if (isset($comm)) {
                    if ($comm->type == 'f') {
                        if (!isset($request->tax_manual)) {
                            $price = $input['price'] + $comm->rate;
                            $offer = $input['offer_price'] + $comm->rate;
                            $input['price'] = $price;
                            $input['offer_price'] = $offer;
                            $input['commission_rate'] = $comm->rate;
                        } else {
                            $cit = $commission->rate * $input['tax_r'] / 100;
                            $price = $input['price'] + $comm->rate + $cit;
                            $offer = $input['offer_price'] + $comm->rate + $cit;
                            $input['price'] = $price;
                            $input['offer_price'] = $offer;
                            $input['commission_rate'] = $comm->rate + $cit;
                        }
                    } else {
                        $taxrate = $comm->rate;
                        $price1 = $input['price'];
                        $price2 = $input['offer_price'];
                        $tax1 = $priceMinusTax = ($price1 * (($taxrate / 100)));
                        $tax2 = $priceMinusTax = ($price2 * (($taxrate / 100)));
                        $price = $input['price'] + $tax1;
                        $offer = $input['offer_price'] + $tax2;
                        $input['price'] = $price;
                        $input['offer_price'] = $offer;

                        if (!empty($tax2)) {
                            $input['commission_rate'] = $tax2;
                        } else {
                            $input['commission_rate'] = $tax1;
                        }
                    }
                }
            }
        }

        if ($request->return_avbls == '1') {
            $request->validate(['return_avbls' => 'required', 'return_policy' => 'required'], ['return_policy.required' => 'Please choose return policy']);

            if ($request->return_policy === 'Please choose an option') {
                return back()
                    ->with('warning', 'Please choose a return policy !');
            }
        }

        if ($request->return_avbls == '1') {
            $input['return_avbl'] = '1';
            $input['return_policy'] = $request->return_policy;
        } else {
            $input['return_avbl'] = 0;
            $input['return_policy'] = 0;
        }
        if ($request->user_id) {
            $input['vender_id'] = $request->user_id;
        } else {
            $input['vender_id'] = Auth::user()->id;
        }

        $findstore = null;
        $input['w_d'] = $request->w_d;
        $input['w_my'] = $request->w_my;
        $input['w_type'] = $request->w_type;
        $input['key_features'] = clean($request->key_features);
        $input['des'] = clean($request->des);
        $input['grand_id'] = isset($request->grand_id) ? $request->grand_id : 0;
        // $input['vender_id'] = $findstore->user->id;
        $input['is_used'] = 1;
        $input['is_new'] = 0;
        $data = Product::create($input);

        $data->save();

        $relsetting = new Related_setting();

        $relsetting->pro_id = $data->id;
        $relsetting->status = '0';
        $relsetting->save();
        $main_image = $this->saveImages($request, 'image1');
        usedProductImage::create([
        'image1' => $main_image,
        'image2' => $this->saveImages($request, 'image2'),
        'image3' => $this->saveImages($request, 'image3'),
        'image4' => $this->saveImages($request, 'image4'),
        'image5' => $this->saveImages($request, 'image5'),
        'image6' => $this->saveImages($request, 'image6'),
        'main_image' => $main_image,
        'pro_id' => $data->id,
       ]);

        return redirect()->route('used-products.index')->with('success', 'Product created ! ');
    }

    public function addSale(Request $request)
    {
        $salePrice = $request->salePrice;
        $pro_id = $request->pro_id;
        DB::table('products')
            ->where('id', $pro_id)->update(['offer_price' => $salePrice]);
        echo 'Added success';
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        session()->put('faqproduct', ['id' => $id]);
        $brands = Brand::all();
        $products = Product::find($id);
        $categorys = Category::all();

        $owners = User::all();

        $faqs = FaqProduct::where('pro_id', $id)->get();
        $cat_id = Product::where('id', $id)->first();
        $child = Subcategory::where('parent_cat', $cat_id->category_id)->get();
        // $realateds = RealatedProduct::get();
        $rel_setting = $products->relsetting;
        $grand = Grandcategory::where('subcat_id', $cat_id->child)
            ->get();

        return view('admin.usedProduct.edit_tab', compact('rel_setting', 'products', 'categorys', 'owners', 'brands', 'faqs', 'child', 'grand'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd('hello');
        $products = Product::findOrFail($id);
        $currency_code = Genral::first()->currency_code;
        $data = $this->validate($request, [
            'name' => 'required',
            'price' => 'required|numeric',
            'brand_id.required' => 'Please Choose Brand',
        ], [
            'name.required' => 'Product Name is needed',
            'price.required' => 'Price is needed',
        ]);

        $product = Product::findOrFail($id);

        $input = $request->all();

        if (isset($request->codcheck)) {
            $input['codcheck'] = '1';
        } else {
            $input['codcheck'] = '0';
        }

        if (isset($request->featured)) {
            $input['featured'] = '1';
        } else {
            $input['featured'] = '0';
        }

        $input['tax_r'] = null;
        $input['tax_name'] = null;
        $input['tax'] = $request->tax;

        $input['vender_price'] = $request->price;
        $input['vender_offer_price'] = $request->offer_price;

        $commissions = CommissionSetting::all();
        foreach ($commissions as $commission) {
            if ($commission->type == 'flat') {
                if ($commission->p_type == 'f') {
                    if (!isset($request->tax_r)) {
                        $price = $input['price'] + $commission->rate;
                        $offer = $input['offer_price'] + $commission->rate;

                        $input['price'] = $price;
                        $input['offer_price'] = $offer;
                        $input['commission_rate'] = $commission->rate;
                    } else {
                        $cit = $commission->rate * $input['tax_r'] / 100;
                        $price = $input['price'] + $commission->rate + $cit;
                        $offer = $input['offer_price'] + $commission->rate + $cit;

                        $input['price'] = $price;
                        $input['offer_price'] = $offer;
                        $input['commission_rate'] = $commission->rate + $cit;
                    }
                } else {
                    $taxrate = $commission->rate;
                    $price1 = $input['price'];
                    $tax1 = $priceMinusTax = ($price1 * (($taxrate / 100)));
                    //$tax2 = $priceMinusTax = ($price2 * (($taxrate / 100)));
                    $price = $input['price'] + $tax1;
                    //$offer = $input['offer_price'] + $tax2;
                    $input['price'] = $price;
                    //$input['offer_price'] = $offer;
                    if (!empty($tax2)) {
                        $input['commission_rate'] = $tax2;
                    } else {
                        $input['commission_rate'] = $tax1;
                    }
                }
            } else {
                $comm = Commission::where('category_id', $request->category_id)
                    ->first();
                if (isset($comm)) {
                    if ($comm->type == 'f') {
                        if (!isset($request->tax_manual)) {
                            $price = $input['price'] + $comm->rate;
                            $offer = $input['offer_price'] + $comm->rate;
                            $input['price'] = $price;
                            $input['offer_price'] = $offer;
                            $input['commission_rate'] = $comm->rate;
                        } else {
                            $cit = $commission->rate * $input['tax_r'] / 100;
                            $price = $input['price'] + $comm->rate + $cit;

                            if ($request->offer_price) {
                                $offer = $input['offer_price'] + $comm->rate + $cit;
                                $input['offer_price'] = $offer;
                            } else {
                                $input['offer_price'] = null;
                            }

                            $input['price'] = $price;

                            $input['commission_rate'] = $comm->rate + $cit;
                        }
                    } else {
                        $taxrate = $comm->rate;
                        $price1 = $input['price'];
                        $price2 = $input['offer_price'];
                        $tax1 = $priceMinusTax = ($price1 * (($taxrate / 100)));
                        $tax2 = $priceMinusTax = ($price2 * (($taxrate / 100)));
                        $price = $input['price'] + $tax1;
                        $offer = $input['offer_price'] + $tax2;
                        $input['price'] = $price;
                        $input['offer_price'] = $offer;

                        if (!empty($tax2)) {
                            $input['commission_rate'] = $tax2;
                        } else {
                            $input['commission_rate'] = $tax1;
                        }
                    }
                }
            }
        }

        if ($request->return_avbls == '1') {
            $request->validate(['return_avbls' => 'required', 'return_policy' => 'required'], ['return_policy.required' => 'Please choose return policy']);

            if ($request->return_policy === 'Please choose an option') {
                return back()
                    ->with('warning', 'Please choose a return policy !');
            }
        }

        if ($request->return_avbls == '1') {
            $input['return_avbl'] = '1';
            $input['return_policy'] = $request->return_policy;
        } else {
            $input['return_avbl'] = 0;
            $input['return_policy'] = 0;
        }

        if (isset($request->free_shipping)) {
            $input['free_shipping'] = '1';
            $input['shipping_id'] = null;
        } else {
            $sid = Shipping::where('default_status', '1')->first();
            $input['shipping_id'] = $sid->id;
            $input['free_shipping'] = '0';
        }

        //$findstore = Store::find($request->store_id);

        $input['price_in'] = $currency_code;
        $input['w_d'] = $request->w_d;
        $input['w_my'] = $request->w_my;
        $input['w_type'] = $request->w_type;
        $input['key_features'] = clean($request->key_features);
        $input['des'] = clean($request->des);
        $input['grand_id'] = isset($request->grand_id) ? $request->grand_id : 0;
        $input['vender_id'] = $input['owner_id'];
        $product->update($input);

        if (isset($request->image1)) {
            $this->saveImages($request, 'image1');
        }
        if (isset($request->image2)) {
            $this->saveImages($request, 'image2');
        }
        if (isset($request->image3)) {
            $this->saveImages($request, 'image3');
        }
        if (isset($request->image3)) {
            $this->saveImages($request, 'image3');
        }
        if (isset($request->image4)) {
            $this->saveImages($request, 'image4');
        }
        if (isset($request->image5)) {
            $this->saveImages($request, 'image5');
        }
        if (isset($request->image6)) {
            $this->saveImages($request, 'image6');
        }

        return back()->with('updated', 'Product has been updated !');
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if ($product->image != null) {
            $image_file = @file_get_contents(public_path().'/images/product/'.$product->image);

            if ($image_file) {
                unlink(public_path().'/images/product/'.$product->image);
            }
        }

        if (isset($value->subvariants)) {
            foreach ($$value->subvariants as $variant) {
                $variant->delete();
            }
        }

        $trash = $product->delete();

        if ($trash) {
            session()->flash('deleted', 'Product Has Been Deleted');

            return redirect('admin/products');
        }
    }

    public function prorelsetting(Request $request, $id)
    {
        $relsetting = Related_setting::where('pro_id', $id)->first();

        if (!isset($relsetting)) {
            $relsetting = new Related_setting();
            $relsetting->pro_id = $id;
            $relsetting->status = $request->status;
            $relsetting->save();

            return 'success';
        } else {
            $relsetting->status = $request->status;

            $relsetting->save();

            return 'success';
        }
    }

    public function relatedProductStore(Request $request, $id)
    {
        $input = $request->all();
        $data = RealatedProduct::where('product_id', '=', $id)->first();

        $request->validate(['related_pro' => 'required'], ['related_pro.required' => 'Please select a product !']);

        if (!isset($data)) {
            $newR = new RealatedProduct();
            $input['product_id'] = $id;
            $newR->create($input);

            return back()->with('added', 'Related Product Added !');
        } else {
            $input['product_id'] = $id;
            $data->update($input);

            return back()->with('updated', 'Related Product Updated !');
        }
    }
}
